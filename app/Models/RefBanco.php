<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class RefBanco extends Model
{
    use Uuid;

    protected $keyType = 'uuid';

    public $incrementing = false;

    protected $table = 'ref_bancos';

    protected $fillable = [
        'ref_cod',
        'ref_banco',
        'ref_banco_descricao',
        'to_view',
    ];

    protected $casts = [
        'to_view' => 'boolean',
    ];
}
