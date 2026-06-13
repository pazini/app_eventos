<?php

use App\Http\Controllers\Notificacao\MailController;
use App\Http\Controllers\ModEvent\EventoGatewayController;
use App\Http\Livewire\Compras\ExibirCompra;
use App\Http\Livewire\Evento\AppEvento;
use App\Http\Livewire\AppEventoIngresso;
use App\Http\Livewire\AppEventoVouchers;
use App\Http\Livewire\Checkin\Checkin;
use App\Http\Livewire\Dashboard\Dashboard;
use App\Http\Livewire\Dashboard\Home as DashboardHome;
use App\Http\Livewire\Dashboard\DashboardEvento;
use App\Http\Livewire\Dashboard\DashboardVendas;
use App\Http\Livewire\Dashboard\DashboardVendasCampanhas;
use App\Http\Livewire\DashboardEventoVendasCadastroManual;
use App\Http\Livewire\DashboardEventoVendasSumario;
use App\Http\Livewire\DashboardPedidos;
use App\Http\Livewire\DashboardFinanceiro;
use App\Http\Livewire\DashboardFinanceiroGestaoOrcamentaria;
use App\Http\Livewire\DashboardFinanceiroGestaoOrcamentariaDespesa;
use App\Http\Livewire\DashboardFinanceiroGestaoOrcamentariaReceita;
use App\Http\Livewire\DashboardFinanceiroTransacoes;
use App\Http\Livewire\Dashboard\DashboardCampanhas;
use App\Http\Livewire\Dashboard\CampanhaNova;
use App\Http\Livewire\Dashboard\CampanhaDetalhes;
use App\Http\Livewire\Campanha\CampanhaPublica;
use App\Http\Livewire\Campanha\AppCampanha;
use App\Http\Livewire\Campanha\AppCampanhaByApp;
use App\Http\Livewire\Campanha\AppCampanhaByAppMinhasDoacoes;
use App\Http\Livewire\Evento\AlteraEvento;
use App\Http\Livewire\Evento\AppEventoPatrocinar;
use App\Http\Livewire\Evento\CampoAdicional;
use App\Http\Livewire\Evento\EventoNovo;
use App\Http\Livewire\Evento\EventoPatrocinio;
use App\Http\Livewire\Evento\LayoutPagina;
use App\Http\Livewire\Evento\Lote;
use App\Http\Livewire\Evento\Patrocinio;
use App\Http\Livewire\Faturamento\FaturamentoTable;
use App\Http\Livewire\Faturamento\GerarFatura;
use App\Http\Livewire\Faturamento\ValidarFatura;
use App\Http\Livewire\Financeiro\GestaoOrcamentariaGrid;
use App\Http\Livewire\Modules\ModuleConfiguracoes;
use App\Http\Livewire\Modules\ModuleConfiguracoesUsuario;
use App\Http\Livewire\Notifica\NotificaCriar;
use App\Http\Livewire\Notifica\NotificaDashboard;
use App\Http\Livewire\Organizadores\Organizadores;
use App\Http\Livewire\Organizadores\OrganizadoresInstituicoes;
use App\Http\Livewire\Organizadores\OrganizadoresSetores;
use App\Http\Livewire\Organizadores\OrganizadoresUsuarios;
use App\Http\Livewire\Organizadores\GerenciarEventos;
use App\Http\Livewire\Organizadores\GerenciarCampanhas;
use App\Http\Livewire\Pagamento\MetodoPagamento;
use App\Http\Livewire\Pagamento\RealizarPagamento;
use App\Http\Livewire\UserProfile;
use BaconQrCode\Encoder\QrCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// ANULANDO ROTAS
Route::match(['get', 'post'], '/register', function () {
    return abort(404);
})->name('register');

// DASHBOARD ANTERIOR
Route::match(['get', 'post'], '/dashboard', function () {
    // Usa a configuração do .env em vez de hardcode
    $painelDomain = config('domains.painel');
    return redirect($painelDomain . '/');
})->name('dashboard-old');

