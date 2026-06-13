<?php

namespace App\Models\ModWorkshop;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class WorkshopClassBudget extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'two_workshop_class_budgets';

    protected $dates = [];

    protected $fillable = [
        'workshop_id',
        'class_id',
        'budget_title',
        'budget_subtitle',
        'budget_operation',
    ];

    public function budgetsItems()
    {
        return $this->HasMany(WorkshopBudgetItem::class);
    }
}
