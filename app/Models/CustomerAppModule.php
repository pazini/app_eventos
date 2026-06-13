<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CustomerAppModule extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_customers_app_modules';

    protected $fillable = [
        'customer_id',
        'module_id',
    ];

    protected $dates = [];
}
