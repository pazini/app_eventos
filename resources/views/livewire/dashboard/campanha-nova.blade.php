<div>
    {{-- Notificações WireUI --}}
    <x-notifications position="top-right" />

    {{-- Header com gradiente moderno --}}
    <div class="max-w-7xl mx-auto px-2 sm:px-2 lg:px-0">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        {{ $campaign_id ? 'Editar Campanha' : 'Nova Campanha' }}
                    </h1>
                    <p class="mt-2 text-blue-100 text-sm">
                        {{ $campaign_id ? 'Atualize os dados da campanha de arrecadação' : 'Preencha os dados para criar uma nova campanha de arrecadação' }}
                    </p>
                </div>
                <div>
                    @if($campaign_id)
                        <x-button outline white label="FECHAR" href="{{ route('dashboard-campanhas-detalhes-detalhes', ['campaign_id' => $campaign_id]) }}" class="hover:bg-white/20" />
                    @else
                        <x-button outline white label="CANCELAR" href="{{ route('dashboard-campanhas') }}" class="hover:bg-white/20" />
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="w-full max-w-7xl mx-auto">
        <x-jet-validation-errors no_loading="true" />
    </div>

    <div class="w-full max-w-7xl mx-auto mt-4">

        {{-- Formulário único --}}
        <div class="bg-white border rounded-sm shadow px-6 py-6">
            <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-4">

                @if (isAdmin())
                    {{-- ORGANIZADOR --}}
                    <div class="col-span-full">
                        <div class="text-sm font-bold text-gray-800 uppercase p-2 bg-gray-100 shadow">Organizador da Campanha</div>
                    </div>

                    <div class="col-span-full md:col-span-4">
                        <x-native-select label="Organizador da Campanha" wire:model="organizer_id">
                            <option value="">Selecione o organizador</option>
                            @php
                                $organizers = \App\Models\ModCampaign\CampaignOrganizer::where('customer_id', $customer->id)
                                    ->when($organization, function($q) use ($organization) {
                                        return $q->where('organization_id', $organization->id);
                                    })
                                    ->get();
                            @endphp
                            @foreach($organizers as $org)
                                <option value="{{ $org->id }}">{{ $org->organizer_name_full }}</option>
                            @endforeach
                        </x-native-select>
                        <p class="text-[10px] text-gray-500 mt-1">Responsável pela campanha</p>
                    </div>
                @endif

                {{-- DADOS BÁSICOS --}}
                <div class="col-span-full">
                    <div class="text-sm font-bold text-gray-800 uppercase p-2 bg-gray-100 shadow">Dados Básicos</div>
                </div>

                <div class="col-span-full md:col-span-6">
                    <x-input label="Nome da campanha *" wire:model.blur="name" placeholder="Ex: Black Friday 2025" />
                </div>

                {{-- <div class="col-span-full md:col-span-6" wire:key="slug-field-{{ $organizer_id }}">
                    <x-input label="Slug do Organizador (URL)" value="/{{ $customer_organization_slug }}/{{ $slug }}" readonly class="bg-gray-50" />
                </div> --}}

                {{-- <div class="col-span-full md:col-span-2">
                    <x-input label="Nome curto" wire:model.defer="name_short" placeholder="Ex: BF2025" />
                </div> --}}

                <div class="col-span-full md:col-span-3">
                    <x-input label="Data início *" type="date" wire:model.defer="datetime_start" />
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="Data fim (opcional)" type="date" wire:model.defer="datetime_finish" />
                </div>

                <div class="col-span-full md:col-span-4">
                    <x-native-select label="Status da campanha *" wire:model.defer="status">
                        <option value="draft">Rascunho (não visível)</option>
                        <option value="active">Ativa (visível ao público)</option>
                        <option value="active_direct">Ativa (Apenas Link Direto)</option>
                        <option value="paused">Pausada</option>
                    </x-native-select>
                </div>

                <div class="col-span-full my-2"><hr></div>

                {{-- VALORES E METAS --}}
                <div class="col-span-full">
                    <div class="text-sm font-bold text-gray-800 uppercase p-2 bg-gray-100 shadow">Valores e Metas</div>
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-label label="Valor mínimo (R$) *"  />
                    <div
                        class="mt-1 flex items-stretch rounded-none shadow border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent bg-white"
                        x-data="currencyField('{{ $amount_min_input ?? '' }}')"
                        x-init="init()"
                    >
                        <span class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 border-r border-gray-200 flex justify-center items-center">
                            R$
                        </span>
                        <input
                            type="text"
                            x-model="display"
                            x-on:input="handleInput($event.target.value)"
                            x-on:blur="updateModel()"
                            inputmode="decimal"
                            pattern="[0-9.,]*"
                            placeholder="10,00"
                            maxlength="18"
                            class="border-none rounded-lg flex-1 px-4 py-2 text-left text-base font-semibold text-gray-900 placeholder-gray-400 focus:outline-none"
                        />
                    </div>
                    <input type="hidden" wire:model.defer="amount_min_input" x-ref="hiddenFieldAmountMin" />
                    @error('amount_min')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Mínimo R$ 10,00</p>
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-label label="Meta de receita (R$)"  />
                    <div
                        class="mt-1 flex items-stretch rounded-none shadow border border-gray-300 overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent bg-white"
                        x-data="currencyField('{{ $goal_amount_input ?? '' }}', true)"
                        x-init="init()"
                    >
                        <span class="px-4 py-2 text-sm font-semibold text-gray-600 bg-gray-100 border-r border-gray-200 flex justify-center items-center">
                            R$
                        </span>
                        <input
                            type="text"
                            x-model="display"
                            x-on:input="handleInput($event.target.value)"
                            x-on:blur="updateModel()"
                            inputmode="decimal"
                            pattern="[0-9.,]*"
                            placeholder="10.000,00"
                            maxlength="18"
                            class="border-none rounded-lg flex-1 px-4 py-2 text-left text-base font-semibold text-gray-900 placeholder-gray-400 focus:outline-none"
                        />
                    </div>
                    <input type="hidden" wire:model.defer="goal_amount_input" x-ref="hiddenFieldGoalAmount" />
                    @error('goal_amount')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Opcional. Se informado, mínimo R$ 10,00.</p>
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="Meta de leads" type="number" wire:model.defer="goal_leads" placeholder="Ex: 500" hint="Opcional. Se informado, mínimo 1." />
                </div>

                <div class="col-span-full md:col-span-3">
                    <x-input label="Meta de conversões" type="number" wire:model.defer="goal_conversions" placeholder="Ex: 200" />
                </div>

                <div class="col-span-full my-2"><hr></div>

                {{-- EXIBIÇÃO PÚBLICA DAS METAS --}}
                <div class="col-span-full">
                    <div class="text-sm font-bold text-gray-800 uppercase p-2 bg-gray-100 shadow">Exibição Pública das Metas</div>
                </div>

                <div class="col-span-full">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <x-toggle
                                label="Mostrar meta de receita (R$)"
                                wire:model.defer="show_goal_amount"
                                hint="Exibe o valor da meta e quanto já foi arrecadado"
                            />
                            <x-toggle
                                label="Mostrar meta de leads"
                                wire:model.defer="show_goal_leads"
                                hint="Exibe a quantidade de leads esperados e alcançados"
                            />
                            <x-toggle
                                label="Mostrar meta de conversões"
                                wire:model.defer="show_goal_conversions"
                                hint="Exibe a quantidade de conversões esperadas e alcançadas"
                            />
                            <x-toggle
                                label="Mostrar barras de progresso"
                                wire:model.defer="show_progress"
                                hint="Exibe barras de progresso e percentuais de conclusão"
                            />
                        </div>
                    </div>
                    <p class="text-xs text-blue-800 mb-3">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Quais informações de metas e progresso serão exibidas na página da campanha.
                    </p>
                </div>

                <div class="col-span-full my-2"><hr></div>

                {{-- DESCRIÇÃO E SOBRE --}}
                <div class="col-span-full" wire:key="editor-description-container">
                    <label class="block text-base font-light uppercase text-black dark:text-gray-400 mb-1">DESCRIÇÃO</label>
                    <div id="toolbar-description" wire:ignore></div>
                    <div class="w-full border border-gray-300 bg-white" wire:ignore>
                        <div id="description_editor" style="min-height: 150px;">{!! $description !!}</div>
                    </div>
                    @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-full" wire:key="editor-about-container">
                    <label class="block text-base font-light uppercase text-black dark:text-gray-400 mb-1">SOBRE (DETALHES)</label>
                    <div id="toolbar-about" wire:ignore></div>
                    <div class="w-full border border-gray-300 bg-white" wire:ignore>
                        <div id="about_editor" style="min-height: 150px;">{!! $about !!}</div>
                    </div>
                </div>

                <div class="col-span-full my-2"><hr></div>

                {{-- FORMULÁRIO --}}
                <div class="col-span-full">
                    <div class="text-sm font-bold text-gray-800 uppercase p-2 bg-gray-100 shadow flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Perguntas do Quiz (Questionário)
                    </div>
                </div>

                <div class="col-span-full">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                        <p class="text-xs text-purple-800 flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Configure perguntas personalizadas que os doadores deverão responder antes de contribuir. Ideal para coletar informações específicas sobre a motivação, preferências ou dados adicionais.</span>
                        </p>
                    </div>

                    {{-- Lista de Perguntas --}}
                    @if(count($questions) > 0)
                        <div class="space-y-3 mb-4">
                            @foreach($questions as $index => $question)
                                <div class="bg-white border rounded-lg p-4 shadow-sm" x-data="{ editing: false }">

                                    {{-- Modo Visualização --}}
                                    <div x-show="!editing">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-1 rounded">
                                                        #{{ $index + 1 }}
                                                    </span>
                                                    <span class="text-xs text-gray-500 uppercase font-semibold">
                                                        {{
                                                            [
                                                                'text' => 'Texto Curto',
                                                                'textarea' => 'Texto Longo',
                                                                'select' => 'Lista de Seleção',
                                                                'radio' => 'Múltipla Escolha (Uma opção)',
                                                                'checkbox' => 'Caixas de Seleção (Múltiplas opções)',
                                                                'number' => 'Número',
                                                                'date' => 'Data'
                                                            ][$question['question_type']] ?? $question['question_type']
                                                        }}
                                                    </span>
                                                    @if($question['is_required'])
                                                        <span class="text-xs text-red-600 font-bold">OBRIGATÓRIA</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm font-semibold text-gray-900 mb-1">{{ $question['question_text'] }}</p>
                                                @if(!empty($question['help_text']))
                                                    <p class="text-xs text-gray-500">💡 {{ $question['help_text'] }}</p>
                                                @endif
                                                @if(!empty($question['placeholder']))
                                                    <p class="text-xs text-gray-500 italic">Placeholder: "{{ $question['placeholder'] }}"</p>
                                                @endif
                                                @if(!empty($question['question_options']) && in_array($question['question_type'], ['select', 'radio', 'checkbox']))
                                                    <div class="mt-2 text-xs text-gray-600">
                                                        <strong>Opções:</strong> {{ str_replace("\n", ', ', $question['question_options']) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex gap-1 ml-4">
                                                <button type="button" @click="editing = true" class="p-1 text-blue-500 hover:text-blue-700" title="Editar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                @if($index > 0)
                                                    <button type="button" wire:click="moveQuestionUp({{ $index }})" class="p-1 text-gray-500 hover:text-blue-600" title="Mover para cima">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                                @if($index < count($questions) - 1)
                                                    <button type="button" wire:click="moveQuestionDown({{ $index }})" class="p-1 text-gray-500 hover:text-blue-600" title="Mover para baixo">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                                <button type="button" wire:click="removeQuestion({{ $index }})" class="p-1 text-red-500 hover:text-red-700" title="Remover">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modo Edição --}}
                                    <div x-show="editing" style="display: none;" class="bg-blue-50 rounded p-3 border-2 border-blue-300">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div class="col-span-2">
                                                <x-input
                                                    label="Texto da Pergunta *"
                                                    wire:model.defer="questions.{{ $index }}.question_text"
                                                />
                                            </div>

                                            <div>
                                                <x-native-select label="Tipo de Resposta" wire:model.defer="questions.{{ $index }}.question_type">
                                                    <option value="text">Texto Curto</option>
                                                    <option value="textarea">Texto Longo</option>
                                                    <option value="select">Lista de Seleção</option>
                                                    <option value="radio">Múltipla Escolha (Uma opção)</option>
                                                    <option value="checkbox">Caixas de Seleção (Múltiplas opções)</option>
                                                    <option value="number">Número</option>
                                                    <option value="date">Data</option>
                                                </x-native-select>
                                            </div>

                                            <div>
                                                <x-input
                                                    label="Placeholder (opcional)"
                                                    wire:model.defer="questions.{{ $index }}.placeholder"
                                                />
                                            </div>

                                            <div class="col-span-2">
                                                <x-textarea
                                                    label="Opções (uma por linha)"
                                                    wire:model.defer="questions.{{ $index }}.question_options"
                                                    rows="3"
                                                />
                                                <p class="text-xs text-gray-500 mt-1">Somente para: Lista, Múltipla Escolha e Caixas de Seleção</p>
                                            </div>

                                            <div class="col-span-2">
                                                <x-input
                                                    label="Texto de Ajuda (opcional)"
                                                    wire:model.defer="questions.{{ $index }}.help_text"
                                                />
                                            </div>

                                            <div class="col-span-2">
                                                <x-toggle
                                                    label="Pergunta Obrigatória"
                                                    wire:model.defer="questions.{{ $index }}.is_required"
                                                />
                                            </div>

                                            <div class="col-span-2 flex gap-2">
                                                <x-button secondary @click="editing = false" icon="x" label="Cancelar" />
                                                <x-button primary @click="editing = false" icon="check" label="Salvar Alterações" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Formulário Nova Pergunta --}}
                    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="col-span-2">
                                <x-input
                                    label="Texto da Pergunta *"
                                    wire:model.defer="newQuestion.question_text"
                                    placeholder="Ex: Qual sua motivação para apoiar esta causa?"
                                />
                            </div>

                            <div>
                                <x-native-select label="Tipo de Resposta" wire:model.defer="newQuestion.question_type">
                                    <option value="text">Texto Curto</option>
                                    <option value="textarea">Texto Longo</option>
                                    <option value="select">Lista de Seleção</option>
                                    <option value="radio">Múltipla Escolha</option>
                                    <option value="checkbox">Caixas de Seleção</option>
                                    <option value="number">Número</option>
                                    <option value="date">Data</option>
                                </x-native-select>
                            </div>

                            <div>
                                <x-input
                                    label="Placeholder (opcional)"
                                    wire:model.defer="newQuestion.placeholder"
                                    placeholder="Texto de exemplo no campo"
                                />
                            </div>

                            <div class="col-span-2" x-show="['select', 'radio', 'checkbox'].includes($wire.newQuestion.question_type)">
                                <x-textarea
                                    label="Opções (uma por linha)"
                                    wire:model.defer="newQuestion.question_options"
                                    placeholder="Opção 1&#10;Opção 2&#10;Opção 3"
                                    rows="3"
                                />
                            </div>

                            <div class="col-span-2">
                                <x-input
                                    label="Texto de Ajuda (opcional)"
                                    wire:model.defer="newQuestion.help_text"
                                    placeholder="Dica ou explicação sobre a pergunta"
                                />
                            </div>

                            <div class="col-span-2">
                                <x-toggle
                                    label="Pergunta Obrigatória"
                                    wire:model.defer="newQuestion.is_required"
                                />
                            </div>

                            <div class="col-span-2">
                                <x-button primary wire:click="addQuestion" label="Salvar Pergunta" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-full my-2"><hr></div>

                {{-- Configurações de Privacidade --}}
                <div class="col-span-full">
                    <div class="bg-white border rounded-lg p-4 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Ativar Perguntas do Quiz
                                </label>
                                <p class="text-xs text-gray-500">Permite que os doadores respondam perguntas personalizadas antes de contribuir</p>
                            </div>
                            <x-toggle wire:model.defer="enable_questions" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Exigir CPF/CNPJ
                                </label>
                                <p class="text-xs text-gray-500">Torna obrigatório o preenchimento do documento do doador</p>
                            </div>
                            <x-toggle wire:model.defer="require_doc" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Permitir Doação Anônima
                                </label>
                                <p class="text-xs text-gray-500">Permite que o doador escolha fazer uma contribuição anônima</p>
                            </div>
                            <x-toggle wire:model.defer="allow_anonymous" />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Permitir Recorrência Mensal
                                </label>
                                <p class="text-xs text-gray-500">Libera doações recorrentes no cartão de crédito (sem parcelas)</p>
                            </div>
                            <x-toggle wire:model.defer="allow_recurring" />
                        </div>
                    </div>
                </div>

                <div class="col-span-full my-2"><hr></div>

                {{-- PERSONALIZAÇÃO VISUAL --}}
                <div class="col-span-full">
                    <div class="text-sm font-bold text-gray-800 uppercase p-2 bg-gray-100 shadow">Personalização Visual</div>
                </div>

                <div class="col-span-full md:col-span-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cor primária</label>
                    <div class="flex items-start gap-2">
                        <input type="color" wire:model.defer="color_primary" class="h-10 w-20 rounded border border-gray-300 cursor-pointer">
                        <x-input wire:model.defer="color_primary" placeholder="#3B82F6" class="flex-1" maxlength="7" hint="Cor principal (botões, destaques)" />
                    </div>
                </div>

                <div class="col-span-full md:col-span-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cor secundária</label>
                    <div class="flex items-start gap-2">
                        <input type="color" wire:model.defer="color_secondary" class="h-10 w-20 rounded border border-gray-300 cursor-pointer">
                        <x-input wire:model.defer="color_secondary" placeholder="#10B981" class="flex-1" maxlength="7" hint="Cor secundária" />
                    </div>
                </div>

                <div class="col-span-full my-2"><hr></div>

                {{-- IMAGENS --}}
                <div class="col-span-full">
                    <div class="text-sm font-bold text-gray-800 uppercase p-2 bg-gray-100 shadow">Imagens (opcional)</div>
                </div>

                <div class="col-span-full md:col-span-6">
                    <label class="text-xs font-semibold text-gray-700 uppercase mb-2">Banner (1200x400px)</label>
                    @if($preview_banner ?? false)
                        <div class="mt-2 relative">
                            <img src="{{ tenantAsset($preview_banner, true) }}" alt="Preview Banner" class="w-full h-40 object-cover rounded border">
                            <button type="button" wire:click="removerBanner" class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                Remover
                            </button>
                        </div>
                    @elseif($image_banner)
                        {{-- Preview temporário enquanto o upload está em andamento --}}
                        <div class="mt-2 relative">
                            <img src="{{ $image_banner->temporaryUrl() }}" alt="Preview temporário" class="w-full h-40 object-cover rounded border">
                            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                                <div class="text-white text-sm">Processando...</div>
                            </div>
                        </div>
                    @else
                        <input type="file" wire:model="image_banner" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        @error('image_banner')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                    <div wire:loading wire:target="image_banner" class="text-xs text-blue-600 mt-1">Carregando imagem...</div>
                </div>

                <div class="col-span-full md:col-span-6">
                    <label class="text-xs font-semibold text-gray-700 uppercase mb-2">Thumbnail (400x300px)</label>
                    @if($preview_thumb)
                        <div class="mt-2 relative">
                            <img src="{{ tenantAsset($preview_thumb, true) }}" alt="Preview Thumb" class="w-full h-40 object-cover rounded border">
                            <button type="button" wire:click="removerThumb" class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                Remover
                            </button>
                        </div>
                    @elseif($image_thumb)
                        {{-- Preview temporário enquanto o upload está em andamento --}}
                        <div class="mt-2 relative">
                            <img src="{{ $image_thumb->temporaryUrl() }}" alt="Preview temporário" class="w-full h-40 object-cover rounded border">
                            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                                <div class="text-white text-sm">Processando...</div>
                            </div>
                        </div>
                    @else
                        <input type="file" wire:model="image_thumb" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        @error('image_thumb')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    @endif
                    <div wire:loading wire:target="image_thumb" class="text-xs text-blue-600 mt-1">Carregando imagem...</div>
                </div>

                <div class="col-span-full my-2"><hr></div>

                {{-- BOTÕES DE AÇÃO --}}
                <div class="col-span-full flex justify-between items-center pt-4 border-t">
                    @if($campaign_id)
                        <div>
                            <button
                                type="button"
                                wire:click="openDeleteModal"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-red-700 bg-white border border-red-200 rounded-lg hover:bg-red-50 hover:border-red-300 transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                APAGAR CAMPANHA
                            </button>
                        </div>
                    @else
                        <div></div>
                    @endif
                    <div class="flex gap-3">
                        @if($campaign_id)
                            <x-button outline label="FECHAR" href="{{ route('dashboard-campanhas-detalhes-detalhes', ['campaign_id' => $campaign_id]) }}" class="hover:text-sky-500" />
                        @else
                            <x-button outline label="CANCELAR" href="{{ route('dashboard-campanhas') }}" class="hover:text-sky-500" />
                        @endif
                        <x-button primary label="{{ $campaign_id ? 'Atualizar Campanha' : 'Criar Campanha' }}" wire:click="salvar" class="uppercase" />
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Apagar --}}
    <x-modal.card blur wire:model="showDeleteModal" title="⚠️ Apagar Campanha">
        @if($this->ordersCount > 0)
            <div class="p-4 bg-red-50 border border-red-300 rounded">
                <p class="text-red-800 font-bold">⛔ Impossível apagar!</p>
                <p class="text-red-600 text-sm">Há {{ $this->ordersCount }} doação(ões) registrada(s).</p>
            </div>

            @if(\App\Http\Middleware\EnsureSuperAdmin::check())
                @php
                    $summary = $deleteSummary ?? [];
                    $webhooks = $summary['webhooks'] ?? 0;
                    $attempts = $summary['attempts'] ?? 0;
                    $payments = $summary['payments'] ?? 0;
                    $slips = $summary['slips'] ?? 0;
                    $subscriptions = $summary['subscriptions'] ?? 0;
                    $subscriptionCycles = $summary['subscription_cycles'] ?? 0;
                    $orders = $summary['orders'] ?? 0;
                    $orderAnswers = $summary['order_answers'] ?? 0;
                    $metrics = $summary['metrics'] ?? 0;
                    $questions = $summary['questions'] ?? 0;

                    $blockTentativas = $webhooks > 0;
                    $blockTransacoes = $webhooks > 0 || $attempts > 0;
                    $blockRecorrencias = $blockTransacoes || $payments > 0 || $slips > 0;
                    $blockDoacoes = $blockRecorrencias || $subscriptions > 0 || $subscriptionCycles > 0;
                    $blockCampanha = $blockDoacoes || $orders > 0 || $orderAnswers > 0;
                @endphp

                <div class="mt-4">
                    <div class="text-xs text-gray-500 mb-2">Apagamento em etapas (super-admin)</div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between gap-3 p-3 border rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Webhooks</div>
                                <div class="text-xs text-gray-500">{{ $webhooks }} registro(s)</div>
                            </div>
                            <button
                                type="button"
                                class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                wire:click="apagarWebhooks"
                                onclick="confirm('Apagar webhooks desta campanha?') || event.stopImmediatePropagation()"
                                @if($webhooks === 0) disabled @endif
                            >
                                Apagar
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 p-3 border rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Tentativas</div>
                                <div class="text-xs text-gray-500">{{ $attempts }} registro(s)</div>
                                @if($blockTentativas)
                                    <div class="text-xs text-red-600">Bloqueado: apague webhooks antes.</div>
                                @endif
                            </div>
                            <button
                                type="button"
                                class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                wire:click="apagarTentativas"
                                onclick="confirm('Apagar tentativas desta campanha?') || event.stopImmediatePropagation()"
                                @if($attempts === 0 || $blockTentativas) disabled @endif
                            >
                                Apagar
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 p-3 border rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Transações</div>
                                <div class="text-xs text-gray-500">{{ $payments }} pagamentos / {{ $slips }} slips</div>
                                @if($blockTransacoes)
                                    <div class="text-xs text-red-600">Bloqueado: apague webhooks e tentativas antes.</div>
                                @endif
                            </div>
                            <button
                                type="button"
                                class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                wire:click="apagarTransacoes"
                                onclick="confirm('Apagar pagamentos e slips desta campanha?') || event.stopImmediatePropagation()"
                                @if(($payments === 0 && $slips === 0) || $blockTransacoes) disabled @endif
                            >
                                Apagar
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 p-3 border rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Recorrências</div>
                                <div class="text-xs text-gray-500">{{ $subscriptions }} assinaturas / {{ $subscriptionCycles }} ciclos</div>
                                @if($blockRecorrencias)
                                    <div class="text-xs text-red-600">Bloqueado: apague transações antes.</div>
                                @endif
                            </div>
                            <button
                                type="button"
                                class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                wire:click="apagarRecorrencias"
                                onclick="confirm('Apagar recorrências desta campanha?') || event.stopImmediatePropagation()"
                                @if(($subscriptions === 0 && $subscriptionCycles === 0) || $blockRecorrencias) disabled @endif
                            >
                                Apagar
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 p-3 border rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Doações</div>
                                <div class="text-xs text-gray-500">{{ $orders }} pedidos / {{ $orderAnswers }} respostas</div>
                                @if($blockDoacoes)
                                    <div class="text-xs text-red-600">Bloqueado: apague transações e recorrências antes.</div>
                                @endif
                            </div>
                            <button
                                type="button"
                                class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                wire:click="apagarDoacoes"
                                onclick="confirm('Apagar doações desta campanha?') || event.stopImmediatePropagation()"
                                @if(($orders === 0 && $orderAnswers === 0) || $blockDoacoes) disabled @endif
                            >
                                Apagar
                            </button>
                        </div>

                        <div class="flex items-center justify-between gap-3 p-3 border rounded">
                            <div>
                                <div class="text-sm font-semibold text-gray-800">Campanha</div>
                                <div class="text-xs text-gray-500">{{ $metrics }} métricas / {{ $questions }} perguntas</div>
                                @if($blockCampanha)
                                    <div class="text-xs text-red-600">Bloqueado: finalize as etapas anteriores.</div>
                                @endif
                            </div>
                            <button
                                type="button"
                                class="shrink-0 inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed"
                                wire:click="apagarCampanhaFinal"
                                onclick="confirm('Apagar a campanha e dados restantes?') || event.stopImmediatePropagation()"
                                @if($blockCampanha) disabled @endif
                            >
                                Apagar
                            </button>
                        </div>
                    </div>

                    @error('deleteConfirmationStatus')
                        <div class="text-white bg-red-600 text-sm mt-3 p-2 rounded shadow">{{ $message }}</div>
                    @enderror
                </div>
            @endif
        @else
            <p class="text-gray-600 mb-4">Digite <strong class="text-red-600">apagar-campanha</strong> para confirmar.</p>

            <x-input wire:model="deleteConfirmation" placeholder="apagar-campanha" />
            @error('deleteConfirmation')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            @error('deleteConfirmationStatus')<div class="text-white bg-red-600 text-sm mt-2 p-2 rounded shadow">{{ $message }}</div>@enderror

            <x-slot name="footer">
                <div class="flex gap-3 justify-end">
                    <x-button flat label="Cancelar" wire:click="closeDeleteModal" />
                    <x-button red label="Apagar" wire:click="apagarCampanha" spinner="apagarCampanha" />
                </div>
            </x-slot>
        @endif
    </x-modal.card>

    {{-- Scripts CKEditor - Carregados uma única vez --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/decoupled-document/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initEditors();
        });

        // Reinicializa editores após Livewire fazer update
        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', (message, component) => {
                setTimeout(() => initEditors(), 100);
            });
        });

        function initEditors() {
            // Editor Descrição
            if (document.querySelector('#description_editor') && !document.querySelector('#description_editor').classList.contains('ck-editor__editable')) {
                DecoupledEditor
                    .create(document.querySelector('#description_editor'))
                    .then(editor => {
                        const toolbarContainer = document.querySelector('#toolbar-description');
                        toolbarContainer.innerHTML = ''; // Limpa toolbar anterior
                        toolbarContainer.appendChild(editor.ui.view.toolbar.element);

                        editor.model.document.on('change:data', () => {
                            @this.set('description', editor.getData());
                        });

                        console.log('✅ Editor Descrição inicializado');
                    })
                    .catch(error => console.error('❌ Erro editor descrição:', error));
            }

            // Editor Sobre
            if (document.querySelector('#about_editor') && !document.querySelector('#about_editor').classList.contains('ck-editor__editable')) {
                DecoupledEditor
                    .create(document.querySelector('#about_editor'))
                    .then(editor => {
                        const toolbarContainer = document.querySelector('#toolbar-about');
                        toolbarContainer.innerHTML = ''; // Limpa toolbar anterior
                        toolbarContainer.appendChild(editor.ui.view.toolbar.element);

                        editor.model.document.on('change:data', () => {
                            @this.set('about', editor.getData());
                        });

                        console.log('✅ Editor Sobre inicializado');
                    })
                    .catch(error => console.error('❌ Erro editor sobre:', error));
            }
        }
    </script>

    {{-- Script para mostrar notificação de erros --}}
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.failed', (message, component) => {
                // Verifica se há erros de validação
                if (component.errors && Object.keys(component.errors).length > 0) {
                    const errorCount = Object.keys(component.errors).length;
                    const errorMessage = 'Um ou mais campos precisam ser corrigidos..';

                    window.$wireui.notify({
                        title: 'Atenção!',
                        description: errorMessage,
                        icon: 'error'
                    });
                }
            });
        });

        // Para Livewire v3 (alternativa)
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('validationErrors', (errors) => {
                const errorCount = Object.keys(errors).length;
                const errorMessage = 'Um ou mais campos precisam ser corrigidos..';

                window.$wireui.notify({
                    title: 'Atenção!',
                    description: errorMessage,
                    icon: 'error'
                });
            });
        });
    </script>

    <style>.ck-file-dialog-button {display: none;}</style>

    {{-- Script para campos de moeda formatados --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('currencyField', (initialValue, allowEmpty = false) => ({
                display: '',
                rawValue: '',
                allowEmpty: !!allowEmpty,
                init() {
                    // Se initialValue já está formatado (ex: "10,00" ou vem do banco como centavos)
                    const hasInitialValue = initialValue !== null && initialValue !== undefined && String(initialValue).trim() !== '';
                    if (hasInitialValue) {
                        if (typeof initialValue === 'string' && initialValue.includes(',')) {
                            // Já está formatado como string "10,00", converte para centavos
                            const digits = initialValue.replace(/\D/g, '');
                            this.rawValue = digits;
                            this.display = this.formatFromCents(digits);
                        } else {
                            // É um número em centavos (int do banco), formata para exibição
                            this.rawValue = initialValue.toString();
                            this.display = this.formatFromCents(initialValue.toString());
                        }
                    } else {
                        this.display = this.allowEmpty ? '' : '0,00';
                        this.rawValue = this.allowEmpty ? '' : '0';
                    }
                },
                handleInput(value) {
                    if (this.allowEmpty && (!value || value.trim() === '')) {
                        this.rawValue = '';
                        this.display = '';
                        return;
                    }

                    // Processa a entrada do usuário
                    // Se tem vírgula ou ponto, assume formato decimal (ex: "20,50" ou "20.50")
                    // Se não tem, assume que são reais completos (ex: "20" = R$ 20,00)
                    const hasDecimal = /[,.]/.test(value);
                    let digits = value.replace(/\D/g, '');

                    if (!hasDecimal && digits.length > 0) {
                        // Não tem vírgula/ponto, assume reais completos - multiplica por 100
                        // Ex: "20" -> 2000 centavos
                        digits = (parseInt(digits, 10) * 100).toString();
                    }
                    // Se tem vírgula/ponto, os dígitos já estão corretos (ex: "20,50" -> "2050")

                    if (this.allowEmpty && digits.length === 0) {
                        this.rawValue = '';
                        this.display = '';
                        return;
                    }

                    this.rawValue = digits || '0';
                    this.display = this.formatFromCents(this.rawValue);
                },
                updateModel() {
                    // Atualiza o campo hidden apenas quando o campo perde o foco
                    // O rawValue já está em centavos (apenas números)
                    const formattedValue = this.formatForInput(this.rawValue);

                    // Encontra o campo hidden no mesmo container
                    const hiddenInput = this.$el.closest('.col-span-full').querySelector('input[type="hidden"]');
                    if (hiddenInput) {
                        hiddenInput.value = formattedValue;
                        // Dispara evento para o Livewire atualizar (mas sem reload)
                        hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                },
                formatForInput(cents) {
                    // Converte centavos para formato de entrada (ex: 2000 -> "20,00")
                    if (cents === '' || cents === null || cents === undefined) return '';
                    if (cents === '0') return '0,00';
                    const number = (parseInt(cents, 10) / 100).toFixed(2);
                    return number.replace('.', ',');
                },
                formatFromCents(cents) {
                    // Formata centavos para exibição (ex: "2000" -> "20,00" ou "123456" -> "1.234,56")
                    if (cents === '' || cents === null || cents === undefined) return '';
                    if (cents === '0') return '0,00';
                    const number = (parseInt(cents, 10) / 100).toFixed(2);
                    const [intPart, decimalPart] = number.split('.');
                    const formattedInt = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    return `${formattedInt},${decimalPart}`;
                },
            }));
        });
    </script>
</div>
