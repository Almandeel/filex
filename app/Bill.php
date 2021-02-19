<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Traits\AuthableModel;
class Bill extends Model
{
    use AuthableModel;
    protected $fillable = [
    'number',
    'amount',
    'payed',
    'remain',
    'bill_id',
    'supplier_id',
    'store_id',
    'user_id',
    ];
    
    public static function purchases(){
        $bills = self::whereNull('bill_id');
        foreach (auth()->user()->getStores() as $store) {
            $bills->orWhere('store_id', $store->id);
        }
        return $bills;
    }
    public function isPrimary(){
        return $this->bill_id == null;
    }
    public function isReceive(){
        return !$this->isPrimary();
    }
    public function isDelivered(){
        foreach ($this->items as $item) {
            if($item->quantity !== $item->items->sum('quantity')) return false;
        }
        return true;
    }
    public function isPayed(){
        return $this->remain == 0;
    }
    
    public function withPay($accountId, $amount, $details = ""){
        $this->pay($accountId, $amount, $details);
        return $this;
    }
    
    public function setRemain($save = true){
        $this->remain = $this->amount - $this->payed;
        if(!$this->entries->count()){
            $this->withEntry();
        }
        if($this->entries->count()) $this->entries->first()->update(['amount' => $this->amount]);
        if($save) $this->save();
    }
    
    public function getAmount(){
        $amount = 0;
        foreach ($this->items as $item) {
            $amount += $item->amount();
        }
        
        return $amount;
    }
    
    public function getPayed(){
        $payed = 0;
        foreach ($this->payments as $payment) {
            $payed += $payment->amount;
        }
        foreach ($this->expenses as $expense) {
            $payed += $expense->amount;
        }
        
        return $payed;
    }
    
    public function getRemain(){
        return $this->getAmount() - $this->getPayed();
    }
    
    public function resetAmount(){
        $this->amount = $this->getAmount();
        $this->remain = $this->amount - $this->payed;
        $this->save();
    }
    
    public function resetPayed(){
        $this->payed = $this->getPayed();
        $this->remain = $this->amount - $this->payed;
        $this->save();
    }
    
    public function refresh(){
        if($this->bill){
            $this->bill->refresh();
        }else{
            $this->resetPayed();
            $this->resetAmount();
        }
    }
    
    public function charge($accountId, $amount = 0, $details){
        $entry = null;
        if($amount){
            if($this->bill_id){
                $entry = Entry::create([
                'amount' => $amount,
                'details' => $details,
                'from_id' => $this->bill->store->account->id,
                'to_id' => $accountId
                ]);
                
            }else{
                $entry = Entry::create([
                'amount' => $amount,
                'details' => $details,
                'from_id' => $this->store->account->id,
                'to_id' => $accountId
                ]);
            }
        }else{
            $entry = $accountId;
        }
        $this->setPrice();
        $this->entries()->attach([$entry->id]);
        
        $charge = Charge::create([
        'amount' => $entry->amount,
        'details' => $entry->details,
        'entry_id' => $entry->id,
        'bill_id' => $this->id,
        ]);
        return $charge;
    }
    public function withCharge($accountId, $amount, $details){
        $this->charge($accountId, $amount, $details);
        return $this;
    }
    public function cheque($amount, $bank, $number, $dueDate, $accountId, $type = Cheque::TYPE_PAY){
        if($amount){
            $cheque = Cheque::create([
            'type' => $type,
            'amount' => $amount,
            'bank_name' => $bank,
            'number' => $number,
            'due_date' => $dueDate,
            'account_id' => $accountId,
            'benefit' => Cheque::BENEFIT_SUPPLIER,
            'benefit_id' => $this->supplier_id,
            'bill_id' => $this->id,
            ]);
            return $cheque;
        }
        return null;
    }
    public function withCheque($amount, $bank, $number, $dueDate, $accountId, $type = Cheque::TYPE_PAY){
        $this->cheque($amount, $bank, $number, $dueDate, $accountId, $type);
        return $this;
    }
    public function setEntry($amount = null)
    {
        if(!isset($this->bill_id)){
            $purchaseEntry = Entry::create([
            'amount' => $amount ? $amount : $this->getPrice(),
            'details' => "عبارة عملية شراء من مورد رقم" . $this->supplier_id,
            'from_id' => $this->store->account->id,
            'to_id' => $this->supplier->account->id,
            'type' => Entry::TYPE_JOURNAL,
            ]);
            // dd($purchaseEntry->id);
            $this->entries()->attach([$purchaseEntry->id]);
        }
        
        return $this;
    }
    public function getPrice(){
        $price = 0;
        foreach ($this->items as $item) {
            $price += $item->quantity * $item->price;
        }
        return $price;
    }
    public function withEntry($amount = null)
    {
        $this->setEntry($amount);
        
        return $this;
    }
    public function setPrice($save = true){
        if($this->bill){
            $this->bill->setPrice($save);
        }else{
            $this->amount = 0;
            $remain = 0;
            
            foreach ($this->items as $item) {
                $this->amount += $item->amount();
            }
            
            $this->setRemain($save);
        }
    }
    public function withPrice($save = true){
        $this->setPrice($save);
        return $this;
    }
    public function activeCheques(){
        return $this->cheques->where('status', '!=', Cheque::STATUS_CANCELED);
    }
    public function details(){
        return 'عبارة عن فاتورة شراء رقم : ' . $this->id;
    }
    public function items()
    {
        return $this->hasMany('App\BillItem');
    }
    