// ====================================================================================
// ROTAS PRINCIPAIS DA APLICAÇÃO
// ====================================================================================

// ROTA ALTERNATIVA PARA ADESÃO (redireciona para a página da campanha)
Route::get('/adesao/{localizador}', function ($localizador) {

    $order = \App\Models\ModCampaign\CampaignOrder::where('order_control', $localizador)->first();

    if (!$order) {
        abort(404, 'Adesão não encontrada');
    }

    $campaign = $order->campaign;

    return redirect(campanhaUrl($campaign->customer_organization_slug, $campaign->slug, $order->id));
})->name('adesao-localizador');

// CONSULTA DE ADESÕES
Route::get('/adesao', \App\Http\Livewire\ConsultaAdesao::class)->name('consulta-adesao');

// Aplica as rotas de home usando configuração do .env
$homeHost = parse_url(config('domains.home'), PHP_URL_HOST);

// Em desenvolvimento local (127.0.0.1 ou localhost), usa prefix ao invés de domain
$isLocalDev = in_array(request()->getHost(), ['127.0.0.1', 'localhost']);

// == PAINEL ADMINISTRATIVO ===================================================================

// Aplica as rotas do painel usando configuração do .env
$painelHost = parse_url(config('domains.painel'), PHP_URL_HOST);

