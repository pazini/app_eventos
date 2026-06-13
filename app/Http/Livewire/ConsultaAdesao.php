<?php

namespace App\Http\Livewire;

use App\Models\AppBuyers;
use App\Models\ModCampaign\CampaignOrder;
use Livewire\Component;
use Carbon\Carbon;

class ConsultaAdesao extends Component
{
    // Campos do formulário
    public $doc_num;
    public $birth_date;
    public $contact_country = '55'; // Brasil por padrão
    public $contact_ddd;
    public $contact_num;
    
    // Estado da consulta
    public $authenticated = false;
    public $buyer = null;
    public $orders = [];
    public $errorMessage = '';
    
    protected $rules = [
        'doc_num' => 'required|cpf_cnpj',
        'birth_date' => 'required|date|before:today',
        'contact_country' => 'required|string',
        'contact_ddd' => 'required_if:contact_country,55|string|min:2|max:3',
        'contact_num' => 'required|string|min:8|max:15',
    ];
    
    protected $messages = [
        'doc_num.required' => 'CPF/CNPJ é obrigatório',
        'doc_num.cpf_cnpj' => 'CPF/CNPJ inválido',
        'birth_date.required' => 'Data de nascimento é obrigatória',
        'birth_date.date' => 'Data de nascimento inválida',
        'birth_date.before' => 'Data de nascimento deve ser anterior a hoje',
        'contact_country.required' => 'País é obrigatório',
        'contact_ddd.required_if' => 'DDD é obrigatório para telefones brasileiros',
        'contact_ddd.min' => 'DDD deve ter no mínimo 2 dígitos',
        'contact_ddd.max' => 'DDD deve ter no máximo 3 dígitos',
        'contact_num.required' => 'Número de telefone é obrigatório',
        'contact_num.min' => 'Número de telefone deve ter no mínimo 8 dígitos',
        'contact_num.max' => 'Número de telefone deve ter no máximo 15 dígitos',
    ];
    
    public function updatedContactCountry($value)
    {
        // Limpa DDD se não for Brasil
        if ($value !== '55') {
            $this->contact_ddd = null;
        }
    }
    
    public function consultar()
    {
        // Limpa mensagens de erro anteriores
        $this->errorMessage = '';
        $this->authenticated = false;
        $this->buyer = null;
        $this->orders = [];
        
        // Valida os dados
        $this->validate();
        
        // Sanitiza CPF/CNPJ (remove caracteres não numéricos)
        $docNumClean = preg_replace('/[^0-9]/', '', $this->doc_num);
        
        // Valida se é CPF (11 dígitos) - apenas CPF pode acessar
        if (empty($docNumClean) || strlen($docNumClean) !== 11) {
            $this->errorMessage = 'Apenas doadores com CPF informado podem consultar suas adesões.';
            return;
        }
        
        // Busca o comprador pelo CPF
        $buyer = AppBuyers::where('doc_num', $docNumClean)->first();
        
        if (!$buyer) {
            $this->errorMessage = 'Nenhum cadastro encontrado com este CPF.';
            return;
        }
        
        // Valida data de nascimento
        $birthDate = Carbon::parse($this->birth_date);
        $buyerBirthDate = $buyer->birth_date ? Carbon::parse($buyer->birth_date) : null;
        
        if (!$buyerBirthDate || !$birthDate->isSameDay($buyerBirthDate)) {
            $this->errorMessage = 'Data de nascimento não confere com o cadastro.';
            return;
        }
        
        // Valida telefone
        $contactNumClean = preg_replace('/[^0-9]/', '', $this->contact_num);
        $buyerContactNumClean = preg_replace('/[^0-9]/', '', $buyer->contact_num ?? '');
        
        // Monta o número completo para comparação
        $fullPhone = $this->contact_country . ($this->contact_ddd ?? '') . $contactNumClean;
        $buyerFullPhone = ($buyer->contact_country ?? '') . ($buyer->contact_ddd ?? '') . $buyerContactNumClean;
        
        if ($fullPhone !== $buyerFullPhone) {
            $this->errorMessage = 'Telefone não confere com o cadastro.';
            return;
        }
        
        // Se chegou aqui, autenticou com sucesso
        $this->authenticated = true;
        $this->buyer = $buyer;
        
        // Busca todas as adesões deste comprador
        $this->orders = CampaignOrder::where('buyer_id', $buyer->id)
            ->with(['campaign'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function sair()
    {
        $this->authenticated = false;
        $this->buyer = null;
        $this->orders = [];
        $this->doc_num = '';
        $this->birth_date = '';
        $this->contact_country = '55';
        $this->contact_ddd = '';
        $this->contact_num = '';
        $this->errorMessage = '';
    }
    
    public function render()
    {
        return view('livewire.consulta-adesao')
            ->layout('layouts.app-public-campanha');
    }
}
