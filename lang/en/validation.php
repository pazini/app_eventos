<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute deve ser aceito',
    'accepted_if' => ':attribute deve ser aceito quando :other for :value',
    'active_url' => ':attribute não é uma URL válida',
    'after' => ':attribute deve ser uma data posterior a :date',
    'after_or_equal' => ':attribute deve ser uma data posterior ou igual a :date',
    'alpha' => ':attribute deve conter apenas letras',
    'alpha_dash' => ':attribute deve conter apenas letras, números, traços e sublinhados',
    'alpha_num' => ':attribute deve conter apenas letras e números',
    'array' => ':attribute deve ser um Array',
    'before' => ':attribute deve ser uma data anterior a :date',
    'before_or_equal' => ':attribute deve ser uma data anterior ou igual a :date',
    'between' => [
        'array' => ':attribute deve ter entre :min e :max itens',
        'file' => ':attribute deve estar entre :min e :max Kb',
        'numeric' => ':attribute deve estar entre :min e :max',
        'string' => ':attribute deve estar entre :min e :max caracteres',
    ],
    'boolean' => ':attribute deve ser True ou False',
    'confirmed' => 'A confirmação de :attribute não corresponde',
    'current_password' => 'A senha está incorreta',
    'date' => ':attribute não é uma data válida',
    'date_equals' => ':attribute deve ser uma data igual a :date',
    'date_format' => ':attribute não corresponde ao formato :format',
    'declined' => ':attribute deve ser recusado',
    'declined_if' => ':attribute deve ser recusado quando :other for :value',
    'different' => ':attribute e :other devem ser diferentes',
    'digits' => ':attribute deve ser :digits digits',
    'digits_between' => ':attribute deve estar entre :min e :max dígitos',
    'dimensions' => ':attribute tem dimensões de imagem inválidas',
    'distinct' => ':attribute tem um valor duplicado',
    'email' => ':attribute deve ser um endereço de e-mail válido',
    'ends_with' => ':attribute deve terminar com um dos seguintes: :values',
    'enum' => ':attribute selecionado é inválido',
    'exists' => ':attribute selecionado é inválido',
    'file' => ':attribute deve ser um arquivo',
    'filled' => ':attribute deve ter um valor',
    'gt' => [
        'array' => ':attribute deve ter mais de :value itens',
        'file' => ':attribute deve ser maior que :value Kb',
        'numeric' => ':attribute deve ser maior que :value',
        'string' => ':attribute deve ser maior que :value caracteres',
    ],
    'gte' => [
        'array' => ':attribute deve ter :value itens ou mais',
        'file' => ':attribute deve ser maior ou igual a :value kilobytes',
        'numeric' => ':attribute deve ser maior ou igual a :value',
        'string' => ':attribute deve ser maior ou igual a :value caracteres',
    ],
    'image' => ':attribute deve ser uma imagem',
    'in' => ':attribute selecionado é inválido',
    'in_array' => ':attribute não existe em :other',
    'integer' => ':attribute deve ser um número',
    'ip' => ':attribute deve ser um endereço IP válido',
    'ipv4' => ':attribute deve ser um endereço IPv4 válido',
    'ipv6' => ':attribute deve ser um endereço IPv6 válido',
    'json' => ':attribute deve ser uma string JSON válida',
    'lt' => [
        'array' => ':attribute deve ter menos que :value itens',
        'file' => ':attribute deve ser menor que :value Kb',
        'numeric' => ':attribute deve ser menor que :value',
        'string' => ':attribute deve ser menor que :value caracteres',
    ],
    'lte' => [
        'array' => ':attribute não deve ter mais que :value itens',
        'file' => ':attribute deve ser menor ou igual a :value kilobytes',
        'numeric' => ':attribute deve ser menor ou igual a :value',
        'string' => ':attribute deve ser menor ou igual a :value caracteres',
    ],
    'mac_address' => ':attribute deve ser um endereço MAC válido',
    'max' => [
        'array' => ':attribute não deve ter mais que :max itens',
        'file' => ':attribute não deve ser maior que :max Kb',
        'numeric' => ':attribute não deve ser maior que :max',
        'string' => ':attribute não deve ser maior que :max caracteres',
    ],
    'mimes' => ':attribute deve ser um arquivo do tipo: :values',
    'mimetypes' => ':attribute deve ser um arquivo do tipo: :values',
    'min' => [
        'array' => ':attribute deve ter pelo menos :min itens',
        'file' => ':attribute deve ter pelo menos :min Kb',
        'numeric' => ':attribute deve ser pelo menos :min',
        'string' => ':attribute deve ter pelo menos :min caracteres',
    ],
    'multiple_of' => ':attribute deve ser um múltiplo de :value',
    'not_in' => ':attribute selecionado é inválido',
    'not_regex' => 'formato :attribute é inválido',
    'numeric' => ':attribute deve ser um número',
    'present' => ':attribute deve estar presente',
    'prohibited' => ':attribute é proibido',
    'prohibited_if' => ':attribute é proibido quando :other é :value',
    'prohibited_unless' => ':attribute é proibido a menos que :other esteja em :values',
    'prohibits' => ':attribute proíbe :other de estar presente',
    'regex' => 'formato :attribute é inválido',
    'required' => ':attribute é obrigatório',
    'required_array_keys' => ':attribute deve conter entradas para: :values',
    'required_if' => ':attribute é obrigatório quando :other é :value',
    'required_unless' => ':attribute é obrigatório a menos que :other esteja em :values',
    'required_with' => ':attribute é obrigatório quando :values está presente',
    'required_with_all' => ':attribute é obrigatório quando :values estão presentes',
    'required_without' => ':attribute é obrigatório quando :values não está presente',
    'required_without_all' => ':attribute é obrigatório quando nenhum dos :values está presente',
    'same' => ':attribute e :other devem corresponder',
    'size' => [
        'array' => ':attribute deve conter :size itens',
        'file' => ':attribute deve ser :size Kb',
        'numeric' => ':attribute deve ser :size',
        'string' => ':attribute deve ser :size caracteres',
    ],
    'starts_with' => ':attribute deve começar com um dos seguintes: :values',
    'string' => ':attribute deve ser uma string',
    'timezone' => ':attribute deve ser um fuso horário válido',
    'unique' => ':attribute já foi usado',
    'uploaded' => ':attribute falhou ao carregar',
    'url' => ':attribute deve ser um URL válido',
    'uuid' => ':attribute deve ser um UUID válido',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        "doc_cpf" => "CPF",
        "nome" => "Nome",
        "sobrenome" => "Sobrenome",
        "genero" => "Gênero",
        "email" => "E-mail",
        "data_nascimento" => "Data de Nascimento",
        "profissao" => "Profissão",
        "endereco_cep" => "CEP",
        "endereco" => "Endereço",
        "endereco_num" => "Número",
        "endereco_complemento" => "Complemento",
        "endereco_bairro" => "Bairro",
        "endereco_cidade" => "Cidade",
        "endereco_estado" => "Estado",
        "estado_civil_id" => "Estado Civil",
        "assunto_id" => "Assunto",
        "conclusao" => "Conclusão",
    ],

];