// Helper function para definir rotas do PAINEL ADMINISTRATIVO
$painelRoutes = function ($fallback = null) {

    // ROTAS PÚBLICAS (sem autenticação)
    Route::get('/vendas/evento/{target_slug}/{target_id}/participantes', DashboardPedidos::class)
        ->defaults('target_ref', 'evento')
        ->defaults('view_status', 'participantes')
        ->name('dashboard-vendas-participantes-public');

    Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () use ($fallback) {

        // Tela neutra inicial do painel: visão geral e cards de módulos
        Route::get('/', DashboardHome::class)->name('dashboard');
        Route::get('/campanhas', DashboardCampanhas::class)->name('dashboard-campanhas');

        // Painel de campanhas
        Route::get('/campanhas/nova-campanha', CampanhaNova::class)->name('dashboard-campanhas-nova');

        // ORGANIZADORES - CAMPANHAS (Nova interface unificada)
        Route::prefix('/campanhas/organizadores')->group(function () {

            // Rota principal unificada
            Route::get('/', \App\Http\Livewire\Organizadores\GerenciarCampanhas::class)->name('campanhas-organizadores');

            // Rotas antigas mantidas para compatibilidade (redirecionam para a nova interface)
            Route::get('/instituicoes', function () {
                return redirect()->route('campanhas-organizadores');
            })->name('campanhas-organizadores-instituicoes');

            Route::get('/setores', function () {
                return redirect()->route('campanhas-organizadores');
            })->name('campanhas-organizadores-setores');

            Route::get('/usuarios', function () {
                return redirect()->route('campanhas-organizadores');
            })->name('campanhas-organizadores-usuarios');
        });

        // Rotas de campanha específica
        Route::get('/campanhas/{campaign_id}', CampanhaDetalhes::class)->name('dashboard-campanhas-detalhes');
        Route::get('/campanhas/{campaign_id}/detalhes', CampanhaDetalhes::class)
            ->defaults('tab', 'detalhes')
            ->name('dashboard-campanhas-detalhes-detalhes');
        Route::get('/campanhas/{campaign_id}/adesoes', CampanhaDetalhes::class)
            ->defaults('tab', 'adesoes')
            ->name('dashboard-campanhas-detalhes-adesoes');
        Route::get('/campanhas/{campaign_id}/adesoes/{order_id}', CampanhaDetalhes::class)
            ->defaults('tab', 'adesoes')
            ->name('dashboard-campanhas-detalhes-adesoes-item');
        Route::get('/campanhas/{campaign_id}/participantes', CampanhaDetalhes::class)
            ->defaults('tab', 'participantes')
            ->name('dashboard-campanhas-detalhes-participantes');
        Route::get('/campanhas/{campaign_id}/questionarios', CampanhaDetalhes::class)
            ->defaults('tab', 'questionarios')
            ->name('dashboard-campanhas-detalhes-questionarios');
        Route::get('/campanhas/{campaign_id}/transacoes', CampanhaDetalhes::class)
            ->defaults('tab', 'transacoes')
            ->name('dashboard-campanhas-detalhes-transacoes');
        Route::get('/campanhas/{campaign_id}/transacoes/{transaction_id}', CampanhaDetalhes::class)
            ->defaults('tab', 'transacoes')
            ->name('dashboard-campanhas-detalhes-transacoes-item');
        Route::get('/campanhas/{campaign_id}/transações', function (string $campaign_id) {
            return redirect()->route('dashboard-campanhas-detalhes-transacoes', ['campaign_id' => $campaign_id]);
        })->name('dashboard-campanhas-detalhes-transacoes-acento');
        Route::get('/campanhas/{campaign_id}/transações/{transaction_id}', function (string $campaign_id, string $transaction_id) {
            return redirect()->route('dashboard-campanhas-detalhes-transacoes-item', [
                'campaign_id' => $campaign_id,
                'transaction_id' => $transaction_id,
            ]);
        })->name('dashboard-campanhas-detalhes-transacoes-item-acento');
        Route::get('/campanhas/{campaign_id}/editar', CampanhaNova::class)->name('dashboard-campanhas-editar');
        Route::get('/campanhas/{campaign_id}/metodo-pagamento', \App\Http\Livewire\Dashboard\CampanhaMetodoPagamento::class)->name('dashboard-campanhas-metodo-pagamento');

        // Painel de eventos (lista, filtros, seleção de organizador/evento)
        Route::get('/eventos', Dashboard::class)->name('dashboard-eventos');

        // ORGANIZADORES - EVENTOS (Nova interface unificada)
        Route::prefix('/eventos/organizadores')->group(function () {
            // Rota principal unificada
            Route::get('/', GerenciarEventos::class)->name('eventos-organizadores');

            // Rotas antigas mantidas para compatibilidade (redirecionam para a nova interface)
            Route::get('/instituicoes', function () {
                return redirect()->route('eventos-organizadores');
            })->name('eventos-organizadores-instituicoes');

            Route::get('/setores', function () {
                return redirect()->route('eventos-organizadores');
            })->name('eventos-organizadores-setores');

            Route::get('/usuarios', function () {
                return redirect()->route('eventos-organizadores');
            })->name('eventos-organizadores-usuarios');
        });

        Route::prefix('/evento')->group(function () {
            $uuidPattern = '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}';
            Route::get('/{event_id}', DashboardEvento::class)
                ->name('evento-by-uuid')
                ->where('event_id', $uuidPattern);
            Route::get('/{event_id}/layout-pagina', LayoutPagina::class)
                ->name('evento-layout-pagina-uuid')
                ->where('event_id', $uuidPattern);
            Route::get('/{event_id}/vendas/sumario', DashboardEventoVendasSumario::class)
                ->name('dashboard-evento-vendas-sumario-uuid')
                ->where('event_id', $uuidPattern);
            Route::get('/{event_id}/vendas/{controle}', DashboardFinanceiroTransacoes::class)
                ->name('dashboard-evento-vendas-controle-uuid')
                ->where('event_id', $uuidPattern);
            Route::get('/{event_id}/vendas', DashboardFinanceiroTransacoes::class)
                ->name('dashboard-evento-vendas-uuid')
                ->where('event_id', $uuidPattern);
            Route::get('/{event_id}/notificacoes', NotificaDashboard::class)
                ->name('notifica-uuid')
                ->where('event_id', $uuidPattern);
            Route::get('/{event_id}/financeiro/gestao-orcamentaria', DashboardFinanceiroGestaoOrcamentaria::class)
                ->name('dashboard-financeiro-gestao-orcamentaria-uuid')
                ->where('event_id', $uuidPattern);
            Route::get('/', DashboardEvento::class)->name('dashboard-evento');
            Route::get('/novo-evento', EventoNovo::class)->name('novo-evento');
            Route::get('/altera-evento', AlteraEvento::class)->name('altera-evento');
            Route::get('/layout-pagina', LayoutPagina::class)->name('evento-layout-pagina');
            Route::get('/metodo-pagamento', MetodoPagamento::class)->name('evento-metodo-pagamento');
            Route::get('/lote/{ticket_type_id?}', Lote::class)->name('evento-lote');
            Route::get('/campo-adicional', CampoAdicional::class)->name('evento-campo-adicional');
            Route::get('/vendas/sumario', DashboardEventoVendasSumario::class)->name('dashboard-evento-vendas-sumario');
            Route::get('/vendas/cadastrar', DashboardEventoVendasCadastroManual::class)->name('dashboard-evento-vendas-cadastro');
            Route::get('/vendas/{controle}/modificar-pagamentos', DashboardFinanceiroTransacoes::class)
                ->defaults('modo', 'modificar-pagamentos')
                ->name('dashboard-evento-vendas-controle-modificar');
            Route::get('/vendas/{controle}', DashboardFinanceiroTransacoes::class)->name('dashboard-evento-vendas-controle');
            Route::get('/vendas', DashboardFinanceiroTransacoes::class)->name('dashboard-evento-vendas');
            Route::get('/patrocinios', EventoPatrocinio::class)->name('evento-patrocinios');
            Route::get('/patrocinio/plano-patrocinio/{patrocinio_id?}', Patrocinio::class)->name('evento-plano-patrocinio');
        });

        // FINANCEIRO
        Route::prefix('/financeiro')->group(function () {
            Route::get('/', DashboardFinanceiro::class)->name('dashboard-financeiro');
            Route::get('/transacoes', DashboardFinanceiroTransacoes::class)->name('dashboard-financeiro-transacoes');
            Route::get('/gestao-orcamentaria', DashboardFinanceiroGestaoOrcamentaria::class)->name('dashboard-financeiro-gestao-orcamentaria');
            Route::get('/gestao-orcamentaria/receita', DashboardFinanceiroGestaoOrcamentariaReceita::class)->name('dashboard-financeiro-gestao-orcamentaria-receita');
            Route::get('/gestao-orcamentaria/despesa', DashboardFinanceiroGestaoOrcamentariaDespesa::class)->name('dashboard-financeiro-gestao-orcamentaria-despesa');
            Route::get('/gestao-orcamentaria/planilha', GestaoOrcamentariaGrid::class)->name('dashboard-financeiro-gestao-orcamentaria-planilha');
        });

        // FATURAMENTO
        Route::get('/faturamento-plataforma/{organizador_id?}', FaturamentoTable::class)->name('plataforma-faturamento');
        Route::get('/faturamento-plataforma/gerar-fatura/{evento_id}', GerarFatura::class)->name('plataforma-faturamento-gerar-fatura');

        // VALIDA FATURAS
        Route::get('/validar-faturas', ValidarFatura::class)->name('validar-faturas');

        // CONFIGURAÇÕES E PERFIL
        Route::get('/configuracoes', ModuleConfiguracoes::class)->name('configuracoes');
        Route::get('/configuracoes/novo-cliente', ModuleConfiguracoes::class)->defaults('standaloneCreate', true)->name('configuracoes-novo-cliente');
        Route::get('/configuracoes/{customer_id}/editar-cliente', ModuleConfiguracoes::class)
            ->whereUuid('customer_id')
            ->defaults('standaloneEdit', true)
            ->name('configuracoes-editar-cliente');
        Route::get('/configuracoes/{customer_id}/novo-usuario', ModuleConfiguracoesUsuario::class)
            ->whereUuid('customer_id')
            ->defaults('standaloneCreate', true)
            ->name('configuracoes-novo-usuario');
        Route::get('/configuracoes/{customer_id}/editar-usuario/{user_id}', ModuleConfiguracoesUsuario::class)
            ->whereUuid('customer_id')
            ->whereUuid('user_id')
            ->defaults('standaloneEdit', true)
            ->name('configuracoes-editar-usuario');
        Route::get('/configuracoes/{customer_id}', ModuleConfiguracoes::class)
            ->whereUuid('customer_id')
            ->name('configuracoes-customer');
        Route::get('/user-profile', UserProfile::class)->name('dashboard-user-profile');
        Route::get('/eventos-ultimas-vendas', DashboardVendas::class)->name('ultimas-vendas');
        Route::get('/ultimas-vendas', function () {
            return redirect()->route('ultimas-vendas');
        });
        Route::get('/campanhas-ultimas-adesoes', DashboardVendasCampanhas::class)->name('ultimas-vendas-campanhas');

        // NOTIFICA
        Route::prefix('/notificacoes')->group(function () {
            Route::get('/', NotificaDashboard::class)->name('notifica');
            Route::get('/nova', NotificaCriar::class)->name('notifica-nova');
            Route::get('/{notificacao_id}', NotificaDashboard::class)->name('notifica-exibir');
            Route::get('/{notificacao_id}/alterar', NotificaCriar::class)->name('notifica-alterar');
        });

        // =================================
        // SUPER ADMIN - WHITE LABEL MANAGEMENT
        // =================================
        Route::prefix('/super-administrador')
            ->middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'super.admin'])
            ->name('super-administrador.')
            ->group(function () {

                // Painel principal do super-admin
                Route::get('/', \App\Http\Livewire\SuperAdmin\Dashboard::class)->name('dashboard');
                Route::get('/listas', \App\Http\Livewire\SuperAdmin\ReferenceLists::class)->name('listas');
                Route::get('/sql', \App\Http\Livewire\SuperAdmin\SqlConsole::class)->name('sql');

                // Wizard de onboarding
                Route::get('/wizard', \App\Http\Livewire\SuperAdmin\WizardOnboarding::class)->name('wizard');

                // Gerenciamento de aplicações
                Route::prefix('/apps')->name('apps.')->group(function () {
                    Route::get('/', \App\Http\Livewire\SuperAdmin\AppsIndex::class)->name('index');
                    Route::get('/create', \App\Http\Livewire\SuperAdmin\AppsCreate::class)->name('create');
                    Route::get('/{app}/edit', \App\Http\Livewire\SuperAdmin\AppsEdit::class)->name('edit');
                });

                // Gerenciamento de usuários (futuro)
                // Route::prefix('/users')->name('users.')->group(function () {
                //     Route::get('/', \App\Http\Livewire\SuperAdmin\UsersIndex::class)->name('index');
                //     Route::get('/create', \App\Http\Livewire\SuperAdmin\UsersCreate::class)->name('create');
                //     Route::get('/{user}/edit', \App\Http\Livewire\SuperAdmin\UsersEdit::class)->name('edit');
                // });

                // Estatísticas e relatórios (futuro)
                // Route::get('/analytics', \App\Http\Livewire\SuperAdmin\Analytics::class)->name('analytics');
                // Route::get('/reports', \App\Http\Livewire\SuperAdmin\Reports::class)->name('reports');
            });

        // DASHBOARD
        Route::get('/dashboard/{target_ref}/{target_slug}/{target_id}/{view_type?}', DashboardPedidos::class)->name('dashboard-pedidos');

        // VENDAS
        Route::get('/vendas/{target_ref}/{target_slug}/{target_id}/{view_status?}', DashboardPedidos::class)->name('dashboard-vendas');

        // Fallback para rotas não encontradas dentro do painel
        Route::fallback(function () use ($fallback) {
            if (is_callable($fallback)) {
                return $fallback();
            }

            session()->flash('error', 'Página não localizada ou inexistente');
            return redirect()->route('dashboard');
        });
    });
};

