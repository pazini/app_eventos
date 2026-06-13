<?php

namespace App\Models\AppEvent;

use App\Models\AppPayment\AppPayment;
use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventTicketType;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppEventOrderItem extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_events_orders_items';

    protected $fillable = [
        'order_id',
        'item_ticket_type_id',
        'item_status',
        'item_description',
        'item_amount',
        'item_amount_pay',
        'item_amount_pay_liquid',
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

    protected $dates = [];

    public function ticketType()
    {
        // 1:1
        return $this->hasOne(EventTicketType::class, 'id', 'item_ticket_type_id');
    }
}
