<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Traits\AuthableModel;
class Invoice extends Model
{
    use AuthableModel;
    protected $fillable = [
    'amount',
    'number',
    'payed',
    'remain',
    'invoice_id',
    'bill_id',
    'customer_id',
    'store_id',
    'user_id',
    ];
    
    public static function purchases(){
        $invoices = self::whereNull('invoice_id');
        foreach (auth()->user()->getStores() as $store) {
            $invoices->orWhere('store_id', $store->id);
        }
        return $invoices;
    }
    public function isPrimary(){
        return $this->invoice_id == null;
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
        $this->resetPayed();
        $this->resetAmount();
    }
    
    public function charge($accountId, $amount = 0, $details){
        $entry = null;
        if($amount){
            if($this->invoice_id){
                $entry = Entry::create([
                'amount' => $amount,
                'details' => $details,
                'to_id' => $this->invoice->store->account->id,
                'from_id' => $accountId
                ]);
                
            }else{
                $entry = Entry::create([
                'amount' => $amount,
                'details' => $details,
                'to_id' => $this->store->account->id,
                'from_id' => $accountId
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
        'invoice_id' => $this->id,
        ]);
        return $charge;
    }
    public function withCharge($accountId, $amount, $details){
        $this->charge($accountId, $amount, $details);
        return $this;
    }
    public function cheque($amount, $bank, $number, $dueDate, $accountId, $type = Cheque::TYPE_COLLECT){
        if($amount){
            $cheque = Cheque::create([
            'type' => $type,
            'amount' => $amount,
            'bank_name' => $bank,
            'number' => $number,
            'due_date' => $dueDate,
            'account_id' => $accountId,
            'benefit' => Cheque::BENEFIT_CUSTOMER,
            'benefit_id' => $this->customer_id,
            'invoice_id' => $this->id,
            ]);
            return $cheque;
        }
        return null;
    }
    public function withCheque($amount, $bank, $number, $dueDate, $accountId, $type = Cheque::TYPE_COLLECT){
        $this->cheque($amount, $bank, $number, $dueDate, $accountId, $type);
        return $this;
    }
    public function setEntry($amount = null)
    {
        $amount = $amount ? $amount : $this->amount;
        if(!isset($this->invoice_id)){
            $purchaseEntry = Entry::create([
            'amount' => $amount,
            'details' => "عبارة عملية بيع للعميل رقم" . $this->customer_id,
            'to_id' => $this->bill ? $this->bill->supplier->account_id : $this->store->account->id,
            'from_id' => $this->customer->account->id,
            'type' => Entry::TYPE_JOURNAL,
            ]);
            // dd($purchaseEntry->id);
            $this->entries()->attach([$purchaseEntry->id]);
        }
        
        return $this;
    }
    public function details(){
        return 'عبارة عن فاتورة بيع رقم : ' . $this->id;
    }
    public function withEntry($amount = null)
    {
        $this->setEntry($amount);
        
        return $this;
    }
    public function getPrice(){
        $price = 0;
        foreach ($this->items as $item) {
            $price += $item->quantity * $item->price;
        }
        return $price;
    }
    public function setPrice($save = true){
        if($this->invoice){
            $this->invoice->setPrice($save);
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
    public function items()
    {
        return $this->hasMany('App\InvoiceItem');
    }
    
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
    
    public function charges()
    {
        return $this->hasMany('App\Charge');
    }
    
    public function chargez()
    {
        return $this->hasManyThrough('App\Charge', 'App\Invoice');
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
    
    public function invoice()
    {
        return $this->belongsTo('App\Invoice', 'invoice_id');
    }
    
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
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
        return $this->hasManyThrough('App\Expense', 'App\Invoice');
    }
    
    public static function create(array $attributes = [])
    {
        $attributes['user_id'] = auth()->user()->id;
        $model = static::query()->create($attributes);
        return $model;
    }
    
    public function update(array $attributes = [], array $options = [])
    {
        $result = parent::update($attributes, $options);
        return $result;
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
            // if($this->isPrimary()){
            //     $storeQuantity = $item->itemStoreUnit->quantity + $item->items->sum('quantity');
            //     $item->itemStoreUnit->update(['quantity' => $storeQuantity]);
            // }
            
            $item->delete();
        }
        foreach($this->payments as $payment){
            $payment->delete();
        }
        foreach($this->expenses as $expense){
            $expense->delete();
        }
        foreach($this->invoices as $invoice){
            $items = $invoice->items;
            if ($items->count()) {
                foreach ($items as $item) {
                    $itemStoreUnit = $item->itemStoreUnit;
                    if($itemStoreUnit){
                        $itemStoreUnit->update(['quantity' => $itemStoreUnit->quantity + $item->quantity]);
                    }
                    $item->delete();
                }
            }
            $invoice->delete();
        }
        foreach($this->entries as $entry){
            $entry->delete();
        }
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    
    public function delete(){
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $invoice = $this->invoice;
        $bill = $this->bill;
        $this->reset();
        // if($bill) $bill->delete();
        if($invoice) $invoice->refresh();
        $result =  parent::delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        return $result;
    }
}
