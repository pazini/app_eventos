<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class UserCustomerOrganizationSub extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'users_customer_organization_sub';

    protected $fillable = [
        'user_id',
        'organization_sub_id',
        'user_active',
        'user_role',
    ];

    protected $dates = [];
}
