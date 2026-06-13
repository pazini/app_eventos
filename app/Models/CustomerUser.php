<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CustomerUser extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'users_customer';

    protected $dates = [];

    protected $fillable = [
        'customer_id',
        'user_id',
        'user_active',
        'user_role',
        'organization_id',
        'can_events',
        'can_campaigns',
        'can_subscriptions',
    ];
}
