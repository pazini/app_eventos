<div class="mb-10">
    <x-notifications position="top-right" />

    @if (session('success'))
        <div class="max-w-7xl mx-auto mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="max-w-7xl mx-auto mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="max-w-7xl mx-auto bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $standaloneEdit ? 'Editar Usuário' : 'Novo Usuário' }}</h2>
                <p class="text-sm text-gray-500">
                    Cliente: <span class="font-medium">{{ $customer->name_corporate ?? '--' }}</span>
                </p>
            </div>
            <x-button
                flat
                label="Voltar"
                href="{{ route('configuracoes-customer', ['customer_id' => $customerId, 'tab' => 'usuarios']) }}"
                as="a"
            />
        </div>

        <div class="space-y-6">
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4 border-b pb-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Dados do Usuário</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input
                            label="Nome"
                            wire:model.defer="name"
                            placeholder="Nome completo"
                            class="w-full"
                        />
                    </div>
                    <div>
                        <x-input
                            type="email"
                            label="E-mail"
                            wire:model.defer="email"
                            placeholder="email@exemplo.com"
                            class="w-full"
                        />
                    </div>
                    <div>
                        <x-native-select wire:model="role" label="Papel" class="w-full">
                            <option value="user">Usuário da Organização</option>
                            <option value="owner">Proprietário da Organização</option>
                            <option value="admin">Administrador do Sistema</option>
                            @if($standaloneEdit && $role === 'super-admin')
                                <option value="super-admin">Super Administrador</option>
                            @endif
                        </x-native-select>
                    </div>
                </div>
            </div>

            <div class="space-y-4 border-b pb-6">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Permissões</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <x-checkbox wire:model.defer="canEvents" label="Pode acessar Eventos" />
                    <x-checkbox wire:model.defer="canCampaigns" label="Pode acessar Campanhas" />
                    <x-checkbox wire:model.defer="canSubscriptions" label="Pode acessar Assinaturas" />
                </div>
            </div>

            @if (!$standaloneEdit)
                <div class="space-y-4 border-b pb-6">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Credenciais</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input
                                type="password"
                                label="Senha"
                                wire:model.defer="password"
                                placeholder="Mínimo 8 caracteres"
                                class="w-full"
                            />
                        </div>
                        <div>
                            <x-input
                                type="password"
                                label="Confirmar Senha"
                                wire:model.defer="passwordConfirmation"
                                placeholder="Confirme a senha"
                                class="w-full"
                            />
                        </div>
                    </div>
                </div>
            @endif

            @if ($standaloneEdit)
                <div class="space-y-4 border-b pb-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Alterar Senha</h3>
                        <x-button
                            flat
                            sm
                            label="{{ $showPasswordSection ? 'Ocultar' : 'Alterar Senha' }}"
                            wire:click="togglePasswordSection"
                        />
                    </div>

                    @if($showPasswordSection)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 rounded-lg p-4">
                            <div>
                                <x-input
                                    type="password"
                                    label="Nova Senha"
                                    wire:model.defer="newPassword"
                                    placeholder="Mínimo 8 caracteres"
                                    class="w-full"
                                />
                            </div>
                            <div>
                                <x-input
                                    type="password"
                                    label="Confirmar Nova Senha"
                                    wire:model.defer="newPasswordConfirmation"
                                    placeholder="Confirme a nova senha"
                                    class="w-full"
                                />
                            </div>
                            <div class="md:col-span-2">
                                <x-button
                                    primary
                                    sm
                                    label="Salvar Nova Senha"
                                    wire:click="updateUserPassword"
                                    spinner="updateUserPassword"
                                />
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <div class="flex items-center justify-between gap-3 pt-4 border-t">
                @if ($standaloneEdit)
                    <div>
                        @if ($confirmingDelete)
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-red-700 bg-red-50 border border-red-200 px-3 py-2 rounded">
                                    Confirmar remoção do usuário deste cliente?
                                </span>
                                <x-button flat sm label="Cancelar" wire:click="cancelDeleteConfirmation" />
                                <x-button negative sm label="Confirmar Remoção" wire:click="removeUser" />
                            </div>
                        @else
                            <x-button negative flat sm label="Remover Usuário" wire:click="startDeleteConfirmation" />
                        @endif
                    </div>
                @else
                    <div></div>
                @endif

                <div class="flex gap-2">
                    <x-button
                        flat
                        label="Cancelar"
                        href="{{ route('configuracoes-customer', ['customer_id' => $customerId, 'tab' => 'usuarios']) }}"
                        as="a"
                    />
                    @if($standaloneEdit)
                        <x-button
                            primary
                            label="Salvar Alterações"
                            wire:click="updateUser"
                            spinner="updateUser"
                        />
                    @else
                        <x-button
                            primary
                            label="Criar Usuário"
                            wire:click="createUser"
                            spinner="createUser"
                        />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