if ($isLocalDev) {
    // Desenvolvimento local: /painel/*
    Route::prefix('/painel')->group($painelRoutes);
} else {
    if ($painelHost !== 'painel.proeventpay.com.br') {
        Route::domain('painel.proeventpay.com.br')->group(function () use ($painelRoutes) {
            $painelRoutes(function () {
                return redirect()->away('https://painel.proeventpay.com.br/');
            });
        });
    }

    // Produção: subdomínio painel.*
    Route::domain($painelHost)->group($painelRoutes);
}

// == CAMPANHAS =====================================================================================

// Aplica as rotas do campanhas usando configuração do .env
$campanhasHost = parse_url(config('domains.campanhas'), PHP_URL_HOST);

// Helper function para definir rotas de campanhas
$campanhasRoutes = function () {

    // MINHAS DOAÇÕES (equivalente de Minhas Compras para o domínio de campanhas)
    Route::get('/minhas-doacoes', \App\Http\Livewire\MinhasCompras::class)->name('minhas-doacoes');

    // HOME DE CAMPANHAS BY APP
    Route::get('/app/{appUserUuid}/minhas-doacoes', AppCampanhaByAppMinhasDoacoes::class)->name('app-campanhas-user-minhasdoacoes');
    Route::get('/app/{appUserUuid}', AppCampanhaByApp::class)->name('app-campanhas-user-home');
    Route::get('/app', AppCampanhaByApp::class)->name('app-campanhas-home');

    // CAMPANHAS PÚBLICAS (URL hierárquica: /{empresa}/{campanha})
    Route::get('/{customer_organization_slug}/{campaign_slug}/{order_id?}', CampanhaPublica::class)->name('campanha-publica');

    // Rota catch-all para slugs de campanhas (deve ser a última)
    Route::get('/{slug}', AppCampanha::class)->name('campanha-home');

    // HOME DE CAMPANHAS
    Route::get('/', AppCampanha::class)->name('campanhas-home');
};

