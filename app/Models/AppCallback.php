<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppCallback extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_callback';

    protected $dates = [];

    protected $fillable = [
        'callback_key',
        'callback_target',
        'callback_json',
        'callback_processed_date',
        'callback_processed_status',
    ];
}
