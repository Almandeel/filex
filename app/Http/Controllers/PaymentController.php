<?php

namespace App\Http\Controllers;

use App\{Entry, Bill, Invoice, Payment};
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct() {
        $this->middleware('permission:payments-create')->only(['create', 'store']);
        $this->middleware('permission:payments-read')->only(['index', 'show']);
        $this->middleware('permission:payments-update')->only(['edit', 'update']);
        $this->middleware('permission:payments-delete')->only('destroy');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $payments = Entry::all();
        return view('dashboard.payments.index', compact('payments'));
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
        $bill = Bill::find($request->bill_id);
        $invoice = Invoice::find($request->invoice_id);
        $rules = [];
        $rules['amount'] = 'required|numeric';
        if ($bill) {
            $rules['amount'] = 'required|numeric|max:' . $bill->remain;
        }
        $request->validate($rules);
        if($bill){
            $payment = Payment::create([
                'amount' => $request->amount,
                'details' => $request->details,
                'from_id' => $bill->supplier->account_id,
                'to_id' => $request->safe_id,
                'safe_id' => $request->safe_id,
                'bill_id' => $bill->id,
            ]);
            
            $bill->refresh();
        }
        else if($invoice){
            $payment = Payment::create([
            'amount' => $request->amount,
            'details' => $request->details,
            'to_id' => $invoice->customer->account_id,
            'from_id' => $request->safe_id,
            'safe_id' => $request->safe_id,
            'invoice_id' => $invoice->id,
            ]);
            
            $invoice->refresh();
        }
        
        else{
            $payment = Payment::create($request->except(['_token', '_method']));
        }
        
        return back()->with('success', 'تمت العملية بنجاح');
        
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Entry  $customer
    * @return \Illuminate\Http\Response
    */
    public function show(Entry $customer)
    {
        return view('dashboard.payments.show', compact('customer'));
        
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Entry  $customer
    * @return \Illuminate\Http\Response
    */
    public function edit(Entry $customer)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Entry  $customer
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Entry $customer)
    {
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Entry  $customer
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Payment $payment)
    {
        $bill = $payment->bill;
        $invoice = $payment->invoice;
        $payment->delete();
        if($bill){
            $bill->refresh();
        }
        if($invoice){
            $invoice->refresh();
        }
        return back()->with('success', 'تمت عملية الحذف بنجاح');
    }
}