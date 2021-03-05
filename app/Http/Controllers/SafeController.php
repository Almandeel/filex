<?php

namespace App\Http\Controllers;

use App\Safe;
use App\Account;
use App\ExpensesType;
use Illuminate\Http\Request;

class SafeController extends Controller
{
    public function __construct() {
        $this->middleware('permission:safes-create')->only(['create', 'store']);
        $this->middleware('permission:safes-read')->only(['index', 'show']);
        $this->middleware('permission:safes-update')->only(['edit', 'update']);
        $this->middleware('permission:safes-delete')->only('destroy');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $safes = Safe::all();
        return view('dashboard.safes.index', compact('safes'));
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
        $request->validate([
        'name' => 'required | string | max:190',
        ]);
        
        $data = $request->except(['_token']);
        $data['show_bills'] = isset($request->show_bills);
        $data['show_invoices'] = isset($request->show_invoices);
        $data['show_expenses'] = isset($request->show_expenses);
        $data['show_cheques'] = isset($request->show_cheques);
        // dd($data);

        $safe = Safe::create($data);
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Safe  $safe
    * @return \Illuminate\Http\Response
    */
    public function show(Request $request, Safe $safe)
    {
        $activeTab = $request->active_tab ? $request->active_tab : 'payments';
        $fromDate = $request->from_date ? $request->from_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $toDate = $request->to_date ? $request->to_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
        $transfers = $safe->transfers()->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $expenses = $safe->expenses->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $payments = $safe->payments->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $cheques = $safe->cheques->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $safes = Safe::all();
        $expenses_types = ExpensesType::all();

        // dd($transfers->first()->to);
        return view('dashboard.safes.show', compact('expenses_types', 'safe', 'activeTab', 'safes', 'expenses', 'cheques', 'payments', 'transfers', 'from_date', 'to_date'));
        
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Safe  $safe
    * @return \Illuminate\Http\Response
    */
    public function edit(Safe $safe)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Safe  $safe
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Safe $safe)
    {
        if($request->update_show){
            $column = $request->column;
            $safe->$column = (int) $request->status;
            $safe->save();
        }else{
            $request->validate([
            'name'      => 'required | string | max:190',
            ]);
            
            $data = $request->except(['_token']);
            $data['show_bills'] = isset($request->show_bills);
            $data['show_invoices'] = isset($request->show_invoices);
            $data['show_expenses'] = isset($request->show_expenses);
            $data['show_cheques'] = isset($request->show_cheques);
            
            $safe->update($data);
            
        }
        session()->flash('success', 'تمت العملية بنجاح');
        return back();
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Safe  $safe
    * @return \Illuminate\Http\Response
    */
    public function destroy(Safe $safe)
    {
        $safe->delete();
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
}