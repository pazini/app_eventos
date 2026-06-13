<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CustomerOrganization extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tb_customers_organizations';

    protected $fillable = [
        'customer_id',
        'organization_slug',
        'organization_name',
        'organization_description',
        'organization_url_image_logo',
        'organization_url_image_thumbnail',
        'organization_url_image',
        'organization_url_image_bg',
        'organization_doc_tipo',
        'organization_doc_num',
        'organization_razao_social',
    ];

    protected $dates = [];

    private function toUpperString($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return mb_strtoupper(trim((string) $value));
    }

    private function toTrimString($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return trim((string) $value);
    }

    public function setOrganizationNameAttribute($value): void
    {
        $this->attributes['organization_name'] = $this->toUpperString($value);
    }

    public function setOrganizationDescriptionAttribute($value): void
    {
        $this->attributes['organization_description'] = $this->toTrimString($value);
    }

    public function setOrganizationDocTipoAttribute($value): void
    {
        $this->attributes['organization_doc_tipo'] = $this->toTrimString($value);
    }

    public function setOrganizationRazaoSocialAttribute($value): void
    {
        $this->attributes['organization_razao_social'] = $this->toUpperString($value);
    }

    public function organizationSubs()
    {
        return $this->hasMany(CustomerOrganizationSub::class,'organization_id','id')->orderBy('created_at');
    }

    public function organizers()
    {
        // return $this->hasMany(CustomerOrganizer::class,'organization_id','id')->orderBy('created_at');

        return $this->hasMany(CustomerOrganizer::class,'organization_id','id')->whereNull('tb_customers_organizers.organization_sub_id')->orderBy('created_at');
    }

    public function users()
    {
        return $this->hasMany(CustomerUser::class, 'organization_id', 'id');
    }

    public function campaignOrganizers()
    {
        return $this->hasMany(\App\Models\ModCampaign\CampaignOrganizer::class, 'organization_id', 'id');
    }
}
