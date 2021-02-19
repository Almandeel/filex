<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AuthableModel;
class Cheque extends Model
{
    use AuthableModel;
    public const TYPE_COLLECT = 1;
    public const TYPE_PAY = 2;
    public const TYPES = [
    self::TYPE_COLLECT => 'قبض',
    self::TYPE_PAY => 'صرف',
    ];
    
    public const STATUS_CANCELED    = 0;
    public const STATUS_WAITING     = 1;
    public const STATUS_DELEVERED   = 2;
    public const STATUSES = [
    self::STATUS_CANCELED => 'ملغي',
    self::STATUS_WAITING => 'في الانتظار',
    self::STATUS_DELEVERED => 'تم',
    ];

    
    public const BENEFIT_SUPPLIER = 'App\Supplier';
    public const BENEFIT_CUSTOMER = 'App\Customer';
    public const BENEFIT_COLLABORATOR = 'App\Collaborator';
    public const BENEFIT_EMPLOYEE = 'App\Employee';
    public const BINIFITS = [
    'Customer' => 'عميل',
    'COLLABORATOR' => 'متعاون',
    'Supplier' => 'مورد',
    'Employee' => 'موظف',
    ];
    
    protected $table = 'cheques';
    
    protected $fillable = [
    'bank_name',
    'amount',
    'type',
    'number',
    'due_date',
    'benefit',
    'benefit_id',
    'bill_id',
    'invoice_id',
    'collaborator_id',
    'status',
    'details',
    'account_id',
    'entry_id',
    'user_id',
    ];
    
    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    protected $casts = [
    'due_date' => 'date:Y-m-d',
    ];
    
    public function getDueDateAttribute($date)
    {
        return $date;
    }
    
    public function entry()
    {
        return $this->belongsTo('App\Entry', 'entry_id');
    }
    
    public function account()
    {
        return $this->belongsTo('App\Account', 'account_id');
    }
    
    public function safe()
    {
        return $this->belongsTo('App\Safe', 'account_id');
    }
    
    public function bill()
    {
        return $this->belongsTo('App\Bill');
    }
    
    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }
    
    public function getBenefit()
    {
        if ($this->benefitIsModal()) {
            return $this->getBenefitModel->name;
        }
        return $this->benefit;
    }
    
    public function getBenefitModel()
    {
        if ($this->benefitIsModal()) {
            return $this->belongsTo($this->benefit, 'benefit_id');
        }
        return null;
    }
    
    public function benefitIsModal()
    {
        return ($this->benefit == self::BENEFIT_SUPPLIER || $this->benefit == self::BENEFIT_CUSTOMER || $this->benefit == self::BENEFIT_EMPLOYEE);
    }
    public function getType()
    {
        return self::TYPES[$this->type];
    }
    
    public function getStatus()
    {
        return self::STATUSES[$this->status];
    }
    
    public function getBeneficiary()
    {
        return isset($this->beneficiary) ? ($this->beneficiary->id == Account::credits()->id ? 'الدائنون' : ($this->beneficiary->id == Account::debts()->id ?  'المدينون' : '')) : '';
    }
    
    public function delivered($register = true)
    {
        $entry = null;
        
        if($register){
            if ($this->type == self::TYPE_PAY) {
                $entry = $this->account->withDrawn($this->amount, $this->benefit_id, $this->details);
            } else {
                $entry = $this->account->deposite($this->amount, $this->benefit_id, $this->details);
            }
            
            $this->update([
            'entry_id' => $entry->id,
            'status' => self::STATUS_DELEVERED,
            ]);
        }
        else{
            $this->update([
            'entry_id' => $entry->id,
            'status' => self::STATUS_DELEVERED,
            ]);
        }
        
        return $entry;
    }
    
    public function deliver(array $attributes = [])
    {
        $from_id = ($this->type !== self::TYPE_PAY) ? $this->account_id : $this->benefit_id;
        $to_id = ($this->type == self::TYPE_PAY) ? $this->account_id : $this->benefit_id;
        $data = [
            'amount' => $this->amount,
            'details' => '',
            'from_id' => $from_id,
            'to_id' => $to_id,
            'safe_id' => $this->account_id,
        ];

        
        foreach ($attributes as $key => $value) {
            $data[$key] = $value;
        }
        // dd($data);
        $payment = Payment::create($data);
        
        $this->update([
        'entry_id' => $payment->entry_id,
        'status' => Cheque::STATUS_DELEVERED,
        ]);
        
        return $payment->entry;
    }
    
    public function canceled()
    {
        $this->update([
        'status' => self::STATUS_CANCELED,
        ]);
    }
    
    public function bills()
    {
        return $this->belongsToMany('App\Bill', 'bill_cheque');
    }
    
    public function invoices()
    {
        return $this->belongsToMany('App\Invoice', 'cheque_invoice');
    }
    
    public static function create(array $attributes = [])
    {
        $attributes['user_id'] = auth()->user()->id;
        // dd($attributes);
        $model = static::query()->create($attributes);
        return $model;
    }
    
    public function delete()
    {
        if ($this->entry) $this->entry->delete();
        return parent::delete();
    }
}