<!-- Step 7: Personalização de Textos -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-edit text-4xl text-indigo-500 mb-4"></i>
        <p class="text-gray-600">
            Personalize textos, mensagens e labels da aplicação para criar uma experiência única.
        </p>
    </div>

    <!-- Seletor de Categoria -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-list mr-2"></i>Categorias de Textos
        </h3>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <!-- Interface -->
            <button type="button" wire:click="$set('text_category', 'interface')"
                    :class="$wire.text_category === 'interface' ? 'bg-indigo-100 border-indigo-500 text-indigo-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'"
                    class="p-4 border-2 rounded-lg text-center transition-all">
                <i class="fas fa-desktop text-2xl mb-2"></i>
                <p class="font-medium">Interface</p>
                <p class="text-sm opacity-75">Botões e menus</p>
            </button>

            <!-- Mensagens -->
            <button type="button" wire:click="$set('text_category', 'messages')"
                    :class="$wire.text_category === 'messages' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'"
                    class="p-4 border-2 rounded-lg text-center transition-all">
                <i class="fas fa-comment-alt text-2xl mb-2"></i>
                <p class="font-medium">Mensagens</p>
                <p class="text-sm opacity-75">Alertas e avisos</p>
            </button>

            <!-- E-mails -->
            <button type="button" wire:click="$set('text_category', 'emails')"
                    :class="$wire.text_category === 'emails' ? 'bg-blue-100 border-blue-500 text-blue-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'"
                    class="p-4 border-2 rounded-lg text-center transition-all">
                <i class="fas fa-envelope text-2xl mb-2"></i>
                <p class="font-medium">E-mails</p>
                <p class="text-sm opacity-75">Templates</p>
            </button>

            <!-- Campanhas -->
            <button type="button" wire:click="$set('text_category', 'campaigns')"
                    :class="$wire.text_category === 'campaigns' ? 'bg-purple-100 border-purple-500 text-purple-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'"
                    class="p-4 border-2 rounded-lg text-center transition-all">
                <i class="fas fa-bullhorn text-2xl mb-2"></i>
                <p class="font-medium">Campanhas</p>
                <p class="text-sm opacity-75">Arrecadações</p>
            </button>

            <!-- Eventos -->
            <button type="button" wire:click="$set('text_category', 'events')"
                    :class="$wire.text_category === 'events' ? 'bg-orange-100 border-orange-500 text-orange-700' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50'"
                    class="p-4 border-2 rounded-lg text-center transition-all">
                <i class="fas fa-calendar-alt text-2xl mb-2"></i>
                <p class="font-medium">Eventos</p>
                <p class="text-sm opacity-75">Ingressos</p>
            </button>
        </div>
    </div>

    <!-- Editor de Textos por Categoria -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-edit mr-2"></i>Editar Textos -
                <span class="capitalize">{{ $text_category ?? 'interface' }}</span>
            </h3>

            <div class="flex items-center space-x-3">
                <!-- Seletor de Idioma -->
                <select wire:model.defer="text_locale"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="pt">🇧🇷 Português</option>
                    <option value="en">🇺🇸 English</option>
                    <option value="es">🇪🇸 Español</option>
                </select>

                <!-- Resetar para Padrão -->
                <button type="button" wire:click="resetCategoryTexts"
                        class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Resetar
                </button>
            </div>
        </div>

        <!-- Textos de Interface -->
        <div x-show="$wire.text_category === 'interface'" x-transition class="space-y-4">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="text_welcome" class="block text-sm font-medium text-gray-700 mb-2">
                        Mensagem de Boas-vindas
                    </label>
                    <textarea id="text_welcome" wire:model.defer="custom_texts.ui.welcome" rows="2"
                              placeholder="Bem-vindo ao nosso sistema!"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label for="text_login" class="block text-sm font-medium text-gray-700 mb-2">
                        Botão de Login
                    </label>
                    <input type="text" id="text_login" wire:model.defer="custom_texts.ui.login"
                           placeholder="Entrar"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="text_logout" class="block text-sm font-medium text-gray-700 mb-2">
                        Botão de Sair
                    </label>
                    <input type="text" id="text_logout" wire:model.defer="custom_texts.ui.logout"
                           placeholder="Sair"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="text_save" class="block text-sm font-medium text-gray-700 mb-2">
                        Botão Salvar
                    </label>
                    <input type="text" id="text_save" wire:model.defer="custom_texts.ui.save"
                           placeholder="Salvar"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="text_cancel" class="block text-sm font-medium text-gray-700 mb-2">
                        Botão Cancelar
                    </label>
                    <input type="text" id="text_cancel" wire:model.defer="custom_texts.ui.cancel"
                           placeholder="Cancelar"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="text_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                        Botão Confirmar
                    </label>
                    <input type="text" id="text_confirm" wire:model.defer="custom_texts.ui.confirm"
                           placeholder="Confirmar"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <!-- Textos de Mensagens -->
        <div x-show="$wire.text_category === 'messages'" x-transition class="space-y-4">
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="msg_success_saved" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check text-green-500 mr-1"></i>Item Salvo com Sucesso
                    </label>
                    <input type="text" id="msg_success_saved" wire:model.defer="custom_texts.messages.success_saved"
                           placeholder="Dados salvos com sucesso!"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="msg_success_created" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-plus text-green-500 mr-1"></i>Item Criado com Sucesso
                    </label>
                    <input type="text" id="msg_success_created" wire:model.defer="custom_texts.messages.success_created"
                           placeholder="Item criado com sucesso!"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="msg_error_general" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i>Erro Geral
                    </label>
                    <input type="text" id="msg_error_general" wire:model.defer="custom_texts.messages.error_general"
                           placeholder="Ocorreu um erro. Tente novamente."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="msg_error_payment" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-credit-card text-red-500 mr-1"></i>Erro de Pagamento
                    </label>
                    <input type="text" id="msg_error_payment" wire:model.defer="custom_texts.messages.error_payment"
                           placeholder="Erro no processamento do pagamento."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <!-- Textos de E-mails -->
        <div x-show="$wire.text_category === 'emails'" x-transition class="space-y-4">
            <div class="space-y-6">
                <div>
                    <label for="email_greeting" class="block text-sm font-medium text-gray-700 mb-2">
                        Saudação Inicial
                    </label>
                    <input type="text" id="email_greeting" wire:model.defer="custom_texts.emails.greeting"
                           placeholder="Olá,"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="email_signature" class="block text-sm font-medium text-gray-700 mb-2">
                        Assinatura dos E-mails
                    </label>
                    <textarea id="email_signature" wire:model.defer="custom_texts.emails.signature" rows="3"
                              placeholder="Atenciosamente,&#10;Equipe {{ $company_name }}"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label for="email_payment_success" class="block text-sm font-medium text-gray-700 mb-2">
                        Assunto - Pagamento Aprovado
                    </label>
                    <input type="text" id="email_payment_success" wire:model.defer="custom_texts.emails.payment_success_subject"
                           placeholder="Pagamento aprovado com sucesso!"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="email_welcome_subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Assunto - E-mail de Boas-vindas
                    </label>
                    <input type="text" id="email_welcome_subject" wire:model.defer="custom_texts.emails.welcome_subject"
                           placeholder="Bem-vindo ao nosso sistema!"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <!-- Textos de Campanhas -->
        <div x-show="$wire.text_category === 'campaigns'" x-transition class="space-y-4">
            <div class="space-y-6">
                <div>
                    <label for="campaign_thank_you" class="block text-sm font-medium text-gray-700 mb-2">
                        Mensagem de Agradecimento
                    </label>
                    <textarea id="campaign_thank_you" wire:model.defer="custom_texts.campaigns.thank_you" rows="3"
                              placeholder="Obrigado por apoiar nossa causa!"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label for="campaign_share_message" class="block text-sm font-medium text-gray-700 mb-2">
                        Mensagem para Compartilhamento
                    </label>
                    <textarea id="campaign_share_message" wire:model.defer="custom_texts.campaigns.share_message" rows="3"
                              placeholder="Compartilhe esta campanha e ajude nossa causa!"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label for="campaign_goal_reached" class="block text-sm font-medium text-gray-700 mb-2">
                        Meta Alcançada
                    </label>
                    <input type="text" id="campaign_goal_reached" wire:model.defer="custom_texts.campaigns.goal_reached"
                           placeholder="Meta alcançada! Obrigado a todos!"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        <!-- Textos de Eventos -->
        <div x-show="$wire.text_category === 'events'" x-transition class="space-y-4">
            <div class="space-y-6">
                <div>
                    <label for="event_ticket_confirmed" class="block text-sm font-medium text-gray-700 mb-2">
                        Ingresso Confirmado
                    </label>
                    <textarea id="event_ticket_confirmed" wire:model.defer="custom_texts.events.ticket_confirmed" rows="2"
                              placeholder="Seu ingresso foi confirmado! Nos vemos lá!"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label for="event_see_you_there" class="block text-sm font-medium text-gray-700 mb-2">
                        Despedida do E-mail
                    </label>
                    <input type="text" id="event_see_you_there" wire:model.defer="custom_texts.events.see_you_there"
                           placeholder="Nos vemos no evento!"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="event_sold_out" class="block text-sm font-medium text-gray-700 mb-2">
                        Evento Esgotado
                    </label>
                    <input type="text" id="event_sold_out" wire:model.defer="custom_texts.events.sold_out"
                           placeholder="Ingressos esgotados!"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Tema e Personalidade -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-6">
            <i class="fas fa-palette mr-2"></i>Tom e Personalidade
        </h3>

        <div class="grid md:grid-cols-3 gap-6">
            <!-- Tema Profissional -->
            <label class="relative">
                <input type="radio" wire:model.defer="text_theme" value="professional" class="sr-only peer">
                <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                    <div class="text-center space-y-3">
                        <div class="w-12 h-12 mx-auto bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Profissional</h4>
                            <p class="text-sm text-gray-600">Tom formal e corporativo</p>
                        </div>
                    </div>
                </div>
            </label>

            <!-- Tema Amigável -->
            <label class="relative">
                <input type="radio" wire:model.defer="text_theme" value="friendly" class="sr-only peer" checked>
                <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-300 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                    <div class="text-center space-y-3">
                        <div class="w-12 h-12 mx-auto bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-smile text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Amigável</h4>
                            <p class="text-sm text-gray-600">Tom caloroso e próximo</p>
                        </div>
                    </div>
                </div>
            </label>

            <!-- Tema Casual -->
            <label class="relative">
                <input type="radio" wire:model.defer="text_theme" value="casual" class="sr-only peer">
                <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all">
                    <div class="text-center space-y-3">
                        <div class="w-12 h-12 mx-auto bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heart text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">Casual</h4>
                            <p class="text-sm text-gray-600">Tom descontraído e divertido</p>
                        </div>
                    </div>
                </div>
            </label>
        </div>

        <!-- Aplicar Tema -->
        <div class="mt-6 flex justify-center">
            <button type="button" wire:click="applyTextTheme"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-magic mr-2"></i>Aplicar Tema {{ ucfirst($text_theme ?? 'friendly') }}
            </button>
        </div>
    </div>

    <!-- Visualizador de Preview -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-indigo-800 mb-4">
            <i class="fas fa-eye mr-2"></i>Preview dos Textos
        </h3>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Preview Interface -->
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <h4 class="font-medium text-gray-900 mb-3">Interface:</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Boas-vindas:</span>
                        <span class="font-medium">{{ $custom_texts['ui']['welcome'] ?? 'Bem-vindo!' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Login:</span>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">
                            {{ $custom_texts['ui']['login'] ?? 'Entrar' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Salvar:</span>
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                            {{ $custom_texts['ui']['save'] ?? 'Salvar' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Preview E-mail -->
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <h4 class="font-medium text-gray-900 mb-3">E-mail:</h4>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-600">Assunto:</span>
                        <p class="font-medium">{{ $custom_texts['emails']['welcome_subject'] ?? 'Bem-vindo!' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Saudação:</span>
                        <p>{{ $custom_texts['emails']['greeting'] ?? 'Olá,' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Assinatura:</span>
                        <p class="text-xs bg-gray-100 p-2 rounded">
                            {!! nl2br($custom_texts['emails']['signature'] ?? "Atenciosamente,\nEquipe") !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações sobre personalização -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800">Sobre a personalização de textos:</h4>
                <ul class="mt-2 text-sm text-blue-700 list-disc list-inside space-y-1">
                    <li>Todos os textos podem ser editados posteriormente</li>
                    <li>Suporte a múltiplos idiomas (português, inglês, espanhol)</li>
                    <li>Os temas aplicam conjuntos pré-definidos de textos</li>
                    <li>Mudanças são aplicadas em toda a aplicação instantaneamente</li>
                </ul>
            </div>
        </div>
    </div>
</div>
