{{-- Campos compartilhados entre os modais Novo Gateway e Editar Gateway --}}

{{-- Tokens --}}
<div class="space-y-4 border-t pt-4">
    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Credenciais</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input label="Token Live" wire:model.defer="gatewayTokenLive" placeholder="Token de produção" type="password" class="w-full" />
        </div>
        <div>
            <x-input label="Senha Token Live" wire:model.defer="gatewayTokenLivePass" placeholder="Senha do token" type="password" class="w-full" />
        </div>
        <div>
            <x-input label="Token Test" wire:model.defer="gatewayTokenTest" placeholder="Token de teste" type="password" class="w-full" />
        </div>
        <div>
            <x-input label="Senha Token Test" wire:model.defer="gatewayTokenTestPass" placeholder="Senha do token" type="password" class="w-full" />
        </div>
    </div>
</div>

{{-- Métodos de Pagamento --}}
<div class="space-y-4 border-t pt-4">
    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Métodos de Pagamento</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <x-checkbox wire:model.defer="gatewayPayBoleto" label="Boleto" />
        <x-checkbox wire:model.defer="gatewayPayPix" label="PIX" />
        <x-checkbox wire:model.defer="gatewayPaySlipPix" label="Slip PIX" />
        <x-checkbox wire:model.defer="gatewayPayCardDebit" label="Cartão Débito" />
        <x-checkbox wire:model.defer="gatewayPayCardCredit" label="Cartão Crédito" />
    </div>
</div>

{{-- Parcelamento Cartão Crédito --}}
<div class="space-y-4 border-t pt-4">
    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Parcelamento Cartão Crédito</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input type="number" label="Máx. Parcelas" wire:model.defer="gatewayPayCardCreditInstallmentMax" min="1" max="12" class="w-full" />
        </div>
        <div>
            <x-input type="number" step="0.01" label="Valor Mínimo Parcela (R$ 12,34 = 1234)" wire:model.defer="gatewayPayCardCreditInstallmentAmountMin" min="0" class="w-full" />
        </div>
    </div>
</div>

{{-- Slip PIX --}}
<div class="space-y-4 border-t pt-4">
    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Slip PIX</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-input type="number" label="Máx. Parcelas" wire:model.defer="gatewayPaySlipPixInstallmentMax" min="1" max="12" class="w-full" />
        </div>
        <div>
            <x-input type="number" step="0.01" label="Valor Mínimo Parcela (R$ 12,34 = 1234)" wire:model.defer="gatewayPaySlipPixInstallmentAmountMin" min="0" class="w-full" />
        </div>
    </div>
</div>

{{-- Status --}}
<div class="space-y-4 border-t pt-4">
    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide pb-2">Status</h3>
    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
        <div>
            <p class="text-sm font-medium text-gray-900">Gateway Ativo</p>
            <p class="text-xs text-gray-500 mt-1">Ative ou desative este gateway de pagamento</p>
        </div>
        <x-toggle wire:model.defer="gatewayPayActive" lg color="green" />
    </div>
</div>

{{-- Uso em Eventos e Campanhas --}}
<div class="space-y-4 border-b pb-6">
    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Uso do Gateway</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div>
                <p class="text-sm font-medium text-gray-900">Usar em Eventos</p>
                <p class="text-xs text-gray-500 mt-1">Permitir uso deste gateway em eventos</p>
            </div>
            <x-toggle wire:model.defer="gatewayUseEvents" lg color="blue" />
        </div>
        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg border border-purple-200">
            <div>
                <p class="text-sm font-medium text-gray-900">Usar em Campanhas</p>
                <p class="text-xs text-gray-500 mt-1">Permitir uso deste gateway em campanhas</p>
            </div>
            <x-toggle wire:model.defer="gatewayUseCampaigns" lg color="purple" />
        </div>
    </div>
</div>
