<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class UserCampaignOrganizer extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'users_campaign_organizer';

    protected $fillable = [
        'user_id',
        'organizer_id',
        'campaign_id',
        'user_active',
        'user_role',
    ];

    protected $dates = [];
}
