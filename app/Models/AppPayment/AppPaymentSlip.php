<?php

namespace App\Models\AppPayment;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\AppPayment\AppPayment;
use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventTicketCodePromo;
use App\Models\User;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppPaymentSlip extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_payments_slip';

    protected $dates = [];

    protected $fillable = [
        'order_id',
        'user_id',
        'slip_id',
        'slip_installment_id_previous',
        'slip_installment_control',
        'slip_installment_available',
        'slip_installment',
        'installment_description',
        'installment_date_due',
        'installment_pay_type',
        'installment_value',
        'installment_value_fees',
        'installment_value_liquid',
        'installment_value_amortization',
        'installment_fee_percentage_used',
        'status',
        'paid_datetime',
        'paid_value',
        'paid_label',
        'payment_id',
    ];

    public function order()
    {
        // 1:1
        return $this->hasOne(AppEventOrder::class, 'slip_id', 'slip_id');
    }

    public function user()
    {
        // 1:1
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function paymentNext()
    {
        // 1:1
        return $this->hasOne(AppPaymentSlip::class, 'slip_installment_id_previous','id');
    }

    public function payment()
    {
        return $this->hasOne(AppPayment::class, 'id', 'payment_id');
    }

    public function payments()
    {
        return $this->hasMany(AppPayment::class, 'order_slip_id', 'id')->orderBy('created_at');
    }
}
