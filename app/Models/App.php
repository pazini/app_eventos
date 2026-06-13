<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_app';

    protected $fillable = [
        'app_name',
        'app_slug',
        'app_description',
        'app_license',
        'app_limit_date',
        'app_active',
        'owner_name',
        'owner_email',
        'owner_phone_country',
        'owner_phone_ddd',
        'owner_phone_num',
        'url_base',
        'url_image_logo',
        // White Label Fields
        'domain_primary',
        'domain_aliases',
        'color_primary',
        'color_secondary',
        'color_accent',
        'url_image_logo_dark',
        'url_image_favicon',
        'url_image_default_thumb',
        'email_from_name',
        'email_from_address',
        'email_reply_to',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'meta_image',
        'settings',
        'branding_updated_at',
        // Safe2Pay Master Tokens (conta pai do APP)
        'safe2pay_token_live',
        'safe2pay_token_test',
        'safe2pay_token_live_pass',
        'safe2pay_token_test_pass',
        'safe2pay_active',
        'safe2pay_test_mode',
        'safe2pay_settings',
    ];

    protected $casts = [
        'app_active' => 'boolean',
        'domain_aliases' => 'array',
        'settings' => 'array',
        'safe2pay_active' => 'boolean',
        'safe2pay_test_mode' => 'boolean',
        'safe2pay_settings' => 'array',
        'app_limit_date' => 'datetime',
        'branding_updated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function modules()
    {
        return $this->hasMany(AppModule::class)->orderBy('module_name');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'app_id');
    }

    public function campaigns()
    {
        return $this->hasManyThrough(
            \App\Models\ModCampaign\Campaign::class,
            Customer::class,
            'app_id', // Foreign key on customers table
            'customer_id', // Foreign key on campaigns table
            'id', // Local key on apps table
            'id' // Local key on customers table
        );
    }

    // Accessors para facilitar uso
    public function getLogoUrlAttribute()
    {
        return $this->url_image_logo ?? asset('images/app/default-logo.png');
    }

    public function getLogoDarkUrlAttribute()
    {
        return $this->url_image_logo_dark ?? $this->getLogoUrlAttribute();
    }

    public function getFaviconUrlAttribute()
    {
        return $this->url_image_favicon ?? asset('images/app/default-favicon.ico');
    }

    public function getDomainAliasesArrayAttribute()
    {
        return $this->domain_aliases ?? [];
    }

    // Helper methods
    public function hasFeature(string $feature): bool
    {
        $settings = $this->settings;
        return $settings['features'][$feature] ?? false;
    }

    public function getDomains(): array
    {
        $domains = [$this->domain_primary];
        if ($this->domain_aliases) {
            $domains = array_merge($domains, $this->domain_aliases);
        }
        return array_filter($domains);
    }

    public function matchesDomain(string $domain): bool
    {
        $domains = $this->getDomains();
        foreach ($domains as $appDomain) {
            if (str_contains($domain, $appDomain)) {
                return true;
            }
        }
        return false;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('app_active', true);
    }

    public function scopeByDomain($query, string $domain)
    {
        return $query->where('domain_primary', $domain)
                    ->orWhereRaw("domain_aliases::text LIKE ?", ["%{$domain}%"]);
    }
}
