<?php

namespace App\Models\ModEvent;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class EventBudget extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tev_events_budgets';

    protected $fillable = [
        'event_id',
        'budget_title',
        'budget_subtitle',
        'budget_operation',
    ];

    protected $dates = [];

    public function budgetsItems()
    {
        return $this->HasMany(EventBudgetItem::class);
    }
}
