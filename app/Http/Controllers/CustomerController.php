<?php

namespace App\Http\Controllers;

use App\Account;
use App\{Customer, Invoice, Safe};
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct() {
        $this->middleware('permission:customers-create')->only(['create', 'store']);
        $this->middleware('permission:customers-read')->only(['index', 'show']);
        $this->middleware('permission:customers-update')->only(['edit', 'update']);
        $this->middleware('permission:customers-delete')->only('destroy');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $customers = Customer::all();
        return view('dashboard.customers.index', compact('customers'));
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
        'name'      => 'required|string|max:190',
        'phone'     => 'required|string|max:30|unique:customers',
        ]);
        
        $account = Account::newCustomer();
        $data = $request->except(['_token']);
        $data['account_id'] = $account->id;
        
        $customer = Customer::create($data);
        if($request->stores) $customer->stores()->attach($request->stores);
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Customer  $customer
    * @return \Illuminate\Http\Response
    */
    public function show(Request $request, Customer $customer)
    {
        $stores = auth()->user()->getStores();
        $stores_ids = $stores->pluck('id')->toArray();
        $invoices = Invoice::where('customer_id', $customer->id);
        // $invoices = $customer->invoices;
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $store_id = $request->store_id ? $request->store_id : 'all';
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        $invoices = $invoices->whereBetween('created_at', [$fromDate, $toDate]);
        
        if(!isset($request->store_id) || $request->store_id == 'all'){
            $stores_invoices = Invoice::where('customer_id', $customer->id)->whereBetween('created_at', [$fromDate, $toDate])->whereNull('invoice_id')->whereIn('store_id', $stores_ids)->get();
            $out_invoices = Invoice::where('customer_id', $customer->id)->whereBetween('created_at', [$fromDate, $toDate])->whereNull('invoice_id')->whereNotNull('bill_id')->get();
            $invoices = $stores_invoices->merge($out_invoices);
        }
        else if($request->store_id == 'out'){
            $invoices = $invoices->whereNotNull('bill_id')->get();
        }
        else{
            $invoices = $invoices->where('store_id', $store_id)->whereNull('invoice_id')->orderBy('created_at', 'DESC')->get();
        }
        // $invoices = $invoices->whereNull('invoice_id')->orderBy('created_at', 'DESC')->get();
        $bills_advanced_search = $request->from_date || $request->store_id;
        
        $safes = Safe::all();
        $safes_ids = $safes->pluck('id')->toArray();
        $activeTab = $request->active_tab ? $request->active_tab : 'invoices';
        $debts = $customer->account->fromEntries->whereIn('to_id', $safes_ids)->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $credits = $customer->account->toEntries->whereIn('from_id', $safes_ids)->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $cheques = $customer->cheques->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        // $payments = $customer->payments;
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
        
        // dd($customer->balance());
        return view('dashboard.customers.show', compact(
        'customer', 'safes', 'cheques_using_dates', 'cheques_status', 'cheques_type',
        'cheques_advanced_search', 'activeTab', 'debts', 'cheques', 'credits', 'from_date', 'to_date',
        'bills_advanced_search', 'stores', 'store_id', 'invoices'));
        
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Customer  $customer
    * @return \Illuminate\Http\Response
    */
    public function edit(Customer $customer)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Customer  $customer
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Customer $customer)
    {
        $customer->update($request->validate([
        'name'      => 'required|string|max:190',
        'phone'     => 'required|string|max:30|unique:customers,phone,'.$customer->id,
        ]));
        if($request->stores) {
            $customer->stores()->detach();
            $customer->stores()->attach($request->stores);
        }
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Customer  $customer
    * @return \Illuminate\Http\Response
    */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    public function statement(Request $request, Customer $customer){
        $invoices = $customer->invoices;
        $from_date = $request->has('from_date') ? $request->from_date : null;
        $from_date = is_null($from_date) && $invoices->count() ? $invoices->first()->created_at->format('Y-m-d') : $from_date;
        $from_date = is_null($from_date) && !$invoices->count() ? date('Y-m-d') : $from_date;
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        
        $invoices = $invoices->whereBetween('created_at', [$from_date_time, $to_date_time])->sortByDesc('created_at');
        // dd($invoices);
        return view('dashboard.customers.statement', compact('customer', 'invoices', 'from_date', 'to_date'));
    }
}