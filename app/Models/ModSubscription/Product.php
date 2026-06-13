<?php

namespace App\Models\ModSubscription;

use App\Models\Customer;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tbs_product';

    protected $fillable = [
        'customer_id',
        'organizer_id',
        'slug',
        'name',
        'name_short',
        'description',
        'about',
        'status',
        'visibility_public',
        'datetime_start',
        'datetime_finish',
        'amount_min',
        'color_primary',
        'color_secondary',
        'url_image_logo',
        'url_image_bg',
        'url_image_banner',
        'url_image_thumb',
        'pay_gateway_id',
        'pay_sandbox',
        'pay_pix',
        'pay_boleto',
        'pay_card_credit',
        'pay_card_credit_installment_max',
        'pay_card_credit_installment_fee_payer',
        'pay_card_credit_installment_amount_min',
        'metadata',
    ];

    protected $casts = [
        'visibility_public' => 'boolean',
        'pay_sandbox' => 'boolean',
        'pay_pix' => 'boolean',
        'pay_boleto' => 'boolean',
        'pay_card_credit' => 'boolean',
        'datetime_start' => 'datetime',
        'datetime_finish' => 'datetime',
        'metadata' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function organizer()
    {
        return $this->belongsTo(ProductOrganizer::class, 'organizer_id', 'id');
    }

    public function plans()
    {
        return $this->hasMany(ProductPlan::class, 'product_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'product_id', 'id');
    }
}
