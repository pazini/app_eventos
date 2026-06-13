<?php

namespace App\Http\Livewire;

use App\Models\AppBuyers;
use App\Models\AppEvent\AppEventOrder;
use Livewire\Component;

class MinhasComprasDetalhes extends Component
{
    public $order;
    public $buyer;

    // App-Version (modo empresa - sempre mobile)
    public $isAppVersion = false;
    public $appCustomerId = null;
    public $appCustomerName = null;

    public function mount($uuid)
    {
        // Detecta se está no modo app-version
        $this->isAppVersion = request()->is('app-version*') || session('app_mode');
        if ($this->isAppVersion) {
            $this->appCustomerId = session('app_customer_id');
            $this->appCustomerName = session('app_customer_name');
        }

        // Verifica se está autenticado
        $authData = session('minhas_compras_auth');
        if (!$authData) {
            session()->flash('error', 'Você precisa estar autenticado para visualizar os detalhes da compra.');
            $route = $this->isAppVersion ? 'app-version-minhas-compras' : 'minhas-compras';
            return redirect()->route($route);
        }

        // Busca o comprador da sessão
        $this->buyer = AppBuyers::find($authData['buyer_id']);
        if (!$this->buyer) {
            session()->forget('minhas_compras_auth');
            session()->flash('error', 'Sessão expirada. Faça login novamente.');
            $route = $this->isAppVersion ? 'app-version-minhas-compras' : 'minhas-compras';
            return redirect()->route($route);
        }

        // Busca o pedido pelo UUID
        $this->order = AppEventOrder::with([
            'event.organizer',
            'itens',
            'tickets',
            'payments',
            'paymentsSlip'
        ])->where('id', $uuid)->first();

        // Verifica se o pedido existe
        if (!$this->order) {
            session()->flash('error', 'Compra não encontrada.');
            $route = $this->isAppVersion ? 'app-version-minhas-compras' : 'minhas-compras';
            return redirect()->route($route);
        }

        // Verifica se o pedido pertence ao comprador autenticado (usando buyer_id)
        if ($this->order->buyer_id !== $this->buyer->id) {
            session()->flash('error', 'Você não tem permissão para visualizar esta compra.');
            $route = $this->isAppVersion ? 'app-version-minhas-compras' : 'minhas-compras';
            return redirect()->route($route);
        }
    }

    public function sair()
    {
        // Limpa apenas a sessão de autenticação de compras
        session()->forget('minhas_compras_auth');

        // Redireciona de acordo com o modo
        if ($this->isAppVersion) {
            return redirect()->route('app-version-home');
        }

        return redirect()->route('eventos-home');
    }

    public function voltar()
    {
        $route = $this->isAppVersion ? 'app-version-minhas-compras' : 'minhas-compras';
        return redirect()->route($route);
    }

    public function render()
    {
        $layout = $this->isAppVersion ? 'layouts.app-version-bare' : 'layouts.app-pep-home';
        return view('livewire.minhas-compras-detalhes')
            ->layout($layout);
    }
}
