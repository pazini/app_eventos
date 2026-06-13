<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NomeCompleto implements Rule
{
    public function passes($attribute, $value)
    {
        // Verificar se o nome contém espaço em branco
        return strpos($value, ' ') !== false;
    }

    public function message()
    {
        return 'Deve conter nome e sobrenome.';
    }
}
