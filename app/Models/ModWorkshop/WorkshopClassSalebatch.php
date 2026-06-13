<?php

namespace App\Models\ModWorkshop;

use App\Models\AppWorkshop\AppWorkshopOrder;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class WorkshopClassSalebatch extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'two_workshop_class_salebatch';

    protected $casts = [
        'sale_start_datetime' => 'datetime',
        'sale_finish_datetime' => 'datetime',
    ];

    protected $fillable = [
        'class_id',
        'sale_slug',
        'sale_name',
        'sale_description',
        'sale_free',
        'sale_free_qtd',
        'visible',
        'price',
        'amount',
        'amount_sales',
        'sale_period_type',
        'sale_start_ticket_id_finish',
        'sale_start_datetime',
        'sale_finish_datetime',
        'sale_amount_min',
        'sale_amount_max',
        'sale_ticket_availability',
    ];

    public function orders()
    {
        return $this->HasMany(AppWorkshopOrder::class,'order_items_ticket_type_id','id')->orderBy('created_at');
    }
}
