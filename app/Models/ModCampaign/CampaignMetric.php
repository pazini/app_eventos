<?php

namespace App\Models\ModCampaign;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CampaignMetric extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tbc_campaign_metric';

    protected $fillable = [
        'campaign_id',
        'date_ref',
        'leads_count',
        'clicks_count',
        'conversions_count',
        'revenue_amount',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_ref' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }
}


