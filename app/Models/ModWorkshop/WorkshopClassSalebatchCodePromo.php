<?php

namespace App\Models\ModWorkshop;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class WorkshopClassSalebatchCodePromo extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'two_workshop_class_salebatch_codepromo';

    protected $dates = [];

    protected $fillable = [
        'class_id',
        'salebath_id',
        'code_name',
        'code_description',
        'code_only_email',
        'discount_type',
        'discount_value',
    ];
}
