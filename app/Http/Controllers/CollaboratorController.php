<?php

namespace App\Http\Controllers;

use App\Account;
use App\{Collaborator, Safe};
use Illuminate\Http\Request;

class CollaboratorController extends Controller
{
    public function __construct() {
        $this->middleware('permission:collaborators-create')->only(['create', 'store']);
        $this->middleware('permission:collaborators-read')->only(['index', 'show']);
        $this->middleware('permission:collaborators-update')->only(['edit', 'update']);
        $this->middleware('permission:collaborators-delete')->only('destroy');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $collaborators = Collaborator::all();
        return view('dashboard.collaborators.index', compact('collaborators'));
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
        'name'      => 'required | string | max:190',
        'phone'     => 'required | string | max:30',
        ]);
        
        $account = Account::newCollaborator();
        $data = $request->except(['_token']);
        $data['account_id'] = $account->id;
        
        $collaborator = Collaborator::create($data);
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Collaborator  $collaborator
    * @return \Illuminate\Http\Response
    */
    public function show(Request $request, Collaborator $collaborator)
    {
        $activeTab = $request->active_tab ? $request->active_tab : 'debts';
        $fromDate = $request->from_date ? $request->from_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $toDate = $request->to_date ? $request->to_date . ' 23:59:59' : date('Y-m-d') . ' 23:59:59';
        $debts = $collaborator->fromEntries()->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $credits = $collaborator->toEntries()->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $cheques = $collaborator->cheques->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $safes = Safe::all();
        
        if($request->cheques_using_dates && ($request->from_date || $request->to_date)){
            $cheques = $cheques->whereBetween('due_date', [$from_date, $to_date]);
        }
        if($request->cheques_type && $request->cheques_type != 111001){
            $cheques = $cheques->where('cheques_type', $request->cheques_type);
        }
        if($request->cheques_status && $request->cheques_status != 111001){
            $cheques = $cheques->where('cheques_status', $request->cheques_status);
        }
        $cheques_type = $request->cheques_type ? $request->cheques_type : 111001;
        $cheques_status = $request->cheques_status ? $request->cheques_status : 111001;
        $cheques_using_dates = $request->cheques_using_dates ? $request->cheques_using_dates : 0;
        
        $cheques_advanced_search = ($request->cheques_using_dates && ($request->from_date || $request->to_date)) || ($request->cheques_type && $request->cheques_type != 111001) || ($request->cheques_status && $request->cheques_status != 111001);
        return view('dashboard.collaborators.show', compact('collaborator', 'safes', 'cheques_using_dates', 'cheques_status', 'cheques_type', 'cheques_advanced_search', 'activeTab', 'debts', 'cheques', 'credits', 'from_date', 'to_date'));
        
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Collaborator  $collaborator
    * @return \Illuminate\Http\Response
    */
    public function edit(Collaborator $collaborator)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Collaborator  $collaborator
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Collaborator $collaborator)
    {
        $collaborator->update($request->validate([
        'name'      => 'required | string | max:190',
        'phone'     => 'required | string | max:30',
        ]));
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Collaborator  $collaborator
    * @return \Illuminate\Http\Response
    */
    public function destroy(Collaborator $collaborator)
    {
        $collaborator->delete();
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
}