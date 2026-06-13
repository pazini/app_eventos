<?php

namespace App\Models\ModEvent;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderSponsorship;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class EventSponsorshipPlan extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'tev_events_sponsorship_plans';

    protected $fillable = [
        'event_id',
        'sponsorship_id',
        'slug',
        'name',
        'description',
        'price',
        'installments_max',
        'installments_fees_pay',
        'amount',
        'amount_sales',
        'plan_active',
        'pay_pix',
        'pay_credit',
        'pay_boleto',
        'pay_boleto_date_max',
        'data_finish',
    ];

    protected $casts = [
        'pay_boleto_date_max' => 'datetime',
        'data_finish' => 'datetime',
    ];

    public function event()
    {
        return $this->HasOne(Event::class,'event_id','id');
    }

    public function sponsorship()
    {
        return $this->HasOne(EventSponsorship::class,'id','sponsorship_id');
    }

    public function orders()
    {
        return $this->HasMany(AppEventOrderSponsorship::class,'plan_id','id')->orderBy('created_at');
    }
}
