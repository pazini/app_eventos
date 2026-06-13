<?php

namespace App\Models\ModEvent;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class EventBudgetItem extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tev_events_budgets_items';

    protected $fillable = [
        'user_id',
        'event_id',
        'event_budget_id',
        'item_date',
        'item_name',
        'item_label',
        'item_description',
        'item_operation',
        'provider_id',
        'provider_name',
        'item_status',
        'item_qtd',
        'item_amount',
        'item_amount_total',
        'item_amount_investment',
        'item_amount_paid',
        'item_amount_liquid',
    ];

    protected $casts = [
        'item_date' => 'datetime',
    ];
}
