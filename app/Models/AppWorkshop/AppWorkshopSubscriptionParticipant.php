<?php

namespace App\Models\AppWorkshop;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppWorkshopSubscriptionParticipant extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_workshop_subscriptions_participants';

    protected $casts = [
        'participant_birth_date' => 'datetime',
        'conclusion_date' => 'datetime',
    ];

    protected $fillable = [
        'subscription_id',
        'status',
        'participant_name',
        'participant_email',
        'participant_doc_type',
        'participant_doc_num',
        'participant_contact_country',
        'participant_contact_ddd',
        'participant_contact_num',
        'participant_birth_date',
        'questions_participant_json_answers',
        'conclusion_date',
        'conclusion_certificate_name',
    ];
}