    public function bills()
    {
        return $this->hasMany('App\Bill');
    }
    
    public function charges()
    {
        return $this->hasMany('App\Charge');
    }
    
    public function chargez()
    {
        return $this->hasManyThrough('App\Charge', 'App\Bill');
    }
    
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
    
    public function cheques()
    {
        return $this->hasMany('App\Cheque');
    }
    
    public function entries()
    {
        return $this->belongsToMany('App\Entry');
    }
    
    public function bill()
    {
        return $this->belongsTo('App\Bill', 'bill_id');
    }
    
    public function supplier()
    {
        return $this->belongsTo('App\Supplier', 'supplier_id');
    }
    
    public function store()
    {
        return $this->belongsTo('App\Store', 'store_id');
    }
    
    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
    
    public function expenses()
    {
        return $this->hasManyThrough('App\Expense', 'App\Bill');
    }
    
    public static function create(array $attributes = [])
    {
        $attributes['user_id'] = auth()->user()->id;
        $model = static::query()->create($attributes);
        return $model;
    }
    
    public function reset(){
        $this->payed = 0;
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach($this->cheques as $cheque){
            $cheque->delete();
        }
        foreach($this->charges as $charge){
            $charge->delete();
        }
        foreach($this->items as $item){
            $item->delete();
        }
        foreach($this->payments as $payment){
            $payment->delete();
        }
        foreach($this->expenses as $expense){
            $expense->delete();
        }
        foreach($this->bills as $bill){
            $bill->delete();
        }
        foreach($this->invoices as $invoice){
            $invoice->delete();
        }
        foreach($this->entries as $entry){
            $entry->delete();
        }
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    
    public function delete(){
        $bill = $this->bill_id ? $this->bill : null;
        $items = $this->items;
        $expenses = $this->expenses;
        $entries = $this->entries;
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $result = parent::delete();
        foreach($items as $item){
            $item->delete();
        }
        if($bill){
            foreach($expenses as $expense){
                $expense->delete();
            }
            $bill->refresh();
        }else{
            $cheques = $this->cheques;
            $charges = $this->charges;
            $payments = $this->payments;
            $bills = $this->bills;
            $invoices = $this->invoices;
            foreach($cheques as $cheque){
                $cheque->delete();
            }
            foreach($charges as $charge){
                $charge->delete();
            }
            foreach($payments as $payment){
                $payment->delete();
            }
            foreach($bills as $bill){
                $bill->delete();
            }
            foreach($invoices as $invoice){
                $invoice->delete();
            }
        }
        foreach($entries as $entry){
            $entry->delete();
        }
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $result;
    }
}