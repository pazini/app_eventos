<?php

namespace App\Models\AppEvent;

use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventTicketType;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppEventOrderTicket extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_events_orders_tickets';

    protected $fillable = [
        'order_id',
        'order_item_id',
        'organizer_id',
        'organizer_name',
        'event_id',
        'event_name',
        'event_description',
        'event_datetime',
        'event_ticket_id',
        'event_ticket_slug',
        'event_ticket_name',
        'event_ticket_price',
        'event_ticket_price_code_promo_id',
        'event_ticket_price_discount',
        'event_ticket_price_paid',
        'ticket_control',
        'ticket_status',
        'ticket_generation_datetime',
        'ticket_checkin_datetime',
        'ticket_cancel_datetime',
        'ticket_cancel_description',
        'user_name',
        'user_email',
        'user_doc_type',
        'user_doc_num',
        'user_contact_country',
        'user_contact_ddd',
        'user_contact_num',
        'user_birth_date',
        'user_json_answers',
    ];

    protected $casts = [
        'event_datetime' => 'datetime',
        'ticket_generation_datetime' => 'datetime',
        'ticket_checkin_datetime' => 'datetime',
        'ticket_cancel_datetime' => 'datetime',
    ];

    public function order()
    {
        return $this->hasOne(AppEventOrder::class,  'id','order_id');
    }

    public function event()
    {
        return $this->hasOne(Event::class,  'id','event_id');
    }

    public function type()
    {
        return $this->hasOne(EventTicketType::class,  'id','event_ticket_id');
    }
}
