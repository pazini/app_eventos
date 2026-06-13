<?php

namespace App\Models\AppWorkshop;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppWorkshopSubscription extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_workshop_subscriptions';

    protected $dates = [];

    protected $fillable = [
        'workshop_id',
        'class_id',
        'order_id',
        'status',
        'questions_subscription_json_answers',
    ];
}