// Aplica as rotas de campanhas
if ($isLocalDev) {
    // Desenvolvimento local: /campanhas/*
    Route::prefix('/campanhas')->group($campanhasRoutes);
} else {
    // Produção: subdomínios
    Route::domain($campanhasHost)->group($campanhasRoutes);

    if ($campanhasHost !== 'campanhas.proeventpay.com.br') {
        Route::domain('campanhas.proeventpay.com.br')->group($campanhasRoutes);
    }
}

// == EVENTOS =====================================================================================

$eventosHost = parse_url(config('domains.eventos'), PHP_URL_HOST);

// Helper function para definir rotas de eventos
$eventosRoutes = function () {

    // MINHAS COMPRAS
    Route::get('/minhas-compras', \App\Http\Livewire\MinhasCompras::class)->name('minhas-compras');
    Route::get('/minhas-compras/{uuid}', \App\Http\Livewire\MinhasComprasDetalhes::class)->name('minhas-compras-detalhes');

    // INGRESSOS
    Route::get('/ingressos', function () {
        return abort(404);
    })->name('ingressos');
    Route::get('/ingressos/{order_control}/{order_id}/{order_control_dv?}', AppEventoIngresso::class)->name('evento-ingressos');

    // VOUCHERS
    Route::get('/vouchers', function () {
        return abort(404);
    })->name('vouchers');
    Route::get('/vouchers/{localizador}/{order_id}/{order_control_dv?}', AppEventoVouchers::class)->name('evento-vouchers');

    // CHECKIN
    Route::get('/checkin', function () {
        return abort(404);
    })->name('checkin');
    Route::get('/checkin/{ref_target}/{ref_target_slug?}', Checkin::class)->name('checkin-target');

    // PATROCINAR
    Route::get('/patrocinicar/{slug}', AppEventoPatrocinar::class)->name('evento-patrocinicar');

    // PAGAMENTO
    Route::get('/pagamento/{targetType}/{localizador?}/{timestamp?}', RealizarPagamento::class)->name('pagamento');

    // COMPRA
    Route::get('/pedido/{localizador}/{timestamp?}', ExibirCompra::class)->name('compra-exibir');

    // APP-VERSION (modo empresa - sempre mobile)
    // IMPORTANTE: Reset deve vir ANTES para não conflitar
    Route::get('/app-version-reset', function () {
        session()->forget([
            'app_customer_id',
            'app_customer_name',
            'app_mode',
            'app_user_id',
            'app_source',
            'appUserUuid',
            'appSource',
        ]);
        return redirect()->route('app-version-home');
    })->name('app-version-reset');

    // Rotas específicas do app-version (ANTES do wildcard /{slug})
    Route::get('/app-version/minhas-compras', \App\Http\Livewire\MinhasCompras::class)->name('app-version-minhas-compras');
    Route::get('/app-version/minhas-compras/{uuid}', \App\Http\Livewire\MinhasComprasDetalhes::class)->name('app-version-minhas-compras-detalhes');

    // Evento específico no modo app-version
    Route::get('/app-version/{slug}', AppEvento::class)->name('app-version-evento');

    // Home do app-version (com ou sem customer selecionado)
    Route::get('/app-version', AppEvento::class)->name('app-version-home');

    // EVENTO ESPECÍFICO
    Route::get('/{slug}', AppEvento::class)->name('evento-home');

    // HOME DE EVENTOS (deve ser a última)
    Route::get('/', AppEvento::class)->name('eventos-home');
};

