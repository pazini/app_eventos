<?php

namespace App\Models\ModWorkshop;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class WorkshopClassBudgetItem extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'two_workshop_class_budgets_items';

    protected $casts = [
        'item_date' => 'datetime',
    ];

    protected $fillable = [
        'workshop_id',
        'class_id',
        'budget_id',
        'user_id',
        'provider_id',
        'provider_name',
        'item_date',
        'item_name',
        'item_label',
        'item_description',
        'item_operation',
        'item_estimated_value',
        'item_effective_value',
        'item_status',
        'item_qtd',
        'item_amount',
        'item_amount_total',
        'item_amount_investment',
        'item_amount_paid',
        'item_amount_liquid',
    ];
}
