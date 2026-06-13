<!-- Step 1: Dados da Empresa/Organização -->
<div class="space-y-6">
    <div class="text-center mb-6">
        <i class="fas fa-building text-4xl text-blue-500 mb-4"></i>
        <p class="text-gray-600">
            Vamos começar com as informações básicas da empresa ou organização.
        </p>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Informações Principais -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                <i class="fas fa-info-circle mr-2"></i>Informações Principais
            </h3>

            <!-- Nome da Empresa -->
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome da Empresa/Organização *
                </label>
                <input type="text" id="company_name" wire:model.defer="company_name"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('company_name') border-red-500 @enderror"
                       placeholder="Ex: Instituto de Eventos, Empresa ABC Ltda">
                @error('company_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Nome que aparecerá em campanhas, eventos e comunicações.
                </p>
            </div>

            <!-- Tipo de Organização -->
            <div>
                <label for="company_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Organização *
                </label>
                <select wire:model.defer="company_type" id="company_type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('company_type') border-red-500 @enderror">
                    <option value="empresa">Empresa</option>
                    <option value="ong">ONG / Organização sem fins lucrativos</option>
                    <option value="pessoa_fisica">Pessoa Física</option>
                </select>
                @error('company_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Documento -->
            <div>
                <label for="document" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $company_type == 'pessoa_fisica' ? 'CPF *' : 'CNPJ *' }}
                </label>
                <input type="text" id="document" wire:model.defer="document"
                       oninput="formatDocument(this)"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('document') border-red-500 @enderror"
                       placeholder="{{ $company_type == 'pessoa_fisica' ? '000.000.000-00' : '00.000.000/0000-00' }}">
                @error('document')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descrição -->
            <div>
                <label for="company_description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descrição (Opcional)
                </label>
                <textarea id="company_description" wire:model.defer="company_description" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('company_description') border-red-500 @enderror"
                          placeholder="Breve descrição sobre a empresa/organização..."></textarea>
                @error('company_description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Contato e Endereço -->
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                <i class="fas fa-map-marker-alt mr-2"></i>Contato e Localização
            </h3>

            <!-- Telefone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Telefone *
                </label>
                <input type="tel" id="phone" wire:model.defer="phone"
                       oninput="formatPhone(this)"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('phone') border-red-500 @enderror"
                       placeholder="(11) 99999-9999">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Website -->
            <div>
                <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                    Website (Opcional)
                </label>
                <input type="url" id="website" wire:model.defer="website"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('website') border-red-500 @enderror"
                       placeholder="https://www.exemplo.com.br">
                @error('website')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Endereço -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Endereço (Opcional)
                </label>
                <input type="text" id="address" wire:model.defer="address"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('address') border-red-500 @enderror"
                       placeholder="Rua, Avenida, número, complemento...">
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Cidade e Estado -->
            <div class="grid md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        Cidade *
                    </label>
                    <input type="text" id="city" wire:model.defer="city"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('city') border-red-500 @enderror"
                           placeholder="Ex: São Paulo">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                        UF *
                    </label>
                    <select wire:model.defer="state" id="state"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('state') border-red-500 @enderror">
                        <option value="">UF</option>
                        <option value="AC">AC</option>
                        <option value="AL">AL</option>
                        <option value="AP">AP</option>
                        <option value="AM">AM</option>
                        <option value="BA">BA</option>
                        <option value="CE">CE</option>
                        <option value="DF">DF</option>
                        <option value="ES">ES</option>
                        <option value="GO">GO</option>
                        <option value="MA">MA</option>
                        <option value="MT">MT</option>
                        <option value="MS">MS</option>
                        <option value="MG">MG</option>
                        <option value="PA">PA</option>
                        <option value="PB">PB</option>
                        <option value="PR">PR</option>
                        <option value="PE">PE</option>
                        <option value="PI">PI</option>
                        <option value="RJ">RJ</option>
                        <option value="RN">RN</option>
                        <option value="RS">RS</option>
                        <option value="RO">RO</option>
                        <option value="RR">RR</option>
                        <option value="SC">SC</option>
                        <option value="SP">SP</option>
                        <option value="SE">SE</option>
                        <option value="TO">TO</option>
                    </select>
                    @error('state')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- CEP -->
            <div>
                <label for="zipcode" class="block text-sm font-medium text-gray-700 mb-2">
                    CEP (Opcional)
                </label>
                <input type="text" id="zipcode" wire:model.defer="zipcode"
                       oninput="formatZipcode(this)"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('zipcode') border-red-500 @enderror"
                       placeholder="00000-000">
                @error('zipcode')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Preview Card -->
    @if ($company_name)
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200 mt-8">
            <h4 class="font-semibold text-blue-800 mb-4 flex items-center">
                <i class="fas fa-eye text-blue-600 mr-2"></i>
                Preview do Cliente
            </h4>

            <div class="bg-white rounded-lg p-4 border">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-xl font-bold">
                            {{ strtoupper(substr($company_name, 0, 2)) }}
                        </div>
                        <div>
                            <h5 class="text-xl font-semibold text-gray-900">{{ $company_name }}</h5>
                            <div class="flex items-center text-sm text-gray-600 mt-1">
                                @if($company_type == 'empresa')
                                    <i class="fas fa-building text-blue-500 mr-1"></i>Empresa
                                @elseif($company_type == 'ong')
                                    <i class="fas fa-heart text-red-500 mr-1"></i>ONG
                                @else
                                    <i class="fas fa-user text-green-500 mr-1"></i>Pessoa Física
                                @endif
                                @if($document)
                                    <span class="ml-3">{{ $company_type == 'pessoa_fisica' ? 'CPF' : 'CNPJ' }}: {{ $document }}</span>
                                @endif
                            </div>
                            @if ($company_description)
                                <p class="text-sm text-gray-600 mt-2">{{ Str::limit($company_description, 100) }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right text-sm text-gray-500">
                        @if($phone)
                            <p><i class="fas fa-phone mr-1"></i>{{ $phone }}</p>
                        @endif
                        @if($city && $state)
                            <p><i class="fas fa-map-marker-alt mr-1"></i>{{ $city }}, {{ $state }}</p>
                        @endif
                        @if($website)
                            <p><i class="fas fa-globe mr-1"></i>{{ $website }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Informações importantes -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-lightbulb text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-yellow-800">Dicas importantes:</h4>
                <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                    <li>Todos os dados podem ser editados posteriormente</li>
                    <li>O documento (CPF/CNPJ) é usado para validações fiscais</li>
                    <li>O telefone será usado para contatos importantes</li>
                    <li>A cidade e estado ajudam em relatórios regionais</li>
                </ul>
            </div>
        </div>
    </div>
</div>
