<?php

namespace App\Models\ModEvent;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class EventTicketCodePromo extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tev_events_tickets_codes_promo';

    protected $fillable = [
        'event_id',
        'event_ticket_id',
        'code_name',
        'code_description',
        'discount_type',
        'discount_value',
        'code_active',
        'code_datetime_validade_start',
        'code_datetime_validade_finish',
        'code_use_amount',
        'code_use_amount_used',
        'code_used',
        'code_used_order_id',
        'generate_user_id',
    ];

    protected $casts = [
        'code_datetime_validade_start' => 'datetime',
        'code_datetime_validade_finish' => 'datetime',
    ];
}
