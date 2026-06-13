<?php

namespace App\Http\Livewire;

use App\Models\AppBuyers;
use App\Models\AppEvent\AppEventOrder;
use App\Models\ModCampaign\CampaignOrder;
use Livewire\Component;
use Carbon\Carbon;

class MinhasCompras extends Component
{
    // Campos do formulário
    public $doc_num;
    public $birth_date_dd;
    public $birth_date_mm;
    public $birth_date_aaaa;

    // Campos adicionais para modo campanhas (auth via birth_date + phone)
    public $birth_date;
    public $contact_country = '55';
    public $contact_ddd;
    public $contact_num;

    // Estado da consulta
    public $authenticated = false;
    public $buyer = null;
    public $orders = [];
    public $errorMessage = '';

    // App-Version (modo empresa - sempre mobile)
    public $isAppVersion = false;
    public $appCustomerId = null;
    public $appCustomerName = null;

    // Modo híbrido: campanhas vs eventos
    public $isCampanhasPage = false;

    protected $rules = [
        'doc_num' => 'required|string|min:11|max:14',
        'birth_date_dd' => 'required_without:birth_date|numeric|min:1|max:31',
        'birth_date_mm' => 'required_without:birth_date|numeric|min:1|max:12',
        'birth_date_aaaa' => 'required_without:birth_date|numeric|min:1900|max:2100',
    ];

    protected $messages = [
        'doc_num.required' => 'CPF é obrigatório',
        'doc_num.min' => 'CPF inválido',
        'birth_date_dd.required_without' => 'Dia de nascimento é obrigatório',
        'birth_date_mm.required_without' => 'Mês de nascimento é obrigatório',
        'birth_date_aaaa.required_without' => 'Ano de nascimento é obrigatório',
        'birth_date.required' => 'Data de nascimento é obrigatória',
        'contact_num.required' => 'Telefone é obrigatório',
    ];

    public function updatedContactCountry($value)
    {
        if ($value !== '55') {
            $this->contact_ddd = null;
        }
    }

    public function mount()
    {
        // Detecta se está no modo campanhas (rota minhas-doacoes)
        $this->isCampanhasPage = request()->routeIs('minhas-doacoes');

        // Detecta se está no modo app-version
        $this->isAppVersion = request()->is('app-version*');

        // Se está no modo app-version, carrega dados da sessão
        if ($this->isAppVersion) {
            $this->appCustomerId = session('app_customer_id');
            $this->appCustomerName = session('app_customer_name');

            // Se não há customer selecionado, redireciona para seleção
            if (!$this->appCustomerId) {
                return redirect()->route('app-version-home');
            }
        }

        // Verifica se já está autenticado via sessão
        $sessionKey = $this->isCampanhasPage ? 'minhas_doacoes_auth' : 'minhas_compras_auth';
        $authData = session($sessionKey);
        if ($authData) {
            $this->buyer = AppBuyers::find($authData['buyer_id']);
            if ($this->buyer) {
                $this->authenticated = true;
                $this->loadOrders();
            } else {
                session()->forget($sessionKey);
            }
        }
    }

    public function consultar()
    {
        // Limpa mensagens de erro anteriores
        $this->errorMessage = '';
        $this->authenticated = false;
        $this->buyer = null;
        $this->orders = [];

        // Sanitiza CPF (remove caracteres não numéricos)
        $docNumClean = preg_replace('/[^0-9]/', '', $this->doc_num ?? '');

        if (empty($docNumClean) || strlen($docNumClean) !== 11) {
            $this->errorMessage = $this->isCampanhasPage
                ? 'Apenas doadores com CPF cadastrado podem consultar suas adesões.'
                : 'Apenas compradores com CPF cadastrado podem consultar suas compras.';
            return;
        }

        if ($this->isCampanhasPage) {
            $this->consultarCampanhas($docNumClean);
        } else {
            $this->consultarEventos($docNumClean);
        }
    }

    protected function consultarCampanhas(string $docNumClean)
    {
        $this->validate([
            'doc_num'         => 'required|string',
            'birth_date_dd'   => 'required|numeric|min:1|max:31',
            'birth_date_mm'   => 'required|numeric|min:1|max:12',
            'birth_date_aaaa' => 'required|numeric|min:1900|max:2100',
        ], [
            'birth_date_dd.required'   => 'Dia de nascimento é obrigatório',
            'birth_date_mm.required'   => 'Mês de nascimento é obrigatório',
            'birth_date_aaaa.required' => 'Ano de nascimento é obrigatório',
        ]);

        try {
            $birthDate = Carbon::createFromDate(
                $this->birth_date_aaaa,
                $this->birth_date_mm,
                $this->birth_date_dd
            );
        } catch (\Exception $e) {
            $this->errorMessage = 'Data de nascimento inválida.';
            return;
        }

        $myKey = $birthDate->format('Y-m-d') . '.' . $docNumClean;
        $buyer = AppBuyers::where('my_key', $myKey)->first();

        if (!$buyer) {
            $this->errorMessage = 'CPF ou data de nascimento incorretos. Verifique os dados e tente novamente.';
            return;
        }

        $this->authenticated = true;
        $this->buyer = $buyer;

        session()->put('minhas_doacoes_auth', [
            'buyer_id'         => $buyer->id,
            'authenticated_at' => now()->toDateTimeString(),
        ]);

        $this->loadOrders();
    }

