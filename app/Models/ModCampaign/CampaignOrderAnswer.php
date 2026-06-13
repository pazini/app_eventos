<?php

namespace App\Models\ModCampaign;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class CampaignOrderAnswer extends Model
{
    use Uuid;

    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $table = 'tbc_campaign_order_answer';

    protected $fillable = [
        'campaign_order_id',
        'campaign_question_id',
        'answer_value',
    ];

    public function order()
    {
        return $this->belongsTo(CampaignOrder::class, 'campaign_order_id');
    }

    public function question()
    {
        return $this->belongsTo(CampaignQuestion::class, 'campaign_question_id');
    }
}
