<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\{Account, Entry, Transfer};

class TransferController extends Controller
{
    public function __construct() {
        // $this->middleware('permission:transfers-create')->only(['create', 'store']);
        // $this->middleware('permission:transfers-read')->only(['index', 'show']);
        // $this->middleware('permission:transfers-update')->only(['edit', 'update']);
        // $this->middleware('permission:transfers-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $fromDate = $request->from_date ? $request->from_date . ' 00:00:00' : date('Y-m-d') . ' 00:00:00';
        $toDate = $request->to_date ? $request->to_date . ' 24:59:59' : date('Y-m-d') . ' 24:59:59';
        $account = $request->account_id ? Account::findOrFail($request->account_id) : Account::safe();
        $transfers = $account->transfers()->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        // dd($transfers, $from_date.'', $to_date.'');
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        return view('dashboard.transfers.index', compact('account', 'transfers', 'from_date', 'to_date'));
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
            'amount' => 'required | numeric',
            'from_id' => 'required | numeric',
            'to_id' => 'required | numeric',
        ];

        if($request->max){
            $rules['amount'] = 'required | numeric | max:' . $request->max;
        }else{
            $rules['amount'] = 'required | numeric';
        }

        $request->validate($rules);

        if($request->amount <= Account::capital()->balance() ) {
            $transfer = Transfer::create($request->except(['_token', '_method']));
            session()->flash('success', 'تمت العملية بنجاح');
        }else {
            session()->flash('error', 'الرصيد لا يكفي');
        }

        
        return back();
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\transfer  $transfer
    * @return \Illuminate\Http\Response
    */
    public function show(transfer $transfer)
    {
        return view('dashboard.transfers.show', compact('transfer'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\transfer  $transfer
    * @return \Illuminate\Http\Response
    */
    public function edit(transfer $transfer)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\transfer  $transfer
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, transfer $transfer)
    {
        $transfer->update($request->validate([
        'amount' => 'required | numeric | max:' . ($transfer->to->balance() + $transfer->amount),
        'from_id' => 'required | numeric',
        'to_id' => 'required | numeric',
        'details' => 'string',
        ]));
        $transfer->entry->update($request->all());
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\transfer  $transfer
    * @return \Illuminate\Http\Response
    */
    public function destroy(transfer $transfer)
    {
        $transfer->delete();
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
        
    }
}