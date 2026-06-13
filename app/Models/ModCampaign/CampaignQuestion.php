<?php

namespace App\Models\ModCampaign;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CampaignQuestion extends Model
{
    use Uuid;

    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $table = 'tbc_campaign_question';

    protected $fillable = [
        'campaign_id',
        'order',
        'question_type',
        'question_text',
        'question_options',
        'is_required',
        'placeholder',
        'help_text',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'question_options' => 'array',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function answers()
    {
        return $this->hasMany(CampaignOrderAnswer::class, 'campaign_question_id');
    }
}
