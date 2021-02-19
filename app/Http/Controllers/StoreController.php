<?php

namespace App\Http\Controllers;

use App\User;
use App\{Item, ItemStore, ItemStoreUnit};
use App\{Store, Supplier, Customer};
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct() {
        $this->middleware('permission:stores-create')->only(['create', 'store']);
        $this->middleware('permission:stores-read')->only(['index', 'show']);
        $this->middleware('permission:stores-update')->only(['edit', 'update']);
        $this->middleware('permission:stores-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $stores = auth()->user()->getStores();
        return view('dashboard.stores.index', compact('stores'));
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
            'name'      => 'required | string | max:45',
        ]);
        
        $store = Store::create($request->all());
        if($request->user_id) $store->users()->attach([$request->user_id]);
        session()->flash('success', 'تمت العملية بنجاح');
        
        return back();
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Store  $store
    * @return \Illuminate\Http\Response
    */
    public function show(Store $store)
    {
        $users = User::all();
        $items = Item::all();
        $suppliers = Supplier::all();
        $customers = Customer::all();
        // $store->resetBills();
        return view('dashboard.stores.show', compact('store', 'users', 'items', 'suppliers', 'customers'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Store  $store
    * @return \Illuminate\Http\Response
    */
    public function edit(Store $store)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Store  $store
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Store $store)
    {
        if($request->item_store_unit_id){
            try {
                $itemStoreUnit = ItemStoreUnit::findOrFail($request->item_store_unit_id);
                $itemStoreUnit->update($request->all());
                $itemStoreUnit->save();
                session()->flash('success', 'تمت عملية التعديل بنجاح');
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        else{
            $request->validate([
            'name' => 'required | string | max:45',
            ]);
            
            // dd($request->all());
            $store->update($request->all());
            if($request->user_id && !$store->users->contains($request->user_id)){
                $store->users()->attach([$request->user_id]);
            }
            
            elseif(!$request->user_id && $store->users->contains(auth()->user()->id)){
                $store->users()->attach([$request->user_id]);
            }
            
            session()->flash('success', 'تمت العملية بنجاح');
        }
        
        return back();
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Store  $store
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Store $store)
    {
        if($request->item_store_unit_id){
            try {
                ItemStoreUnit::destroy($request->item_store_unit_id);
                session()->flash('success', 'تم حذف الوحدة من المخزن بنجاح');
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        // else if($request->item_store_unit_id){
        //     session()->flash('success', 'تم حذف المستخدم من المخزن بنجاح');
        // }
        else{
            try {
                $store->delete();
                session()->flash('success', 'تم حذف المخزن بنجاح');
            } catch (\Throwable $th) {
                throw $th;
            }
        }
        return back();
    }
    
    public function addUser(Request $request){
        try {
            $store = Store::findOrFail($request->store_id);
            if(!$store->users->contains('id', $request->user_id)){
                $store->users()->attach([$request->user_id]);
            }
            session()->flash('success', 'تمت اضافة المستخدم للمخزن بنجاح');
        } catch (\Throwable $th) {
            throw $th;
        }
        $tab = isset($request->active_tab) ? $request->active_tab : 'default';
        return back()->with('tab', $tab);
    }
    
    public function removeUser(Request $request){
        $user = User::find($request->user_id);
        try {
            $store = Store::findOrFail($request->store_id);
            if($store->users->contains('id', $request->user_id)){
                $store->users()->detach($request->user_id);
            }
            session()->flash('success', 'تم حذف المستخدم من المخزن بنجاح');
        } catch (\Throwable $th) {
            throw $th;
        }
        $tab = isset($request->active_tab) ? $request->active_tab : 'default';
        return back()->with('tab', $tab);
    }
    
    public function addSupplier(Request $request){
        $tab = isset($request->active_tab) ? $request->active_tab : 'default';
        try {
            $store = Store::findOrFail($request->store_id);
            if(!$store->suppliers->contains('id', $request->supplier_id)){
                $store->suppliers()->attach([$request->supplier_id]);
            }
            session()->flash('success', 'تمت اضافة المورد للمخزن بنجاح');
        } catch (\Throwable $th) {
            throw $th;
        }
        return back()->with('tab', $tab);
    }
    
    public function removeSupplier(Request $request){
        $tab = isset($request->active_tab) ? $request->active_tab : 'default';
        try {
            $store = Store::findOrFail($request->store_id);
            if($store->suppliers->contains('id', $request->supplier_id)){
                $store->suppliers()->detach($request->supplier_id);
            }
            session()->flash('success', 'تم حذف المورد من المخزن بنجاح');
        } catch (\Throwable $th) {
            throw $th;
        }
        return back()->with('tab', $tab);
    }
    
    public function addCustomer(Request $request){
        try {
            $store = Store::findOrFail($request->store_id);
            if(!$store->customers->contains('id', $request->customer_id)){
                $store->customers()->attach([$request->customer_id]);
            }
            session()->flash('success', 'تمت اضافة العميل للمخزن بنجاح');
        } catch (\Throwable $th) {
            throw $th;
        }
        $tab = isset($request->active_tab) ? $request->active_tab : 'default';
        return back()->with('tab', $tab);
    }
    
    public function removeCustomer(Request $request){
        try {
            $store = Store::findOrFail($request->store_id);
            if($store->customers->contains('id', $request->customer_id)){
                $store->customers()->detach($request->customer_id);
            }
            session()->flash('success', 'تم حذف العميل من المخزن بنجاح');
        } catch (\Throwable $th) {
            throw $th;
        }
        $tab = isset($request->active_tab) ? $request->active_tab : 'default';
        return back()->with('tab', $tab);
    }
    
    public function addItem(Request $request){
        try {
            $store = Store::findOrFail($request->store_id);
            $store->items()->attach([$request->item_id]);
            session()->flash('success', 'تمت اضافة المنتج للمخزن بنجاح');
        } catch (\Throwable $th) {
            throw $th;
        }
        $tab = isset($request->active_tab) ? $request->active_tab : 'default';
        return back()->with('tab', $tab);
    }
    
    public function removeItem(Request $request){
        try {
            $store = Store::findOrFail($request->store_id);
            if($request->item_id){
                $store->items()->detach($request->item_id);
                session()->flash('success', 'تم حذف المنتج من المخزن بنجاح');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        $tab = isset($request->active_tab) ? $request->active_tab : 'default';
        return back()->with('tab', $tab);
    }
}