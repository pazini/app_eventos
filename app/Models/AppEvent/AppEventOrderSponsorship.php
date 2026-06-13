<?php

namespace App\Models\AppEvent;

use App\Models\AppPayment\AppPayment;
use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventSponsorshipPlan;
use App\Models\User;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppEventOrderSponsorship extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_events_orders_sponsorship';

    protected $casts = [
        'order_generation_datetime' => 'datetime',
        'order_cancel_datetime' => 'datetime',
    ];

    protected $fillable = [
        'event_id',
        'plan_id',
        'channel_order',
        'channel_user_id',
        'status_old',
        'status',
        'order_control',
        'order_amount',
        'order_amount_pay',
        'order_amount_received',
        'order_description',
        'order_generation_datetime',
        'order_cancel_datetime',
        'sponsorship_id',
        'buyer_name',
        'buyer_segment',
        'buyer_description',
        'buyer_email',
        'buyer_doc_type',
        'buyer_doc_num',
        'buyer_contact_name',
        'buyer_contact_country',
        'buyer_contact_ddd',
        'buyer_contact_num',
        'buyer_url_logo',
        'buyer_url_website',
        'buyer_url_instagram',
        'buyer_json_answers',
        'order_json',
        'reservation_expiration_date',
        'payment_id',
        'slip_id',
        'slip_description',
    ];

    public function event()
    {
        // 1:1
        return $this->hasOne(Event::class, 'id', 'event_id');
    }

    public function plano()
    {
        // 1:1
        return $this->hasOne(EventSponsorshipPlan::class, 'id', 'plan_id');
    }

    public function userChannel()
    {
        // 1:1
        return $this->hasOne(User::class, 'id', 'channel_user_id');
    }

    public function payment()
    {
        return $this->hasOne(AppPayment::class, 'id', 'payment_id');
    }

    public function payments()
    {
        return $this->hasMany(AppPayment::class, 'app_ref_order_id', 'id')->whereIn('app_ref',['evento_patrocinador'])->orderBy('created_at');
    }
}
