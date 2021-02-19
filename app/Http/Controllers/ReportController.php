<?php

namespace App\Http\Controllers;

use PDF;
use App\{Account, Bill, Entry, Safe, User, Item};
use App\{Salary, Supplier, Customer, Invoice, InvoiceItem};
use App\Employee;
use App\{Store, ItemStore, ItemStoreUnit};
use Illuminate\Http\Request;
// use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection;
// use PHPJasper\PHPJasper;
use Storage;
use Response;
use File;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function salary($salary_id) {
        $salary = Salary::find($salary_id);
        $pdf = PDF::loadView('dashboard.reports.salary', compact('salary'));
        $pdf->stream('مرتب' . '.pdf');
    }
    
    public function salaries($employee_id) {
        $employee = Employee::find($employee_id);
        $pdf = PDF::loadView('dashboard.reports.salaries', compact('employee'));
        $pdf->stream('المرتبات' . '.pdf');
    }
    
    public function invoice(Request $request){
        $invoice = null;
        if($request->print){
            $pdf = PDF::loadView('reports.invoice', compact('invoice'));
            $pdf->stream('قائمة المنتجات' . '.pdf',  array("Attachment" => false));
        }else{
            return view('reports.invoice');
        }
    }
    
    
    public function bill($id) {
        $bill = Bill::find($id);
        $title = $bill->bill ? 'فاتورة استلام' : 'فاتورة شراء';
        $pdf = new PDF;
        $pdf = PDF::loadView('dashboard.reports.bill', compact('bill', 'title'));
        $pdf->stream("dompdf_out.pdf");
        
        
        // // $bill = Bill::find($id);
        // // $title = $bill->bill ? 'فاتورة استلام' : 'فاتورة شراء';
        // // $pdf = new PDF;
        // // $pdf->loadView('dashboard.reports.bill', compact('bill', 'title'));
        // // dd($pdf);
        // // $pdf->stream("dompdf_out.pdf", array("Attachment" => false));
        // // PDF::loadView('dashboard.reports.bill', compact('bill', 'title'))->save(public_path('reports/bills/bill.pdf'))->stream('bill.pdf');
        // // return response()->file(public_path('reports/bills/bill.pdf'));
        // // ->save(public_path('reports/bills/bill.pdf'));
        
        // // $pdf->stream('الفواتير' . '.pdf');
        
        // // $info = Larinfo::getInfo();
        // // dd($info);
        
        // return response()->file(public_path($this->print('bill', 'bills', ['BILL_ID' => $id])));
    }
    
    public function billReceipt($bill) {
        $bill = Bill::find($bill);
        $title = $bill->bill ? 'فاتورة استلام' : 'فاتورة شراء';
        $pdf = PDF::loadView('dashboard.reports.bill_receipt', compact('bill', 'title'));
        $pdf->stream('الفواتير' . '.pdf');
    }
    
    public static function generateReport() {
        //JasperPHP::compile(base_path('/vendor/cossou/jasperphp/examples/hello_world.jrxml'))->execute();
        $jasper = new JasperPHP;
        $filename = 'gau';
        $output = base_path('//public/reports/' . $filename);
        $jasper->process(
        base_path('/vendor/geekcom/jasperphp/examples/hello_world.jrxml'),
        $output,
        array("pdf"),
        array("test" => "Tax Invoice"),
        array(
        'driver' => env('DB_CONNECTION', 'mysql'),
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'shamel'),
        'username' => env('DB_USERNAME', 'admin'),
        'password' => env('DB_PASSWORD', 'admin'),
        )
        )->execute();
    }
    
    public function safes(Request $request){
        $safes = Safe::all();
        $users = User::all();
        $entries = Safe::entries();
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        $user_id = $request->user_id ? $request->user_id : auth()->user()->id;
        $safe_id = $request->safe_id ? $request->safe_id : "all";
        if($entries){
            if($request->safe_id){
                if($request->safe_id !== 'all'){
                    $searching_for =  $request->safe_id;
                    $entries = $entries->filter(function($entry) use ($searching_for) {
                        return
                        strstr($entry->from_id, $searching_for) ||strstr($entry->to_id, $searching_for);
                    });
                }
            }
            
            $entries = $entries->whereBetween('created_at', [$fromDate, $toDate]);
            if($request->user_id){
                if($request->user_id == 'all'){
                    $filtered = new Collection();
                    foreach ($users as $user) {
                        $filtered = $filtered->merge($entries->where('user_id', $user->id));
                    }
                    $entries = $filtered;
                }else{
                    $entries = $entries->where('user_id', $request->user_id);
                }
            }else{
                $entries = $entries->where('user_id', auth()->user()->id);
            }
        }
        return view('reports.safes', compact('entries', 'users', 'safes', 'user_id', 'safe_id', 'from_date', 'to_date'));
        
    }
    
    public function safe(Request $request){
        $safes = Safe::all();
        $safes_ids = $safes->pluck('id')->toArray();
        $safe = $request->safe_id ? Safe::findOrFail($request->safe_id) : $safes->first();
        $safe_id = $safe->id;
        $entries = Entry::where('from_id', $safe_id)->orwhere('to_id', $safe_id)->get();
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $opening_balance = 0;
        
        if(count($entries)){
            $first_date = $entries->first()->created_at->toDateTimeString();
            $before_date = date('Y-m-d', strtotime(Carbon::parse($from_date)->subDays(1)->toDateTimeString())) . ' 23:59:59';
            $beforeDebts = $entries->whereBetween('created_at', [$first_date, $before_date])->filter(function($entry) use($safe) { return $entry->from_id == $safe->id; });
            $beforeCredits = $entries->whereBetween('created_at', [$first_date, $before_date])->filter(function($entry) use($safe) { return $entry->to_id == $safe->id; });
            $opening_balance = ($beforeDebts->sum('amount') - $beforeCredits->sum('amount'));
        }
        
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        // $debts = Entry::whereBetween('created_at', [$fromDate, $toDate])->where('from_id', $safe_id)->orderBy('created_at', 'ASC')->get()->sortBy('created_at');
        // $credits = Entry::whereBetween('created_at', [$fromDate, $toDate])->where('to_id', $safe_id)->orderBy('created_at', 'ASC')->get()->sortBy('created_at');
        $entries = Entry::where('from_id', $safe_id)->orwhere('to_id', $safe_id)->get()->sortBy('created_at')->whereBetween('created_at', [$fromDate, $toDate]);
        // dd($entries);
        //return view('reports.safe', compact('entries', 'opening_balance', 'safes', 'safe', 'safe_id', 'from_date', 'to_date'));
        
        $debts = Entry::whereBetween('created_at', [$fromDate, $toDate])->where('from_id', $safe_id)->orderBy('created_at', 'DESC')->get();
        $credits = Entry::whereBetween('created_at', [$fromDate, $toDate])->where('to_id', $safe_id)->orderBy('created_at', 'DESC')->get();
        // dd($safe);
        return view('reports.safe', compact('debts', 'credits', 'opening_balance', 'safes', 'safe', 'safe_id', 'from_date', 'to_date'));
        
    }
    
    public function statement(Request $request){
        $account = Account::findOrFail($request->account_id);
        $entries = $account->entries();
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $opening_balance = ['amount' => 0, 'type' => null, 'date' => null, 'title' => ''];
        if($entries->count()){
            $from_date = !isset($request->from_date) ? $entries->first()->created_at->format('Y-m-d') : $from_date;
            $to_date = !isset($request->to_date) ? $entries->last()->created_at->format('Y-m-d') : $to_date;
        }
        $debts = $account->fromEntries;
        $credits = $account->toEntries;
        if(count($debts) || count($credits)){
            $first_debt_date = $debts->count() ? $debts->first()->created_at->toDateTimeString() : date('Y-m-d');
            $first_credit_date = $credits->count() ? $credits->first()->created_at->toDateTimeString() : date('Y-m-d');
            $before_date = date('Y-m-d', strtotime(Carbon::parse($from_date)->subDays(1)->toDateTimeString())) . ' 23:59:59';
            $beforeDebts = $debts->whereBetween('created_at', [$first_debt_date, $before_date]);
            $beforeCredits = $credits->whereBetween('created_at', [$first_credit_date, $before_date]);
            $debts_amount = $beforeDebts->sum('amount');
            $credits_amount = $beforeCredits->sum('amount');
            if($debts_amount || $credits_amount){
                if($debts_amount > $credits_amount){
                    $opening_balance['type'] = 'debt';
                    $opening_balance['title'] = 'مدين';
                    $opening_balance['amount'] = $debts_amount - $credits_amount;
                    
                }
                elseif($credits_amount > $debts_amount){
                    $opening_balance['title'] = 'دائن';
                    $opening_balance['type'] = 'credit';
                    $opening_balance['amount'] = $credits_amount - $debts_amount;
                    
                }
            }
        }
        
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        
        $debts = $debts->whereBetween('created_at', [$fromDate, $toDate])->sortByDesc('created_at');
        $credits = $credits->whereBetween('created_at', [$fromDate, $toDate])->sortByDesc('created_at');
        // dd($opening_balance, $credits);
        return view('reports.statement', compact('debts', 'credits', 'opening_balance', 'account', 'from_date', 'to_date'));
        
    }
    
    public function purchases(Request $request){
        $suppliers = auth()->user()->suppliers();
        $users = User::all();
        $stores = auth()->user()->getStores();
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        $store_id = $request->store_id ? $request->store_id : 'all';
        $user_id = $request->user_id ? $request->user_id : auth()->user()->id;
        $supplier_id = $request->supplier_id ? $request->supplier_id : "all";
        
        if(!isset($request->store_id) || $request->store_id == 'all'){
            $filtered = new Collection();
            foreach ($stores as $store) {
                $filtered = $filtered->merge(Bill::where('store_id', $store->id)->get());
            }
            $bills = $filtered;
        }else{
            $bills = Bill::where('store_id', $request->store_id)->get();
        }
        $bills = $bills->whereBetween('created_at', [$fromDate, $toDate]);
        
        
        if(!isset($request->supplier_id) || $request->supplier_id == 'all'){
            $filtered = new Collection();
            foreach ($suppliers as $supplier) {
                $filtered = $filtered->merge($bills->where('supplier_id', $supplier->id));
            }
            $bills = $filtered;
        }else{
            $bills = $bills->where('supplier_id', $request->supplier_id);
        }
        
        if($request->user_id){
            if($request->user_id == 'all'){
                $filtered = new Collection();
                foreach ($users as $user) {
                    $filtered = $filtered->merge($bills->where('user_id', $user->id));
                }
                $bills = $filtered;
            }else{
                $bills = $bills->where('user_id', $request->user_id);
            }
        }else{
            $bills = $bills->where('user_id', auth()->user()->id);
        }
        
        return view('reports.purchases', compact('bills', 'users', 'suppliers', 'user_id', 'stores', 'store_id', 'supplier_id', 'from_date', 'to_date'));
        
    }
    
    public function sells(Request $request){
        $customers = auth()->user()->customers();
        $users = User::all();
        $stores = auth()->user()->getStores();
        
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        $store_id = $request->store_id ? $request->store_id : 'all';
        $user_id = $request->user_id ? $request->user_id : auth()->user()->id;
        $customer_id = $request->customer_id ? $request->customer_id : "all";
        
        if(!isset($request->store_id) || $request->store_id == 'all'){
            $filtered = new Collection();
            $filtered = $filtered->merge(Invoice::whereNull('store_id')->get());
            $stores_ids = auth()->user()->getStores()->pluck('id')->toArray();
            $bills = Bill::whereBetween('created_at', [$fromDate, $toDate])->whereNull('bill_id')->whereIn('store_id', $stores_ids)->get();
            $invoices = Invoice::whereIn('bill_id', $bills->pluck('id')->toArray())->get();
            
            $invoices = $invoices->merge(Invoice::whereIn('store_id', $stores_ids)->get());
        }
        else if($request->store_id == 'out'){
            $bills = Bill::whereBetween('created_at', [$fromDate, $toDate])->whereNull('bill_id')->whereIn('store_id', $stores->pluck('id')->toArray())->get();
            $invoices = Invoice::whereIn('bill_id', $bills->pluck('id')->toArray())->get();
        }
        else{
            $invoices = Invoice::where('store_id', $request->store_id)->get();
        }
        
        $invoices = $invoices->whereBetween('created_at', [$fromDate, $toDate]);
        
        
        if(!isset($request->customer_id) || $request->customer_id == 'all'){
            $filtered = new Collection();
            foreach ($customers as $customer) {
                $filtered = $filtered->merge($invoices->where('customer_id', $customer->id));
            }
            $invoices = $filtered;
        }else{
            $invoices = $invoices->where('customer_id', $request->customer_id);
        }
        
        if($request->user_id){
            if($request->user_id == 'all'){
                $filtered = new Collection();
                foreach ($users as $user) {
                    $filtered = $filtered->merge($invoices->where('user_id', $user->id));
                }
                $invoices = $filtered;
            }else{
                $invoices = $invoices->where('user_id', $request->user_id);
            }
        }else{
            $invoices = $invoices->where('user_id', auth()->user()->id);
        }
        
        return view('reports.sells', compact('invoices', 'users', 'customers', 'user_id', 'stores', 'store_id', 'customer_id', 'from_date', 'to_date'));
        
    }
    
    public function profits(Request $request){
        $stores = auth()->user()->getStores();
        $stores_ids = $stores->pluck('id')->toArray();
        
        $items = Item::all();
        $items_ids = $items->pluck('id')->toArray();
        
        
        $invoices = Invoice::whereNull('invoice_id');
        $first_invoice = $invoices->get()->first();
        $last_invoice = $invoices->get()->last();
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        $from_date = (!isset($request->from_date) && $first_invoice) ? $first_invoice->created_at->format('Y-m-d') : $from_date;
        $to_date = (!isset($request->to_date) && $last_invoice) ? $last_invoice->created_at->format('Y-m-d') : $to_date;
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        $item_id = $request->item_id ? $request->item_id : "all";
        $store_id = $request->store_id ? $request->store_id : "all";
        
        $invoices = $invoices->whereBetween('created_at', [$fromDate, $toDate]);
        
        if(!isset($request->store_id) || $request->store_id == 'all'){
            $stores_invoices = $invoices->whereIn('store_id', $stores_ids)->get();
            $out_invoices = Invoice::whereNull('store_id')
            ->where('user_id', auth()->user()->id)
            ->whereBetween('created_at', [$fromDate, $toDate])->get();
            
            $invoices = $stores_invoices->merge($out_invoices);
        }
        else if($request->store_id == 'out'){
            $invoices = Invoice::whereNull('store_id')
            ->where('user_id', auth()->user()->id)
            ->whereBetween('created_at', [$fromDate, $toDate])->get();
        }
        else{
            $invoices = $invoices->where('store_id', $request->store_id)->get();
        }
        
        $invoices_ids = $invoices->pluck('id')->toArray();
        
        $invoices_items = InvoiceItem::whereNull('invoice_item_id')->whereIn('invoice_id', $invoices_ids);
        
        if(!isset($request->item_id) || $request->item_id == 'all'){
            $invoices_items = $invoices_items->whereIn('item_id', $items_ids)->orderBy('created_at', 'DESC')->paginate();
        }else{
            $invoices_items = $invoices_items->where('item_id', $request->item_id)->orderBy('created_at', 'DESC')->paginate();
        }
        
        $itemz = $invoices_items->groupBy('item_id');
        
        $store = isset($request->store_id) ? Store::find($request->store_id) : null;
        $item = isset($request->item_id) ? Item::find($request->item_id) : null;
        $title = $item ? 'ارباح وخسائر : منتج ' . $item->name : 'الارباح والخسائر';
        $title = $store ? $title . ' في مخزن: ' . $store->name : $title;
        $title = $store_id == 'out' ? $title . ' بيع مباشر' : $title;
        // dd($title, !$store && $store_id !== 'out');
        return view('reports.profits', compact('title', 'invoices_items', 'store', 'store_id', 'stores', 'item_id', 'item', 'items', 'itemz', 'from_date', 'to_date'));
        
    }
    
    public function stores(Request $request){
        $stores = Store::all();
        
        $store_id = $request->store_id ? $request->store_id : "all";
        $items_stores_units = [];
        if(!isset($request->store_id) || $request->store_id == 'all'){
            $stores_ids = $stores->pluck('id')->toArray();
            $items_stores = ItemStore::whereIn('store_id', $stores_ids)->get();
            $items_stores_ids = $items_stores->pluck('id')->toArray();
            $items_stores_units = ItemStoreUnit::whereIn('item_store_id', $items_stores_ids)->get();
        }else{
            $items_stores = ItemStore::where('store_id', $request->store_id)->get();
            $items_stores_ids = $items_stores->pluck('id')->toArray();
            $items_stores_units = ItemStoreUnit::whereIn('item_store_id', $items_stores_ids)->get();
        }
        $items_stores_units = $items_stores_units->sortByDesc('quantity');
        $store = isset($request->store_id) ? Store::find($request->store_id) : null;
        $title = $store ? 'مخزن ' . $store->name : 'المخازن';
        
        return view('reports.stores', compact('items_stores_units', 'store_id', 'stores', 'title'));
        
    }
    
    public function quantities(Request $request){
        $stores = auth()->user()->getStores();
        $store = $request->store_id ? Store::findOrFail($request->store_id) : $stores->first();
        
        $items_stores = ItemStore::where('store_id', $store->id)->get();
        
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        
        
        return view('reports.quantities', compact('stores', 'store', 'items_stores', 'from_date', 'to_date'));
        
    }
}
