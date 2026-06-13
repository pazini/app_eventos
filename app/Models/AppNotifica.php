<?php

namespace App\Models;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayGateway;
use App\Models\CustomerPayGateway;
use App\Models\ModEvent\EventTicketCodePromo;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppNotifica extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_notifica';

    protected $fillable = [
        'buyer_id',
        'order_id',
        'payment_id',
        'tipo',
        'canal',
        'envio_destino',
        'envio_datahora',
        'subject',
        'body',
        'job_id',
        'job_json',
    ];

    protected $casts = [
        'pay_datetime' => 'datetime',
    ];
}
