<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TelefoneOuCelularComDddRule implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match('/^\(\d{2}\)\s?\d{4,5}-\d{4}$/', $value) > 0;
    }

    public function message()
    {
        return 'Telefone com DDD inválido.';
    }
}