    protected function consultarEventos(string $docNumClean)
    {
        $this->validate([
            'doc_num'          => 'required|string|min:11|max:14',
            'birth_date_dd'    => 'required|numeric|min:1|max:31',
            'birth_date_mm'    => 'required|numeric|min:1|max:12',
            'birth_date_aaaa'  => 'required|numeric|min:1900|max:2100',
        ], [
            'doc_num.required'         => 'CPF é obrigatório',
            'birth_date_dd.required'   => 'Dia de nascimento é obrigatório',
            'birth_date_mm.required'   => 'Mês de nascimento é obrigatório',
            'birth_date_aaaa.required' => 'Ano de nascimento é obrigatório',
        ]);

        try {
            $birthDate = Carbon::createFromDate(
                $this->birth_date_aaaa,
                $this->birth_date_mm,
                $this->birth_date_dd
            )->startOfDay();
        } catch (\Exception $e) {
            $this->errorMessage = 'Data de nascimento inválida.';
            return;
        }

        $myKey = $birthDate->format('Y-m-d') . '.' . $docNumClean;

        if (config('app.debug')) {
            \Log::debug('MinhasCompras - Tentativa de autenticação', [
                'cpf'        => $docNumClean,
                'birth_date' => $birthDate->format('Y-m-d'),
                'my_key'     => $myKey,
            ]);
        }

        $buyer = AppBuyers::where('my_key', $myKey)->first();

        if (!$buyer) {
            if (config('app.debug')) {
                \Log::debug('MinhasCompras - Buyer não encontrado', ['my_key' => $myKey]);
            }
            $this->errorMessage = 'Nenhum cadastro encontrado com este CPF e data de nascimento.';
            return;
        }

        if (config('app.debug')) {
            \Log::debug('MinhasCompras - Buyer encontrado', ['buyer_id' => $buyer->id, 'my_key' => $myKey]);
        }

        $this->authenticated = true;
        $this->buyer = $buyer;

        session()->put('minhas_compras_auth', [
            'buyer_id'         => $buyer->id,
            'authenticated_at' => now()->toDateTimeString(),
        ]);

        $this->loadOrders();
    }

    protected function loadOrders()
    {
        if ($this->isCampanhasPage) {
            // Modo campanhas: busca por buyer_id OU por buyer_doc_num (para pedidos sem buyer_id vinculado)
            $docNum = preg_replace('/[^0-9]/', '', $this->buyer->doc_num ?? '');
            $this->orders = CampaignOrder::where(function ($q) use ($docNum) {
                    $q->where('buyer_id', $this->buyer->id)
                      ->orWhere('buyer_doc_num', $docNum);
                })
                ->with(['campaign'])
                ->orderBy('created_at', 'desc')
                ->get();
            return;
        }

        // Modo eventos: busca todas as compras deste comprador pelo buyer_id
        $query = AppEventOrder::where('buyer_id', $this->buyer->id)
            ->with(['event.organizer']);

        // Se está no modo app-version, filtra por customer_id
        if ($this->isAppVersion && $this->appCustomerId) {
            $query->whereHas('event', function($q) {
                $q->where('customer_id', $this->appCustomerId);
            });
        }

        $this->orders = $query->orderBy('created_at', 'desc')->get();

        // Debug: Log de compras encontradas
        if (config('app.debug')) {
            \Log::debug('MinhasCompras - Pedidos carregados', [
                'buyer_id'        => $this->buyer->id,
                'my_key'          => $this->buyer->my_key,
                'total_pedidos'   => $this->orders->count(),
                'pedidos_ids'     => $this->orders->pluck('id')->toArray(),
                'is_app_version'  => $this->isAppVersion,
                'app_customer_id' => $this->appCustomerId,
            ]);
        }
    }

    public function sair()
    {
        $this->authenticated = false;
        $this->buyer = null;
        $this->orders = [];
        $this->doc_num = '';
        $this->birth_date_dd = '';
        $this->birth_date_mm = '';
        $this->birth_date_aaaa = '';
        $this->errorMessage = '';

        // Limpa sessão de autenticação
        session()->forget('minhas_compras_auth');
        session()->forget('minhas_doacoes_auth');

        // Redireciona de acordo com o modo
        if ($this->isAppVersion) {
            return redirect()->route('app-version-home');
        }

        if ($this->isCampanhasPage) {
            return redirect()->route('campanhas-home');
        }

        return redirect()->route('eventos-home');
    }

    public function render()
    {
        if ($this->isCampanhasPage) {
            $layout = 'layouts.app-pep-guest';
        } elseif ($this->isAppVersion) {
            $layout = 'layouts.app-version-bare';
        } else {
            $layout = 'layouts.app-pep-home';
        }

        return view('livewire.minhas-compras')->layout($layout);
    }
}
