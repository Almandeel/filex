<?php

namespace App\Http\Controllers;

use App\{Account, Entry};
use App\{Employee, Transaction};
use Illuminate\Http\Request;

class EntryController extends Controller
{
    public function __construct() {
        // $this->middleware('permission:entries-create')->only(['create', 'store']);
        $this->middleware('permission:entries-read')->only(['index', 'show']);
        $this->middleware('permission:entries-update')->only(['edit', 'update']);
        $this->middleware('permission:entries-delete')->only('destroy');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $entries = Entry::all();
        return view('dashboard.entries.index', compact('entries'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $rules = [
        'amount'      => 'required | numeric',
        'from_id'      => 'required | numeric|exists:accounts,id',
        'to_id'      => 'required | numeric|exists:accounts,id',
        ];
        if($request->_collaborate == 'debt'){
            $rules['amount'] = 'required | numeric | max:' . (Account::findOrFail($request->to_id)->balance());
        }
        
        $request->validate($rules);
        $entry = Entry::create($request->except(['_token', '_method']));
        if ($request->deducation) {
            $accountable = Account::findOrFail($request->to_id)->accountable;
            if (get_class($accountable) == Employee::class) {
                $data = [
                    'type' => Transaction::TYPE_DEDUCATION,
                    'amount' => $request->deducation,
                    'month' => date('Y-m'),
                    'safe_id' => $request->safe_id,
                    'created_at' => $request->created_at,
                    'employee_id' => $accountable->id,
                    'employee_account' => $accountable->account->id,
                    'details' => 'عبارة عن خصم من الموظف رقم: ' . $accountable->id,
                ];
                Transaction::create($data);
            }
        }
        return back()->with('success', 'تمت العملية بنجاح');
        
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Entry  $entry
    * @return \Illuminate\Http\Response
    */
    public function show(Entry $entry)
    {
        return view('dashboard.entries.show', compact('entry'));
        
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Entry  $entry
    * @return \Illuminate\Http\Response
    */
    public function edit(Entry $entry)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Entry  $entry
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Entry $entry)
    {
        $rules = [
        'amount'      => 'required | numeric',
        'from_id'      => 'required | numeric',
        'to_id'      => 'required | numeric',
        ];
        if($request->_collaborate == 'debt'){
            $rules['amount'] = 'required | numeric | max:' . (Account::findOrFail($request->to_id)->balance() + $entry->amount);
        }
        $request->validate($rules);
        
        $entry->update($request->except(['_token', '_method']));
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Entry  $entry
    * @return \Illuminate\Http\Response
    */
    public function destroy(Entry $entry)
    {
        $entry->delete();
        return back()->with('success', 'تمت عملية الحذف بنجاح');
    }
}