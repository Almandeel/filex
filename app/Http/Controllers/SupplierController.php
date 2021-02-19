<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Store;
use App\Account;
use App\{Supplier, Safe};
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    
    public function __construct() {
        $this->middleware('permission:suppliers-create')->only(['create', 'store']);
        $this->middleware('permission:suppliers-read')->only(['index', 'show']);
        $this->middleware('permission:suppliers-update')->only(['edit', 'update']);
        $this->middleware('permission:suppliers-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('dashboard.suppliers.index', compact('suppliers'));
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
            'phone'     => 'required|string|max:30|unique:suppliers',
        ]);

        $account = Account::newSupplier();
        $data = $request->except(['_token']);
        $data['account_id'] = $account->id;
        $supplier = Supplier::create($data);

        $store = Store::create($request->all());
        $store->users()->attach(auth()->user()->id);

        // if($request->stores) $supplier->stores()->attach($request->stores);
        $supplier->stores()->attach($store->id);

        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Supplier  $supplier
    * @return \Illuminate\Http\Response
    */
    public function show(Request $request, Supplier $supplier)
    {
        $stores = auth()->user()->getStores();
        $stores_ids = $stores->pluck('id')->toArray();
        $bills = Bill::where('supplier_id', $supplier->id);
        // $bills = $supplier->bills;
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $store_id = $request->store_id ? $request->store_id : 'all';
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        $bills = $bills->whereBetween('created_at', [$fromDate, $toDate]);
        
        if(!isset($request->store_id) || $request->store_id == 'all'){
            $stores_bills = Bill::where('supplier_id', $supplier->id)->whereBetween('created_at', [$fromDate, $toDate])->whereNull('bill_id')->whereIn('store_id', $stores_ids)->get();
            $out_bills = Bill::where('supplier_id', $supplier->id)->whereBetween('created_at', [$fromDate, $toDate])->whereNull('bill_id')->whereNotNull('bill_id')->get();
            $bills = $stores_bills->merge($out_bills);
        }
        else if($request->store_id == 'out'){
            $bills = $bills->whereNotNull('bill_id')->get();
        }
        else{
            $bills = $bills->where('store_id', $store_id)->whereNull('bill_id')->orderBy('created_at', 'DESC')->get();
        }
        // $bills = $bills->whereNull('bill_id')->orderBy('created_at', 'DESC')->get();
        $bills_advanced_search = $request->from_date || $request->store_id;
        
        $safes = Safe::all();
        $safes_ids = $safes->pluck('id')->toArray();
        $activeTab = $request->active_tab ? $request->active_tab : 'bills';
        $debts = $supplier->account->fromEntries->whereIn('to_id', $safes_ids)->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $credits = $supplier->account->toEntries->whereIn('from_id', $safes_ids)->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        $cheques = $supplier->cheques->whereBetween('created_at', [$fromDate.'', $toDate.'']);
        // $payments = $supplier->payments;
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
        return view('dashboard.suppliers.show', compact(
        'supplier', 'safes', 'cheques_using_dates', 'cheques_status', 'cheques_type',
        'cheques_advanced_search', 'activeTab', 'debts', 'cheques', 'credits', 'from_date', 'to_date',
        'bills_advanced_search', 'stores', 'store_id', 'bills'));
        
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Supplier  $supplier
    * @return \Illuminate\Http\Response
    */
    public function edit(Supplier $supplier)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Supplier  $supplier
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update($request->validate([
        'name'      => 'required|string|max:190',
        'phone'     => 'required|string|max:30|unique:suppliers,phone,'.$supplier->id,
        ]));
        
        if($request->stores) {
            $supplier->stores()->detach();
            $supplier->stores()->attach($request->stores);
        }
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Supplier  $supplier
    * @return \Illuminate\Http\Response
    */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            return back()->with('success', 'تم الحذف بنجاح');
        } catch (\Throwable $th) {
            return back()->with('error', 'فشلت العملية');
        }
    }
    
    public function showBill($bill_id) {
        $bill = Bill::find($bill_id);
        $title = $bill->bill ? 'فاتورة استلام' : 'فاتورة شراء';
        // dd($bill->entries);
        // dd($title);
        $billsSafes = Safe::billsSafes();
        return view('dashboard.suppliers.bill', compact('bill', 'title', 'billsSafes'));
    }
    
    public function statement(Request $request, Supplier $supplier){
        $bills = $supplier->bills;
        $from_date = $request->has('from_date') ? $request->from_date : null;
        $from_date = is_null($from_date) && $bills->count() ? $bills->first()->created_at->format('Y-m-d') : $from_date;
        $from_date = is_null($from_date) && !$bills->count() ? date('Y-m-d') : $from_date;
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        $from_date_time = $from_date . ' 00:00:00';
        $to_date_time = $to_date . ' 23:59:59';
        
        $bills = $bills->whereBetween('created_at', [$from_date_time, $to_date_time])->sortByDesc('created_at')->unique();
        // dd($bills);
        return view('dashboard.suppliers.statement', compact('supplier', 'bills', 'from_date', 'to_date'));
    }
}