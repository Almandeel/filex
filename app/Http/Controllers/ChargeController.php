<?php

namespace App\Http\Controllers;

use App\{Charge, Bill, Invoice};
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    public function __construct() {
        $this->middleware('permission:charges-create')->only(['create', 'store']);
        $this->middleware('permission:charges-read')->only(['index', 'show']);
        $this->middleware('permission:charges-update')->only(['edit', 'update']);
        $this->middleware('permission:charges-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $charges = Charge::all();
        return view('dashboard.charges.index', compact('charges'));
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
        $charge = Charge::create($request->validate([
        'amount' => 'required | string',
        'details' => 'required | string',
        ]));
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\charge  $charge
    * @return \Illuminate\Http\Response
    */
    public function show(Charge $charge)
    {
        return view('dashboard.charges.show', compact('charge'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\charge  $charge
    * @return \Illuminate\Http\Response
    */
    public function edit(Charge $charge)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\charge  $charge
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, charge $charge)
    {
        $charge->update($request->validate([
        'amount' => 'required | string',
        'details' => 'required | string',
        ]));
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\charge  $charge
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Charge $charge)
    {
        if($request->bill_id){
            $bill = Bill::findOrFail($request->bill_id);
            $bill->refresh();
        }
        else if($request->invoice_id){
            $invoice = Invoice::findOrFail($request->invoice_id);
            
            $invoice->refresh();
        }
        $charge->delete();
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
        
    }
}