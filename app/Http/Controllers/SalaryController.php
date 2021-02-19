<?php

namespace App\Http\Controllers;

use App\Salary;
use App\Employee;
use App\Account;
use App\Transaction;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function __construct() {
        $this->middleware('year.activated')->only(['create','store']);
        $this->middleware('year.opened')->only(['create','store']);

        $this->middleware('permission:salaries-create')->only(['create', 'store']);
        $this->middleware('permission:salaries-read')->only(['index', 'show']);
        $this->middleware('permission:salaries-update')->only(['edit', 'update']);
        $this->middleware('permission:salaries-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request, Employee $employee)
    {
        $salaries = $employee->salaries;
        return view('dashboard.salaries.index', compact('employee', 'salaries'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Request
    */
    public function create(Request $request, Employee $employee)
    {
        $year = isset($request->year) ? $request->year : date('Y');
        $month = isset($request->month) ? $request->month < 10 ? '0' . $request->month : $request->month : date('m');
        $fullMonth = $year . '-' . $month;
        $transactions = $employee->monthlyTransactions($fullMonth);
        $debts = $transactions->where('type', Transaction::TYPE_DEBT)->groupBy('safe_id');
        $bonuses = $transactions->where('type', Transaction::TYPE_BONUS)->groupBy('safe_id');
        $deducations = $transactions->where('type', Transaction::TYPE_DEDUCATION)->groupBy('safe_id');
        
        $totalDebts = ['payed' => 0, 'remain' => 0, 'total' => 0];
        $totalDeducations = ['payed' => 0, 'remain' => 0, 'total' => 0];
        $totalBonuses = ['payed' => 0, 'remain' => 0, 'total' => 0];
        $net = $employee->salary;
        foreach ($debts as $debt) {
            if($debt->first()->safe){
                $totalDebts['payed'] += $debt->sum('amount');
            }else{
                $totalDebts['remain'] += $debt->sum('amount');
                $net -= $debt->sum('amount');
            }
            $totalDebts['total'] += $debt->sum('amount');
        }
        foreach ($bonuses as $bonus) {
            if($bonus->first()->safe){
                $totalBonuses['payed'] += $bonus->sum('amount');
            }else{
                $totalBonuses['remain'] += $bonus->sum('amount');
                $net += $bonus->sum('amount');
            }
            $totalBonuses['total'] += $bonus->sum('amount');
        }
        foreach ($deducations as $deducation) {
            if($deducation->first()->safe){
                $totalDeducations['payed'] += $deducation->sum('amount');
            }else{
                $totalDeducations['remain'] += $deducation->sum('amount');
                $net -= $deducation->sum('amount');
            }
            $totalDeducations['total'] += $deducation->sum('amount');
        }
        return view('dashboard.salaries.create', compact('employee', 'transactions', 'net', 'totalDebts', 'totalBonuses', 'totalDeducations', 'year', 'fullMonth', 'month'));
    }
    
    /**
    * Salary a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request, Employee $employee)
    {
        if(\Hash::check($request->password, auth()->user()->password)){
            $request->validate([
            'net'      => 'required | numeric',
            'total'      => 'required | numeric',
            ]);
            
            $date = $request->year . '-';
            $date .= $request->month < 10 ? '0' . $request->month : $request->month;
            $request['month'] = $date;
            $salary = Salary::create($request->all());
            // foreach ($employee->monthlyTransactions($date) as $transaction) {
            //     if(isset($transaction->entry)) $transaction->entry->reverse("عبارة عن قيد تسوية " . Transaction::TYPES[$transaction->type] . ' للموظف: ' . $employee->name . ' مرتب شهر: ' . $date);
            // }
            
            return redirect()->route('salaries.index', $employee)->with('success', 'تمت اضافة المرتب بنجاح');
        }
        return back()->with('error', 'كلمة المرور خاطئة');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Salary  $salary
    * @return \Illuminate\Http\Response
    */
    public function show(Employee $employee, Salary $salary)
    {
        //
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Salary  $salary
    * @return \Illuminate\Http\Response
    */
    public function edit(Request $request, Employee $employee, Salary $salary)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Salary  $salary
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Employee $employee, Salary $salary)
    {
        if(\Hash::check($request->password, auth()->user()->password)){
            $request->validate([
            'net'      => 'required | numeric',
            'total'      => 'required | numeric',
            ]);
            $date = $request->year . '-';
            $date .= $request->month < 10 ? '0' . $request->month : $request->month;
            $request['month'] = $date;
            if(!array_key_exists($request->type, Salary::TYPES)) $request['type'] = Salary::TYPE_DEBT;
            $salary->update($request->all());
            if(isset($salary->entry)) $salary->entry->delete();
            
            if($request->paymethod != 0){
                $sb = Account::find($request->paymethod);
                $entry = $sb->withDrawn($request->amount, Account::debts()->id, $request->details);
                $salary->update(['entry_id' => $entry->id]);
            }
            
            session()->flash('success', 'تم تعديل المعاملة بنجاح');
            
            return back();
        }
        return back()->with('error', 'كلمة المرور خاطئة');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Salary  $salary
    * @return \Illuminate\Http\Response
    */
    public function destroy(Employee $employee, Salary $salary)
    {
        $salary->delete();
        return back()->with('success', 'تم حذف المعاملة بنجاح');
    }
}