// Aplica as rotas de eventos
if ($isLocalDev) {
    // Desenvolvimento local: /eventos/*
    Route::prefix('/eventos')->group($eventosRoutes);
} else {
    // Produção: subdomínios
    Route::domain($eventosHost)->group($eventosRoutes);

    if ($eventosHost !== 'eventos.proeventpay.com.br') {
        Route::domain('eventos.proeventpay.com.br')->group($eventosRoutes);
    }
}

// ========================================================================================
// Redireciona tentativas de acesso ao painel via outros domínios para o subdomínio correto
// (Apenas em produção, pois em dev local já está usando /painel diretamente)
if (in_array(request()->getHost(), ['127.0.0.1', 'localhost'])) {
    Route::prefix('/painel')->group(function () {
        Route::any('{any?}', function () {
            // Usa a configuração do .env em vez de hardcode
            $painelDomain = config('domains.painel');

            // Redireciona para o subdomínio do painel mantendo a rota
            $currentPath = request()->path();
            $pathWithoutPainel = str_replace('painel/', '', $currentPath);
            $pathWithoutPainel = str_replace('painel', '', $pathWithoutPainel);

            if (empty($pathWithoutPainel) || $pathWithoutPainel === '/') {
                return redirect($painelDomain . '/');
            }

            return redirect($painelDomain . '/' . ltrim($pathWithoutPainel, '/'));
        })->where('any', '.*');
    });
}

