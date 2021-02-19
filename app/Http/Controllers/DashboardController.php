<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Collection;

use App\{
    User, Employee, Role,
    Supplier, Customer, Collaborator,
    Bill, Invoice, BillItem, InvoiceItem, Cheque, Transfer,
    Safe, Store, ItemStore, ItemStoreUnit, Unit, Item, Expense, Payment,
};
class DashboardController extends Controller
{
    /**
    * Handle the incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function __invoke(Request $request)
    {

        $stores = auth()->user()->getStores();
        $stores_ids = $stores->pluck('id')->toArray();
        $users_ids = [];
        foreach ($stores as $store) {
            foreach ($store->users as $user) {
                if(!in_array($user->id, $users_ids)){
                    $users_ids[] = $user->id;
                }
            }
        }
        // dd($users_ids);
        $safes = Safe::all();
        $safes_ids = $safes->pluck('id')->toArray();
        
        
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        
        $billsCashEntries = new Collection();
        $billsBankEntries = new Collection();
        
        $invoicesCashEntries = new Collection();
        $invoicesBankEntries = new Collection();
        
        foreach (Safe::cashes() as $safe) {
            $billsCashEntries = $billsCashEntries->merge($safe->billsEntries($fromDate, $toDate, $users_ids));
            $invoicesCashEntries = $invoicesCashEntries->merge($safe->invoicesEntries($fromDate, $toDate, $users_ids));
        }
        
        foreach (Safe::banks() as $safe) {
            $billsBankEntries = $billsBankEntries->merge($safe->billsEntries($fromDate, $toDate, $users_ids));
            $invoicesBankEntries = $invoicesBankEntries->merge($safe->invoicesEntries($fromDate, $toDate, $users_ids));
        }
        
        // $billsCashEntries = $billsCashEntries->whereBetween('created_at', [$fromDate, $toDate]);
        // $invoicesCashEntries = $invoicesCashEntries->whereBetween('created_at', [$fromDate, $toDate]);
        // $billsBankEntries = $billsBankEntries->whereBetween('created_at', [$fromDate, $toDate]);
        // $invoicesBankEntries = $invoicesBankEntries->whereBetween('created_at', [$fromDate, $toDate]);
        
        $bills = Bill::whereNull('bill_id')->whereIn('store_id', $stores_ids)->whereIn('user_id', $users_ids)->whereBetween('created_at', [$fromDate, $toDate])->get();
        $billsItems = BillItem::whereIn('bill_id', $bills->pluck('id')->toArray())->get();
        $billsItemsCount = $billsItems->count();
        $billsItemsAmount = 0;
        foreach ($billsItems as $item) {
            $billsItemsAmount += $item->amount();
        }
        
        $invoices = Invoice::whereNull('invoice_id')->whereIn('store_id', $stores_ids)->whereIn('user_id', $users_ids)->whereBetween('created_at', [$fromDate, $toDate])->get();
        $bills = Bill::whereBetween('created_at', [$fromDate, $toDate])->whereNull('bill_id')->whereIn('store_id', $stores->pluck('id')->toArray())->get();
        $out_invoices = Invoice::whereBetween('created_at', [$fromDate, $toDate])->whereNull('invoice_id')->whereIn('bill_id', $bills->pluck('id')->toArray())->get();
        $invoices = $invoices->merge($out_invoices);
        $invoicesItems = InvoiceItem::whereIn('invoice_id', $invoices->pluck('id')->toArray())->get();
        $invoicesItemsCount = $invoicesItems->count();
        $invoicesItemsAmount = 0;
        
        foreach ($invoicesItems as $item) {
            $invoicesItemsAmount += $item->amount();
        }
        
        $stores_ids = $stores->pluck('id')->toArray();
        $items_stores = ItemStore::whereIn('store_id', $stores_ids)->get();
        $items_stores_ids = $items_stores->pluck('id')->toArray();
        $items = ItemStoreUnit::whereIn('item_store_id', $items_stores_ids)->get()->groupBy('item_store_id');
        return view('dashboard.index', compact('billsItemsCount', 'billsItemsAmount', 'invoicesItemsCount',
        'billsCashEntries', 'invoicesCashEntries', 'billsBankEntries', 'invoicesBankEntries',
        'invoicesItemsAmount', 'items', 'from_date', 'to_date'));
    }
    
    public function reset(Request $request){
        Schema::disableForeignKeyConstraints();
        
        // Removing employees ...
        if($request->employees){
            $employees = Employee::all()->except([auth()->user()->id]);
            foreach ($employees as $employee) {
                $employee->delete();
            }
        }
        
        // Removing users ...
        if($request->users){
            $users = User::all()->except([auth()->user()->id]);
            foreach ($users as $user) {
                $user->delete();
            }
        }
        
        // Removing roles ...
        if($request->roles){
            $roles = Role::all();
            foreach ($roles as $role) {
                $role->delete();
            }
        }
        
        // Removing suppliers ...
        if($request->suppliers){
            $suppliers = auth()->user()->suppliers();
            foreach ($suppliers as $supplier) {
                $supplier->account->reset();
            }
        }
        
        // Removing customers ...
        if($request->customers){
            $customers = auth()->user()->customers();
            foreach ($customers as $customer) {
                $customer->account->reset();
            }
        }
        
        // Removing collaborators ...
        if($request->collaborators){
            $collaborators = Collaborator::all();
            foreach ($collaborators as $collaborator) {
                $collaborator->account->reset();
            }
        }
        
        // Removing safes ...
        if($request->safes){
            $safes = Safe::all();
            foreach ($safes as $safe) {
                $safe->delete();
            }
        }
        
        // Removing stores ...
        if($request->stores){
            $stores = Store::all();
            foreach ($stores as $store) {
                $store->delete();
            }
        }
        
        // Removing units ...
        if($request->units){
            $units = Unit::all();
            foreach ($units as $unit) {
                $unit->delete();
            }
        }
        
        // Removing items ...
        if($request->items){
            $items = Item::all();
            foreach ($items as $item) {
                $item->delete();
            }
        }
        
        // Removing bills ...
        if($request->bills){
            $bills = Bill::all();
            foreach ($bills as $bill) {
                $bill->delete();
            }
            // truncating -> table
            // Bill::truncate();
            // BillItem::truncate();
        }
        
        // Removing invoices ...
        if($request->invoices){
            $invoices = Invoice::all();
            foreach ($invoices as $invoice) {
                $invoice->delete();
            }
            // truncating invoice table
            // Invoice::truncate();
            // InvoiceItem::truncate();
        }
        
        // Removing cheques ...
        if($request->cheques){
            $cheques = Cheque::all();
            foreach ($cheques as $cheque) {
                $cheque->delete();
            }
            // truncating cheque table
            // Cheque::truncate();
        }
        
        // Removing transfers ...
        if($request->transfers){
            $transfers = Transfer::all();
            foreach ($transfers as $transfer) {
                $transfer->delete();
            }
            // truncating transfer table
            // Transfer::truncate();
        }
        
        // Removing expenses ...
        if($request->expenses){
            $expenses = Expense::all();
            foreach ($expenses as $expense) {
                $expense->delete();
            }
            // truncating expense table
            // Expense::truncate();
        }
        
        // Removing payments ...
        if($request->payments){
            $payments = Payment::all();
            foreach ($payments as $payment) {
                $payment->delete();
            }
            // truncating payment table
            // Payment::truncate();
        }
        
        Schema::enableForeignKeyConstraints();
        return redirect()->route('dashboard.index')->with('success', 'تمت عملية التهيئة بنجاح');
    }
    
    
    public function example() {
        // dd();
        return response()->download(public_path('dashboard/example/example.xlsx'));
    }


    public function index() {
        if (auth()->user()->roles->contains(Role::market()->id) && auth()->user()->roles->count() != 1 || auth()->user()->roles->contains(Role::cashier()->id) && auth()->user()->roles->count() != 1) {
            return view('dashboard.index');
        }

        if (auth()->user()->roles->contains(Role::cashier()->id) && auth()->user()->roles->count() == 1) {
            return redirect()->route('cashier.dashboard');
        }

        if (auth()->user()->roles->contains(Role::market()->id) && auth()->user()->roles->count() == 1) {
            return redirect()->route('market.pos');
        }

        return view('dashboard.index');
    }
}
