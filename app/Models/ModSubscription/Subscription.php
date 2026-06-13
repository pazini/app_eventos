<?php

namespace App\Models\ModSubscription;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tbs_subscription';

    protected $fillable = [
        'product_id',
        'product_plan_id',
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
        'next_charge_at' => 'datetime',
        'last_charge_at' => 'datetime',
        'canceled_at' => 'datetime',
        'paused_at' => 'datetime',
        'error_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(ProductPlan::class, 'product_plan_id', 'id');
    }

    public function cycles()
    {
        return $this->hasMany(SubscriptionCycle::class, 'subscription_id', 'id');
    }
}