// PÁGINA INICIAL EM DESENVOLVIMENTO LOCAL
// Redireciona para menu de seleção em ambiente local
if (in_array(request()->getHost(), ['127.0.0.1', 'localhost'])) {
    Route::get('/', function () {
        return view('dev-home');
    })->name('home');
} else {
    // Produção: redireciona para a home de eventos
    Route::get('/', function () {
        $eventosDomain = config('domains.eventos');
        return redirect()->away($eventosDomain, 302);
    })->name('home');
}

// ========================================================================================

// TESTE ENVIA EMAIL
Route::get('/send-mail', [MailController::class, 'enviarEmail']);

// TESTE EMAIL
Route::get('/email-teste/{email?}', function ($email = null) {

    return notificaEmail(emailTo: $email);

})->name('email-teste');

// Rota de teste White Label - Identificação de Tenant
Route::get('/test-tenant', function () {
    $app = session('app');
    return response()->json([
        'success' => true,
        'app_id' => $app->id ?? null,
        'app_name' => $app->app_name ?? null,
        'domain_primary' => $app->domain_primary ?? null,
        'colors' => [
            'primary' => $app->color_primary ?? null,
            'secondary' => $app->color_secondary ?? null,
            'accent' => $app->color_accent ?? null,
        ],
        'host' => request()->getHost(),
    ]);
});

#dd(config('domains.home'), session('app'),request()->getHost(),);

route::fallback(function () {
    return redirect()->away(config('domains.eventos'), 302);
});

return redirect($homeHost);
