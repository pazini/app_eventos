<?php

namespace App\Models\AppWorkshop;

use App\Models\AppPayment\AppPayment;
use App\Models\ModWorkshop\Workshop;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AppWorkshopOrder extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'app_workshop_orders';

    protected $casts = [
        'order_generation_datetime' => 'datetime',
        'order_cancel_datetime' => 'datetime',
        'buyer_birth_date' => 'datetime',
    ];

    protected $fillable = [
        'workshop_id',
        'class_id',
        'channel_order',
        'channel_user_id',
        'status',
        'order_control',
        'order_description',
        'order_amount_total',
        'order_amount_paid',
        'order_amount_liquid',
        'order_salebatch_id',
        'order_salebatch_codepromo_id',
        'order_salebatch_codepromo',
        'order_salebatch_description',
        'order_generation_datetime',
        'order_cancel_datetime',
        'order_cancel_description',
        'buyer_name',
        'buyer_email',
        'buyer_doc_type',
        'buyer_doc_num',
        'buyer_contact_country',
        'buyer_contact_ddd',
        'buyer_contact_num',
        'buyer_birth_date',
        'questions_buyer_json_answers',
    ];

    public function workshop()
    {
        return $this->hasOne(Workshop::class, 'id', 'workshop_id');
    }

    public function payments()
    {
        return $this->hasMany(AppPayment::class, 'app_ref_order_id', 'id')->where('app_ref','app_workshop')->orderBy('created_at');
    }

}
