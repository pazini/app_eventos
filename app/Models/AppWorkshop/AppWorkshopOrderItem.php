<?php

namespace App\Models\AppWorkshop;

use App\Models\AppPayment\AppPayment;
use App\Models\ModWorkshop\Workshop;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppWorkshopOrderItem extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_workshop_orders_items';

    protected $dates = [];

    protected $fillable = [
        'order_id',
        'item_qtd',
        'item_description',
        'status',
        'item_amount',
        'item_amount_total',
    ];

}
