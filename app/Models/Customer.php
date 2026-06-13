<?php

namespace App\Models;

use App\Scopes\ActiveCustomerScope;
use App\Traits\HasTenantScope;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use Uuid, HasTenantScope;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_customers';

    protected $fillable = [
        'app_id',
        'customer_slug',
        'prefix_url',
        'name_corporate',
        'name_fantasy',
        'doc_type',
        'doc_num',
        'comercial_contact_name',
        'comercial_contact_email',
        'comercial_contact_country',
        'comercial_contact_ddd',
        'comercial_contact_num',
        'financial_contact_name',
        'financial_contact_email',
        'financial_contact_country',
        'financial_contact_ddd',
        'financial_contact_num',
        'address',
        'address_number',
        'address_complement',
        'address_reference',
        'city_neighborhood',
        'city',
        'state',
        'zip_code',
        'url_image_logo',
        'url_image_thumbnail',
        'url_image',
        'url_image_bg',
        'url_site',
        'url_instagram',
        'url_facebook',
        'country',
        'name_short',
        'generate_invoice',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $dates = [];

    protected static function booted(): void
    {
        static::addGlobalScope(new ActiveCustomerScope);
    }

    public function app()
    {
        return $this->belongsTo(App::class, 'app_id', 'id');
    }

    public function appModules()
    {
        return $this->hasManyThrough(AppModule::class,CustomerAppModule::class,'customer_id','id','id','module_id')->orderBy('created_at');
    }

    public function paymentGateways()
    {
        return $this->hasMany(CustomerPayGateway::class,'customer_id','id')->orderBy('pay_gateway_slug');
    }

    public function organizations()
    {
        return $this->hasMany(CustomerOrganization::class,'customer_id','id')->orderBy('created_at');
    }

    public function organizationSubs()
    {
        return $this->hasMany(CustomerOrganizationSub::class,'customer_id','id')->orderBy('created_at');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_customer', 'customer_id', 'user_id')
            ->withPivot(['user_active', 'user_role', 'can_events', 'can_campaigns', 'can_subscriptions'])
            ->orderBy('created_at');
    }

    public function organizers()
    {
        // TODOS QUE POSSUI CUSTOMER_ID
        return $this->hasMany(CustomerOrganizer::class)->orderBy('created_at');

        // SOMENTE AS QUE POSSUI APENAS CUSTOMER_ID
        // return $this->hasMany(CustomerOrganizer::class)->whereNull('tb_customers_organizers.organization_id')->whereNull('tb_customers_organizers.organization_sub_id')->orderBy('created_at');
    }

    public function campaignOrganizers()
    {
        return $this->hasMany(\App\Models\ModCampaign\CampaignOrganizer::class, 'customer_id', 'id')->orderBy('created_at');
    }
}
