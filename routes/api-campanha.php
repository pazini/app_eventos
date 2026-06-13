<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CampaignApiController;
use App\Http\Controllers\Api\CampaignWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes - Campanhas
|--------------------------------------------------------------------------
|
| Rotas de API dedicadas ao módulo de Campanhas.
| Separadas das rotas de eventos para melhor organização.
|
*/

// Rotas públicas
Route::prefix('v1/campaigns')->group(function () {

    // Consulta pública de campanha (por slug hierárquico)
    Route::get('/{customer_organization_slug}/{campaign_slug}', [CampaignApiController::class, 'show']);

    // Resumo público da campanha (detalhes + financeiro sumarizado)
    Route::match(['get', 'post'], '/{customer_organization_slug}/{campaign_slug}/summary', [CampaignApiController::class, 'summary']);

    // Criar pedido/order para uma campanha
    Route::post('/{customer_organization_slug}/{campaign_slug}/orders', [CampaignApiController::class, 'createOrder']);

    // Consultar status de um pedido
    Route::get('/orders/{order_control}', [CampaignApiController::class, 'getOrder']);
});

// Webhook Safe2Pay exclusivo para campanhas
Route::match(['get', 'post'],'/webhook/safe2pay/campaigns/{orderId}/{paymentId}', [CampaignWebhookController::class, 'handleSafe2PayWebhook'])->name('api.campaigns.webhook.safe2pay');

// Rotas administrativas (requerem autenticação)
Route::middleware(['auth:sanctum'])->prefix('v1/admin/campaigns')->group(function () {

    // Listar campanhas do cliente
    Route::get('/', [CampaignApiController::class, 'index']);

    // Criar campanha
    Route::post('/', [CampaignApiController::class, 'store']);

    // Atualizar campanha
    Route::put('/{campaign_id}', [CampaignApiController::class, 'update']);

    // Deletar/arquivar campanha
    Route::delete('/{campaign_id}', [CampaignApiController::class, 'destroy']);

    // Métricas de uma campanha
    Route::get('/{campaign_id}/metrics', [CampaignApiController::class, 'metrics']);

    // Transações de uma campanha
    Route::get('/{campaign_id}/transactions', [CampaignApiController::class, 'transactions']);

    // Pedidos de uma campanha
    Route::get('/{campaign_id}/orders', [CampaignApiController::class, 'orders']);
});

