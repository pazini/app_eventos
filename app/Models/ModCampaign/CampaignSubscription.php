<?php

namespace App\Models\ModCampaign;

use App\Models\AppBuyers;
use App\Models\Customer;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSubscription extends Model
{
    use HasFactory, Uuid;

    protected $table = 'tbc_campaign_subscription';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'campaign_id',
        'customer_id',
        'buyer_id',
        'amount_total',
        'status',
        'current_cycle',
        'next_charge_at',
        'last_charge_at',
        'card_token',
        'card_description',
        'card_validate_mm',
        'card_validate_aaaa',
        'canceled_at',
        'paused_at',
        'error_at',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'next_charge_at' => 'datetime',
        'last_charge_at' => 'datetime',
        'canceled_at' => 'datetime',
        'paused_at' => 'datetime',
        'error_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function buyer()
    {
        return $this->belongsTo(AppBuyers::class, 'buyer_id');
    }

    public function cycles()
    {
        return $this->hasMany(CampaignSubscriptionCycle::class, 'subscription_id')
            ->orderBy('cycle_number');
    }
}
