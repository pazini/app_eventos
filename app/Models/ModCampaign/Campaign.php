<?php

namespace App\Models\ModCampaign;

use App\Models\Customer;
use App\Models\CustomerOrganization;
use App\Models\CustomerPayGateway;
use App\Traits\HasTenantScope;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use Uuid, HasTenantScope;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tbc_campaign';

    protected $fillable = [
        'customer_id',
        'organization_id',
        'organizer_id',
        'slug',
        'customer_organization_slug',
        'name',
        'name_short',
        'description',
        'about',
        'status',
        'campaign_type',
        'visibility_public',
        'datetime_start',
        'datetime_finish',
        'goal_amount',
        'goal_leads',
        'goal_conversions',
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
        'pay_pix_direto',
        'pay_boleto',
        'pay_card_credit',
        'pay_card_credit_installment_max',
        'pay_card_credit_installment_amount_min',
        'pay_card_credit_installment_fee_payer',
        'show_goal_amount',
        'show_goal_leads',
        'show_goal_conversions',
        'show_progress',
        'enable_questions',
        'require_doc',
        'allow_anonymous',
        'allow_recurring',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'datetime_start' => 'datetime',
        'datetime_finish' => 'datetime',
        'pay_sandbox' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function (self $campaign) {
            $campaign->syncCustomerOrganizationSlugFromOrganizer();
        });
    }

    public function syncCustomerOrganizationSlugFromOrganizer(): void
    {
        if ($this->organizer_id) {
            $organizer = CampaignOrganizer::find($this->organizer_id);

            if ($organizer && !empty($organizer->organizer_slug)) {
                $this->customer_organization_slug = $organizer->organizer_slug;
                return;
            }
        }

        // Fallback para casos legados sem organizer válido
        if ($this->organization_id) {
            $organization = $this->relationLoaded('organization')
                ? $this->organization
                : CustomerOrganization::find($this->organization_id);

            if ($organization) {
                $this->customer_organization_slug = \Illuminate\Support\Str::slug(
                    $organization->organization_slug ?: $organization->organization_name
                );
                return;
            }
        }

        if ($this->customer_id) {
            $customer = $this->relationLoaded('customer')
                ? $this->customer
                : Customer::find($this->customer_id);

            if ($customer) {
                $this->customer_organization_slug = \Illuminate\Support\Str::slug(
                    $customer->name_corporate ?: $customer->name_fantasy
                );
            }
        }
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(CustomerOrganization::class, 'organization_id', 'id');
    }

    public function organizer()
    {
        return $this->belongsTo(CampaignOrganizer::class, 'organizer_id', 'id');
    }

    public function gateway()
    {
        return $this->belongsTo(CustomerPayGateway::class, 'pay_gateway_id', 'id');
    }

    public function metrics()
    {
        return $this->hasMany(CampaignMetric::class, 'campaign_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(CampaignOrder::class, 'campaign_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(CampaignQuestion::class, 'campaign_id', 'id')->orderBy('order');
    }

    /**
     * Relacionamento com payment slips (carnês)
     */
    public function paymentSlips()
    {
        return $this->hasMany(CampaignPaymentSlip::class, 'campaign_id', 'id');
    }

    /**
     * Relacionamento com payments (transações individuais)
     */
    public function campaignPayments()
    {
        return $this->hasMany(CampaignPayment::class, 'campaign_id', 'id');
    }

    /**
     * Relacionamento com webhooks
     */
    public function webhooks()
    {
        return $this->hasMany(CampaignPaymentWebhook::class, 'campaign_id', 'id');
    }

    /**
     * Accessor: Total arrecadado (payments pagos)
     */
    public function getTotalReceivedAttribute()
    {
        return $this->campaignPayments()
            ->where('status', 'paid')
            ->sum('value_liquid'); // Retorna em centavos
    }

    /**
     * Accessor: Total de doações (orders pagos)
     */
    public function getTotalDonationsAttribute()
    {
        return $this->orders()
            ->where('status', 'paid')
            ->count();
    }
}
