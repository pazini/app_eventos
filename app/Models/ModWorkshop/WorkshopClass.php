<?php

namespace App\Models\ModWorkshop;

use App\Models\AppWorkshop\AppWorkshopOrder;
use App\Models\CustomerPayGateway;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class WorkshopClass extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'two_workshop_class';

    protected $dates = [];

    protected $fillable = [
        'workshop_id',
        'active',
        'status',
        'class_slug',
        'class_name',
        'class_name_short',
        'class_description',
        'class_description_additional_1',
        'class_description_additional_2',
        'class_program',
        'subscription_prerequisite',
        'subscription_prerequisite_date',
        'subscription_prerequisite_comment',
        'subscription_free',
        'subscription_price',
        'subscription_price_comment',
        'subscription_amount_min',
        'subscription_amount_max',
        'subscription_participant_max',
        'class_coffeebrake',
        'class_coffeebrake_optional',
        'class_coffeebrake_price',
        'class_coffeebrake_title',
        'class_coffeebrake_comment',
        'class_courseware',
        'class_courseware_optional',
        'class_courseware_price',
        'class_courseware_title',
        'class_courseware_comment',
        'class_graduation',
        'class_graduation_optional',
        'class_graduation_price',
        'class_graduation_title',
        'class_graduation_comment',
        'class_souvenirs',
        'class_souvenirs_optional',
        'class_souvenirs_price',
        'class_souvenirs_title',
        'class_souvenirs_comment',
        'pay_gateway_id',
        'pay_sandbox',
        'pay_boleto',
        'pay_boleto_date_end',
        'pay_pix',
        'pay_card_debit',
        'pay_card_credit',
        'pay_card_credit_installment_max',
        'pay_card_credit_installment_amount_min',
        'lesson_date_start',
        'lesson_date_finish',
        'lesson_online',
        'lesson_place_reference',
        'url_image_logo',
        'url_image_thumbnail',
        'url_image',
        'url_image_bg',
        'questions_buyer_pre_purchase',
        'questions_buyer_json',
        'questions_subscription_pre_purchase',
        'questions_subscription_json',
        'questions_participant_pre_purchase',
        'questions_participant_individual',
        'questions_participant_json',
        'notification_text_1',
        'notification_text_2',
        'notification_text_pos_btn',
    ];





    // LEGADO

    public function ticketsTypes()
    {
        return $this->HasMany(WorkshopTicketType::class);
    }

    public function tickets()
    {
        return $this->HasMany(AppWorkshopOrderTicket::class);
    }

    public function gatewayPay()
    {
        return $this->HasOne(CustomerPayGateway::class,'id','pay_gateway_id');
    }

    public function orders()
    {
        return $this->HasMany(AppWorkshopOrder::class,'workshop_id','id')->orderBy('created_at');
    }

    public function budgetsReceita()
    {
        return $this->HasMany(WorkshopBudget::class)->whereIn('tev_workshops_budgets.budget_operation', ['receita']);
    }

    public function budgetsDespesa()
    {
        return $this->HasMany(WorkshopBudget::class)->whereIn('tev_workshops_budgets.budget_operation', ['despesa']);
    }
}
