{{-- Aba: Configuração Safe2Pay Master --}}
<div class="p-6 space-y-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-900">Safe2Pay - Conta Master (Pai)</h3>
        <p class="text-sm text-gray-500 mt-1">
            Configure os tokens da conta master do Safe2Pay para esta aplicação. Estes tokens serão usados como fallback/padrão para customers que não tiverem seus próprios tokens configurados.
        </p>
    </div>

    {{-- Card de Informação --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg class="h-6 w-6 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-sm text-blue-900">
                <strong class="block mb-1">Hierarquia de Contas Safe2Pay:</strong>
                <ul class="space-y-1 ml-4 list-disc">
                    <li><strong>APP (Conta Master/Pai):</strong> Tokens configurados aqui. Usados como fallback quando customer não tem tokens próprios.</li>
                    <li><strong>Customers (Contas Filhas):</strong> Cada customer pode ter seus próprios tokens Safe2Pay ou usar os tokens do APP.</li>
                </ul>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="saveSafe2Pay" class="space-y-6">

        {{-- Status e Modo --}}
        <div class="grid grid-cols-2 gap-6">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <label class="text-sm font-medium text-gray-700">Gateway Ativo</label>
                    <p class="text-xs text-gray-500">Ativar Safe2Pay para esta aplicação</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           wire:model="safe2pay_active"
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <label class="text-sm font-medium text-gray-700">Modo de Teste</label>
                    <p class="text-xs text-gray-500">Usar tokens de teste (sandbox)</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           wire:model="safe2pay_test_mode"
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-500"></div>
                </label>
            </div>
        </div>

        {{-- Tokens de Produção --}}
        <div class="border border-gray-200 rounded-lg p-4 space-y-4">
            <div class="flex items-center space-x-2">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <h4 class="text-sm font-semibold text-gray-900">Tokens de Produção (Live)</h4>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Token Live
                    </label>
                    <input type="text"
                           wire:model="safe2pay_token_live"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 font-mono text-sm"
                           placeholder="live_xxx">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Live
                    </label>
                    <input type="password"
                           wire:model="safe2pay_token_live_pass"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 font-mono text-sm"
                           placeholder="••••••••">
                </div>
            </div>
        </div>

        {{-- Tokens de Teste --}}
        <div class="border border-yellow-200 bg-yellow-50 rounded-lg p-4 space-y-4">
            <div class="flex items-center space-x-2">
                <svg class="h-5 w-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <h4 class="text-sm font-semibold text-gray-900">Tokens de Teste (Sandbox)</h4>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Token Test
                    </label>
                    <input type="text"
                           wire:model="safe2pay_token_test"
                           class="w-full px-3 py-2 border border-yellow-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500 font-mono text-sm bg-white"
                           placeholder="test_xxx">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password Test
                    </label>
                    <input type="password"
                           wire:model="safe2pay_token_test_pass"
                           class="w-full px-3 py-2 border border-yellow-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500 font-mono text-sm bg-white"
                           placeholder="••••••••">
                </div>
            </div>
        </div>

        {{-- Aviso de Segurança --}}
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-red-800">
                    <strong>Importante - Segurança:</strong>
                    <ul class="mt-1 ml-4 list-disc space-y-1">
                        <li>Nunca compartilhe seus tokens e senhas do Safe2Pay</li>
                        <li>Use sempre HTTPS em produção</li>
                        <li>Tokens de teste (sandbox) devem ser usados apenas em ambiente de desenvolvimento</li>
                        <li>Em produção, certifique-se de usar os tokens LIVE e desativar o "Modo de Teste"</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="flex items-center justify-between pt-4 border-t">
            <div class="text-sm text-gray-500">
                @if($safe2pay_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Gateway Ativo
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        Gateway Inativo
                    </span>
                @endif

                @if($safe2pay_test_mode)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Modo Teste
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                        Modo Produção
                    </span>
                @endif
            </div>

            <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Salvar Configurações Safe2Pay
            </button>
        </div>

        @error('safe2pay')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
    </form>
</div>
