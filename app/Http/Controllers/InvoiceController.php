<?php

namespace App\Http\Controllers;

use App\Entry;
use App\Store;
use App\Charge;
use App\Account;
use App\Customer;
use Carbon\Carbon;
use App\InvoiceItem;
use App\ItemStoreUnit;
use Illuminate\Http\Request;
use App\{Invoice, Bill, BillItem};
use Illuminate\Support\Collection;
use App\{Cheque, Safe, Payment, Expense};
// use JasperPHP\JasperPHP as JasperPHP;
class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:invoices-create')->only(['create','store']);
        $this->middleware('permission:invoices-read')->only(['index', 'show']);
        $this->middleware('permission:invoices-update')->only(['edit', 'update']);
        $this->middleware('permission:invoices-delete')->only('destroy');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        /*
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');

        
        // $from_date = !isset($request->from_date) && $invoices->get()->count() ? $invoices->get()->first()->created_at->format('Y-m-d') : $from_date;
        // $to_date = !isset($request->from_date) && $invoices->get()->count() ? $invoices->get()->last()->created_at->format('Y-m-d') : $from_date;
        
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        $invoices = $invoices->whereBetween('created_at', [$fromDate, $toDate])->orderBy('created_at', 'DESC');
        */
        $limit = $request->limit ? $request->limit : 50;
        $stores = auth()->user()->getStores();
        $customers = auth()->user()->customers();
        $stores_ids = $stores->pluck('id')->toArray();
        $customers_ids = $customers->pluck('id')->toArray();
        $store_id = $request->store_id ? $request->store_id : 'all';
        $customer_id = $request->customer_id ? $request->customer_id : 'all';

        $from_date = Carbon::parse($request->from_date ?? now()->startOfDay());
        $to_date = Carbon::parse($request->to_date ?? now())->endOfDay();

        $invoices = Invoice::with('user')->with('customer')->with('store')
            ->whereNull('invoice_id')
            ->whereBetween('created_at', [$from_date, $to_date])
            ->when($store_id != 'all', function ($q) use($request) {return $q->where('store_id', $request->store_id);})
            ->when($customer_id != 'all', function ($q) use($request) {return $q->where('customer_id', $request->customer_id);})
            ->orderBy('created_at', 'DESC')
            ->paginate();
            
        
        // if(!isset($request->store_id) || $request->store_id == 'all'){
        //     $stores_invoices = Invoice::with('user')->with('customer')->with('store')->whereBetween('created_at', [$fromDate, $toDate])->whereNull('invoice_id')->whereIn('store_id', $stores_ids)->limit($limit)->get();
        //     $bills = Bill::whereBetween('created_at', [$fromDate, $toDate])->whereNull('bill_id')->whereIn('store_id', $stores->pluck('id')->toArray())->get();
        //     $out_invoices = Invoice::with('user')->with('customer')->with('store')->whereBetween('created_at', [$fromDate, $toDate])->whereNull('invoice_id')->whereIn('bill_id', $bills->pluck('id')->toArray())->limit($limit)->get();
        //     $invoices = $stores_invoices->merge($out_invoices);
        // }
        // else if($request->store_id == 'out'){
        //     $bills = Bill::whereBetween('created_at', [$fromDate, $toDate])->whereNull('bill_id')->whereIn('store_id', $stores->pluck('id')->toArray())->get();
        //     $invoices = $invoices->whereIn('bill_id', $bills->pluck('id')->toArray())->get();
        // }
        // else{
        //     $invoices = $invoices->where('store_id', $store_id)->orderBy('created_at', 'DESC')->get();
        // }
        
        // if(!isset($request->customer_id) || $request->customer_id == 'all'){
        //     $invoices = $invoices->whereIn('customer_id', $customers_ids);
        // }
        // else{
        //     $invoices = $invoices->where('customer_id', $customer_id);
        // }
        
        // $is_payed = isset($request->is_payed) ? $request->is_payed : 'both';
        // if(isset($request->is_payed) && $request->is_payed != 'both'){
        //     $invoices = $invoices->filter(function($invoice) use ($is_payed){
        //         return (bool) $is_payed ? $invoice->isPayed() : !$invoice->isPayed();
        //     });
        // }
        
        // $is_delivered = isset($request->is_delivered) ? $request->is_delivered : 'both';
        // if(isset($request->is_delivered) && $request->is_delivered != 'both'){
        //     $invoices = $invoices->filter(function($invoice) use ($is_delivered){
        //         return (bool) $is_delivered ? $invoice->isDelivered() : !$invoice->isDelivered();
        //     });
        // }
        
        // $invoices = $invoices->sortByDesc('created_at');
        // $advanced_search = isset($request->from_date) || isset($request->store_id) || isset($request->customer_id) || isset($request->is_delivered) || isset($request->is_payed);
        $advanced_search = true;
        return view('dashboard.invoices.index', compact('advanced_search', 'limit', 'stores', 'store_id', 'customers', 'customer_id', 'invoices', 'from_date', 'to_date'));
        
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {
        $customers = auth()->user()->customers();
        $stores = auth()->user()->getStores();
        $invoice = $request->invoice_id ? Invoice::findOrFail($request->invoice_id) : null;
        $bill = $request->bill_id ? Bill::findOrFail($request->bill_id) : null;
        $store = $request->store_id ? Store::find($request->store_id) : null;
        $store = count($stores) && $store == null ? $stores->first() : $store;
        
        $invoicesSafes = Safe::invoicesSafes();
        $expensesSafes = Safe::expensesSafes();
        return view('dashboard.invoices.create', compact('invoicesSafes', 'expensesSafes', 'stores', 'store', 'customers', 'invoice', 'bill'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if($request->invoice_id){
            if($request->items){
                // $receivedItems = array_filter($request->quantities);
                // $chargeAmount = $request->chargesSafe + $request->chargesBank;
                // $chargePerItem = count($receivedItems) ? $chargeAmount / count($receivedItems) : 0;
                $invoice = Invoice::create([
                'invoice_id' => $request->invoice_id
                ]);
                
                for ($i=0; $i < count($request->items); $i++) {
                    $item = $request->items[$i];
                    $quantity = $request->quantities[$i];
                    if($quantity > 0){
                        $invoiceItem = InvoiceItem::findOrFail($item);
                        $itemStoreUnit = $invoiceItem->itemStoreUnit;
                        
                        $receiveInvoiceItem = InvoiceItem::create([
                        'item_store_unit_id' => $itemStoreUnit->id,
                        'item_id' => $itemStoreUnit->itemStore->item_id,
                        'unit_id' => $itemStoreUnit->itemUnit->unit_id,
                        'invoice_id' => $invoice->id,
                        'invoice_item_id' => $invoiceItem->id,
                        'quantity' => $quantity,
                        // 'expenses' => $chargePerItem,
                        ]);
                    }
                }
                
                if($request->modal)
                return back()->with('success', 'تمت اضافة فاتورة التسليم بنجاح');
                
            }else{
                return back()->with('error', 'لا توجد منتجات في الفاتورة');
            }
            
        }
        else{
            $invoice = $this->setInvoice($request);
            if($invoice == false)
            return back()->with('error', 'لم يتم اختيار عميل');
        }
        if($request->bill_id){
            $bill = Bill::findOrFail($request->bill_id);
            return redirect()->route('bills.show', $bill)->with('success', 'تم انشاء الفاتورة البيع بنجاح');
        }
        return redirect()->route('invoices.show', $invoice)->with('success', 'تم انشاء الفاتورة بنجاح');
        
    }
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show(Invoice $invoice)
    {
        $title = $invoice->invoice ? 'فاتورة تسليم' : 'فاتورة بيع';
        $billsSafes = Safe::invoicesSafes();
        return view('dashboard.invoices.show', compact('invoice', 'billsSafes', 'title'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request, Invoice $invoice)
    {
        if($invoice->bill_id){
            return back()->with('error', 'لا يمكن تعديل فاتورة من مورد خارجي');
        }
        $customers = auth()->user()->customers();
        $stores = auth()->user()->getStores();
        $store = $request->store_id ? Store::find($request->store_id) : null;
        $store = count($stores) && $store == null ? $invoice->store : $store;
        $cheque = $invoice->cheques->count() ? $invoice->cheques->first() : null;
        $safeId = Account::safe()->id;
        // dd($invoice->cheques);
        $invoicesSafes = Safe::invoicesSafes();
        return view('dashboard.invoices.edit', compact('invoice', 'invoicesSafes', 'stores', 'store', 'customers', 'cheque'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Invoice $invoice)
    {
        if($this->setInvoice($request, $invoice))
        return back()->with('success', 'تم تعديل الفاتورة بنجاح');
        else
            return back()->with('error', 'لم يتم اختيار عميل');
    }
    
    private function setInvoice($request, $invoice = null){
        if(isset($request->customer_id)){
            // $receivedCount = $request->receives ? count(array_filter($request->receives)) : 0;
            // $chargeAmount = $request->expenses_amounts ? array_sum($request->expenses_amounts) : 0;
            // $chargePerItem = $receivedCount ? $chargeAmount / $receivedCount : 0;
            // $payed = $request->payments_amounts ? array_sum($request->payments_amounts) : 0;
            
            $bill = isset($request->bill_id) ? Bill::findOrFail($request->bill_id) : null;
            $receiveBill = $bill ? Bill::create(['bill_id' => $bill->id]) : null;
            if($invoice){
                $invoice->reset(false);
                $invoice->update($request->except(['_token', '_method']));
            }else{
                $invoice = Invoice::create($request->except(['_token', '_method']));
            }
            $total = 0;
            $totalQ = 0;
            $totalP = 0;
            $remain = 0;
            $payed = isset($request->payments_amounts) ? array_sum($request->payments_amounts) : 0;
            $receiveInvoice = Invoice::create([
            'invoice_id' => $invoice->id
            ]);
            for ($i=0; $i < count($request->units); $i++) {
                $unit = $request->units[$i];
                $quantity = $request->quantities[$i];
                $purchase = $request->purchases[$i];
                $sell = $request->sells[$i];
                $receive = $bill || !isset($request->receives) ? null : $request->receives[$i];
                $billItem = $bill ? BillItem::findOrFail($request->billItems[$i]) : null;
                if($quantity > 0 && $sell > 0){
                    $itemStoreUnit = ItemStoreUnit::findOrFail($unit);
                    $totalQ += $quantity;
                    $totalP += $sell;
                    $total += $quantity * $sell;
                    
                    $invoiceItemData = [
                    // 'item_store_unit_id' => $itemStoreUnit->id,
                    'item_id' => $itemStoreUnit->itemStore->item_id,
                    'unit_id' => $itemStoreUnit->itemUnit->unit_id,
                    'invoice_id' => $invoice->id,
                    'quantity' => $quantity,
                    'price_purchase' => $purchase,
                    'price_sell' => $sell,
                    ];
                    $newBillItem = null;
                    if($billItem){
                        $newBillItem = BillItem::create([
                        // 'item_store_unit_id' => $billItem->itemStoreUnit->id,
                        'item_id' => $billItem->item_id,
                        'unit_id' => $billItem->unit_id,
                        'bill_id' => $receiveBill->id,
                        'bill_item_id' => $billItem->id,
                        'quantity' => $quantity,
                        'price' => $billItem->price,
                        'expense' => 0,
                        'expenses' => 0,
                        ]);
                        $invoiceItemData['bill_item_id'] = $newBillItem->id;
                    }else{
                        $invoiceItemData['item_store_unit_id'] = $itemStoreUnit->id;
                    }
                    $invoiceItem = InvoiceItem::create($invoiceItemData);
                    $receiveInvoiceItem = null;
                    if($newBillItem){
                        $receiveInvoiceItem = InvoiceItem::create([
                        // 'item_store_unit_id' => $billItem->itemStoreUnit->id,
                        'item_id' => $billItem->item_id,
                        'unit_id' => $billItem->unit_id,
                        'invoice_id' => $receiveInvoice->id,
                        'invoice_item_id' => $invoiceItem->id,
                        'quantity' => $quantity,
                        ]);
                        
                    }else{
                        if($receive){
                            $receiveInvoiceItem = InvoiceItem::create([
                            'item_store_unit_id' => $itemStoreUnit->id,
                            'item_id' => $itemStoreUnit->itemStore->item_id,
                            'unit_id' => $itemStoreUnit->itemUnit->unit_id,
                            'invoice_id' => $receiveInvoice->id,
                            'invoice_item_id' => $invoiceItem->id,
                            'quantity' => $receive,
                            // 'expenses' => $chargePerItem,
                            ]);
                            // $total += $chargePerItem;
                        }
                    }
                    if($receiveInvoiceItem){}
                }
            }
            
            $remain = $total - $payed;
            
            $invoice->withEntry();
            if(count($receiveInvoice->items) < 1){
                $receiveInvoice->delete();
            }
            // else{
            //     $receiveInvoice->withCharge(Account::safe()->id, $request->chargesSafe, 'مصاريف (نقدية) لفاتورة تسليم رقم: ' . $receiveInvoice->id)
            //     ->withCharge(Account::bank()->id, $request->chargesBank, 'مصاريف (بنك) لفاتورة تسليم رقم: ' . $receiveInvoice->id);
            // }
            if($request->payments_safes){
                for ($i=0; $i < count($request->payments_safes); $i++) {
                    $safe = $request->payments_safes[$i];
                    $amount = $request->payments_amounts[$i];
                    if($amount){
                        $payment = Payment::create([
                        'amount' => $amount,
                        'details' => 'دفعة لفاتورة بيع رقم: ' . $invoice->id,
                        'to_id' => $invoice->customer->account_id,
                        'from_id' => $safe,
                        'safe_id' => $safe,
                        'invoice_id' => $invoice->id,
                        ]);
                    }
                }
            }
            
            
            if($request->chequeAmount){
                $invoice->cheque($request->chequeAmount, $request->chequeBank, $request->chequeNumber, $request->chequeDueDate, $request->chequeAccount);
            }
            
            // if($request->chargesSafe || $request->chargesBank){
            //     $receiveInvoice->invoice->withPrice(true);
            // }
            if($invoice->amount !== $total || $invoice->payed !== $payed || $invoice->remain !== $remain){
                $invoice->update([
                'amount' => $total,
                'payed' => $payed,
                'remain' => $remain,
                ]);
            }
            return $invoice;
        }
        return false;
    }
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Invoice $invoice)
    {
        $fromBill = $invoice->bill_id ? true : false;
        $invoice->delete();
        $primaryInvoice = $invoice->invoice;
        $storesItems = [];
        foreach ($invoice->items as $item) {
            if ($item->itemStoreUnit) {
                $storesItems[$item->itemStoreUnit->id] = $item->items->sum('quantity');
            }
        }
        if($primaryInvoice) $primaryInvoice->refresh();
        $prev_url = route('invoices.destroy', $invoice);
        foreach ($storesItems as $item => $value) {
            $itemStore = ItemStoreUnit::find($item);
            $itemStore->update(['quantity' => $itemStore->quantity + $value]);
        }
        if($primaryInvoice)
        return redirect()->route('invoices.show', $primaryInvoice)->with('success', 'تم إلغاء فاتورة الاستلام بنجاح');
        else if(route('invoices.show', $invoice->id) == url()->previous())
        return redirect()->route('invoices.index')->with('success', 'تم إلغاء الفاتورة بنجاح');
        elseif($fromBill && url()->previous() !== $prev_url)
            return back()->with('success', 'تم إلغاء فاتورة البيع بنجاح');
        else
            return back()->with('success', 'تم إلغاء الفاتورة بنجاح');
    }
}
