<?php

namespace App\Models\AppEvent;

use App\Models\AppPayment\AppPayment;
use App\Models\AppPayment\AppPaymentSlip;
use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventTicketCodePromo;
use App\Models\User;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppEventOrder extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_events_orders';

    protected $casts = [
        'order_generation_datetime' => 'datetime',
        'order_cancel_datetime' => 'datetime',
        'buyer_birth_date' => 'datetime',
        'reservation_expiration_date' => 'datetime',
        'status_old_datetime' => 'datetime',
        'order_tracking_timestamp' => 'datetime',
    ];

    protected $fillable = [
        'event_id',
        'channel_order',
        'channel_user_id',
        'status',
        'order_control',
        'order_amount',
        'order_amount_pay',
        'order_description',
        'order_generation_datetime',
        'order_cancel_datetime',
        'order_cancel_description',
        'buyer_name',
        'buyer_email',
        'buyer_doc_type',
        'buyer_doc_num',
        'buyer_contact_country',
        'buyer_contact_ddd',
        'buyer_contact_num',
        'buyer_birth_date',
        'buyer_json_answers',
        'order_ip_address',
        'order_user_agent',
        'order_device_type',
        'order_browser',
        'order_platform',
        'order_session_id',
        'order_tracking_timestamp',
        'order_items_ticket_type_id',
        'order_items_qtd',
        'order_items_amount',
        'order_items_amount_total',
        'order_items_amount_paid',
        'order_items_amount_liquid',
        'code_promo_id',
        'code_promo_discount_amount',
        'code_promo_label',
        'code_promo_price_old',
        'code_promo_price_less',
        'code_promo_price_new',
        'order_amount_received',
        'order_amount_received_liquid',
        'order_json',
        'reservation_expiration_date',
        'status_old',
        'status_old_datetime',
        'notifica_sucesso',
        'notifica_sucesso_datahora',
        'payment_id',
        'buyer_id',
        'slip_id',
        'slip_description',
        'order_terms',
    ];

    public function event()
    {
        // 1:1
        return $this->hasOne(Event::class, 'id', 'event_id');
    }

    public function userChannel()
    {
        // 1:1
        return $this->hasOne(User::class, 'id', 'channel_user_id');
    }

    public function codePromo()
    {
        // 1:1
        return $this->hasOne(EventTicketCodePromo::class, 'id', 'code_promo_id');
    }

    public function itens()
    {
        return $this->hasMany(AppEventOrderItem::class, 'order_id', 'id')->orderBy('created_at');
    }

    public function payment()
    {
        return $this->hasOne(AppPayment::class, 'id', 'payment_id');
    }

    public function payments()
    {
        return $this->hasMany(AppPayment::class, 'app_ref_order_id', 'id')->whereIn('app_ref',['app_event','evento'])->orderBy('created_at');
    }

    public function paymentsSlip()
    {
        return $this->hasMany(AppPaymentSlip::class, 'slip_id', 'slip_id')->orderBy('installment_date_due')->orderBy('slip_installment_control');
    }

    public function tickets()
    {
        return $this->hasMany(AppEventOrderTicket::class, 'order_id', 'id');
    }
}
