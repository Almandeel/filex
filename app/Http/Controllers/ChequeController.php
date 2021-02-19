<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\{Cheque, Account, Bill, Invoice, Payment};
use Illuminate\Http\Request;
use App\{Customer, Supplier, Employee, Collaborator};
use App\Events\ChequeCreatedEvent;

class ChequeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:cheques-create')->only(['create', 'store']);
        $this->middleware('permission:cheques-read')->only(['index', 'show']);
        $this->middleware('permission:cheques-update')->only(['edit', 'update']);
        $this->middleware('permission:cheques-delete')->only('destroy');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $cheques = Cheque::orderBy('created_at', 'DESC')->orderBy('due_date', 'DESC')->get();
        $beneficiaries = null; //$cheques->beneficiary()->all();
        $customers = auth()->user()->customers();
        $suppliers = auth()->user()->suppliers();
        $employees = Employee::all();
        $collaborators = Collaborator::all();
        $from_date = $request->from_date ? $request->from_date : date('Y-m-d');
        $to_date = $request->to_date ? $request->to_date : date('Y-m-d');
        $fromDate = $from_date . ' 00:00:00';
        $toDate = $to_date . ' 23:59:59';
        if($request->using_dates && ($request->from_date || $request->to_date)){
            $cheques = $cheques->whereBetween('due_date', [$from_date, $to_date]);
        }
        if($request->type && $request->type != 111001){
            $cheques = $cheques->where('type', $request->type);
        }
        if($request->status && $request->status != 111001){
            $cheques = $cheques->where('status', $request->status);
        }
        $type = $request->type ? $request->type : 111001;
        $status = $request->status ? $request->status : 111001;
        $using_dates = $request->using_dates ? $request->using_dates : 0;
        $advanced_search = ($request->using_dates && ($request->from_date || $request->to_date)) || ($request->type && $request->type != 111001) || ($request->status && $request->status != 111001);
        return view('dashboard.cheques.index', compact('cheques', 'advanced_search', 'using_dates', 'status', 'type', 'from_date', 'to_date', 'beneficiaries', 'customers', 'suppliers', 'employees', 'collaborators'));
    }
    
    public function create()
    {
        return view('dashboard.cheques.create');
    }
    
    public function show(Cheque $cheque)
    {
        return view('dashboard.cheques.show', compact('cheque'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        request()->validate([
        'due_date' => 'required',
        'amount' => 'required | numeric',
        ]);
        $cheque = Cheque::create($request->except('_method', 'token'));
        
        event(new ChequeCreatedEvent($cheque));
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Cheque  $cheque
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Cheque $cheque)
    {
        if (isset($request->status) && !isset($request->amount) && !isset($request->bank_name) && !isset($request->due_date) && !isset($request->number)) {
            if ($request->status == Cheque::STATUS_DELEVERED) {
                $bill = $cheque->bill;
                $invoice = $cheque->invoice;
                if ($bill) {
                    $cheque->deliver([
                    'details' => 'عبارة عن سداد شيك كدفعة لفاتورة شراء رقم: ' . $bill->id,
                    'bill_id' => $bill->id,
                    ]);
                    
                    $bill->refresh();
                }
                else if ($invoice){
                    $cheque->deliver([
                    'details' => 'عبارة عن سداد شيك كدفعة لفاتورة بيع رقم: ' . $invoice->id,
                    'invoice_id' => $invoice->id,
                    ]);
                    
                    $invoice->refresh();
                }
                else{
                    $entry = $cheque->deliver();
                }
            } else if ($request->status == Cheque::STATUS_CANCELED) {
                $cheque->canceled();
            }
        } else {
            request()->validate([
            'due_date' => 'required',
            'amount' => 'required | numeric',
            ]);
            // dd($cheque);
            $cheque->update($request->all());
        }
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Cheque  $cheque
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Cheque $cheque)
    {
        $bill = $cheque->bill;
        $invoice = $cheque->invoice;
        $cheque->delete();
        if($request->bill){
            $bill->refresh();
        }
        else if($request->invoice){
            $invoice->refresh();
        }
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    public function updateStatus(Cheque $cheque)
    {
        if ($request->has('changeStatus')) {
            $request->changeStatus == 1 ? $cheque->delivered() : $cheque->canceled();
        }
        
        return redirect()->route('cheques.index')->with('success', 'تمت العملية بنجاح');
    }
}