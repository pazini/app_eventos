<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class UserCustomer extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'users_customer';

    protected $fillable = [
        'user_id',
        'customer_id',
        'user_active',
        'user_role',
        'can_events',
        'can_campaigns',
        'can_subscriptions',
    ];

    protected $dates = [];
}
