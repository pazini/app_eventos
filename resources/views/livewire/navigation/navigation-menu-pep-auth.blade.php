<div>

    {{-- NAVIGATION MENU PEP --}}
    {{-- <div class="max-w-7xl mx-auto py-2 md:py-2 px-2 md:px-10 bg-white z-50 border shadow my-4"> --}}
    <div class="max-w-7xl mx-auto py-4 md:py-4 z-50">

        <div class="flex justify-between items-center min-h-14">

            <!-- Logo 2 -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}">
                    {{-- White Label: Logo dinâmico --}}
                    <img src="{{ appLogo(true) }}" alt="{{ appName() }}" class="h-10">
                </a>
            </div>

            <!-- Menu / Usuário -->
            <div class="flex items-center gap-4">
                @auth
                    <style>
                        body {
                            margin: 0;
                            font-family: Arial, sans-serif;
                        }

                        /* Estilos básicos do Offcanvas */
                        .offcanvas {
                            position: fixed;
                            top: 0;
                            right: 0;
                            min-width: 300px;
                            max-width: 50%;
                            height: 100%;
                            background: white;
                            visibility: hidden;
                            opacity: 0;
                            z-index: 1000;
                            transition: visibility 0.3s, opacity 0.3s, transform 0.3s;
                            transform: translateX(100%);
                            /* Inicia fora da tela */
                            box-shadow: -2px 0px 10px rgba(0, 0, 0, 0.1);
                        }

                        .offcanvas.active {
                            visibility: visible;
                            opacity: 1;
                            transform: translateX(0);
                            /* Entra na tela */
                        }

                        .offcanvas-content {
                            padding: 20px;
                            height: 100%;
                            overflow-y: auto;
                        }

                        .offcanvas-header {
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            border-bottom: 1px solid #ddd;
                        }

                        .offcanvas-body {
                            margin-top: 10px;
                        }

                        .close-btn {
                            cursor: pointer;
                            font-size: 1.5rem;
                            border: none;
                            background: none;
                        }

                        /* Fundo com Blur */
                        .overlay {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: rgba(0, 0, 0, 0.5);
                            visibility: hidden;
                            opacity: 0;
                            z-index: 999;
                            transition: visibility 0.3s, opacity 0.3s, filter 0.3s;
                            backdrop-filter: blur(5px);
                            /* Aplica o blur */
                        }

                        .overlay.active {
                            visibility: visible;
                            opacity: 1;
                        }
                    </style>

                    <!-- Botão para abrir o Offcanvas -->
                    <button id="openOffcanvas">
                        <div class="shadow border111 border-gray-300 rounded-full py-1 px-1 bg-white">
                            <div class="flex items-center gap-x-1 text-gray-500">
                                <div class="flex justify-center pl-2 pr-1">
                                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                                        width="18" height="18">
                                        <path d="M2.5 10h15m-15-5h15m-15 10h15" stroke="#717680" stroke-width="1"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                                <div class="rounded-full bg-gray-100 p-2 shadow">
                                    <x-icon name="user" class="h-5" />
                                </div>
                            </div>
                        </div>
                    </button>
                    <!-- Botão para abrir o Offcanvas fim -->

                    <!-- Fundo com Blur -->
                    <div id="overlay" class="overlay"></div>

                    <!-- Offcanvas -->
                    <div id="offcanvas" class="offcanvas">
                        <div class="offcanvas-content">
                            <div class="flex justify-between items-center gap-2">
                                <div>
                                    <div class="text-lg font-bold truncate capitalize" title="{{ Auth::user()->name }}">
                                        {{ Auth::user()->name }}</div>
                                    <div class="-mt-1 text-base truncate lowercase" title="{{ Auth::user()->email }}">
                                        {{ Auth::user()->email }}</div>
                                </div>
                                <button id="closeOffcanvas" class="close-btn">&times;</button>
                            </div>

                            <div class="py-4">
                                <hr>
                            </div>

                            <div class="flex flex-col gap-4">

                                @php
                                    $user = Auth::user();
                                    $customer = sessionCustomer();

                                    $canEvents = false;
                                    $canCampaigns = false;

                                    if ($user) {
                                        // Admin global enxerga sempre os módulos principais
                                        if (\App\Services\ModuleAccessService::userIsAppAdmin($user)) {
                                            $canEvents = true;
                                            $canCampaigns = true;
                                        } elseif ($customer) {
                                            $canEvents = \App\Services\ModuleAccessService::userCanAccessEvents(
                                                $user,
                                                $customer,
                                            );
                                            $canCampaigns = \App\Services\ModuleAccessService::userCanAccessCampaigns(
                                                $user,
                                                $customer,
                                            );
                                        }
                                    }
                                @endphp

                                {{-- MÓDULOS DISPONÍVEIS PARA O USUÁRIO --}}
                                @if (isAdmin() || $canEvents)
                                    <div class="space-y-3 border-b pb-6 mb-1">
                                        <div class="text-[14px] font-light uppercase tracking-wide text-gray-500">
                                            Módulo Eventos
                                        </div>

                                        <div>
                                            <a href="{{ route('dashboard-eventos') }}"
                                                class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                <x-icon name="calendar" class="h-6" />
                                                <span class="">Painel</span>
                                            </a>
                                        </div>

                                        {{-- ORGANIZADORES --}}
                                        @if (isAdmin() || isOwner())
                                            <div>
                                                <a href="{{ route('eventos-organizadores') }}"
                                                    class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                    <x-icon name="users" class="h-6" />
                                                    <span class="">Organizadores</span>
                                                </a>
                                            </div>
                                        @endif

                                        @if (isAdmin())
                                            <div>
                                                <a href="{{ route('ultimas-vendas') }}"
                                                    class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                    <x-icon name="cash" class="h-6" />
                                                    <span class="">Últimas Vendas</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if (isAdmin() || $canCampaigns)
                                    <div class="space-y-3 border-b pb-6 mb-1">
                                        <div class="text-[14px] font-light uppercase tracking-wide text-gray-500">
                                            Módulo Campanhas
                                        </div>

                                        <div>
                                            <a href="{{ route('dashboard-campanhas') }}"
                                                class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                <x-icon name="view-grid" class="h-6" />
                                                <span class="">Painel</span>
                                            </a>
                                        </div>

                                        {{-- ORGANIZADORES --}}
                                        @if (isAdmin() || isOwner())
                                            <div>
                                                <a href="{{ route('campanhas-organizadores') }}"
                                                    class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                    <x-icon name="users" class="h-6" />
                                                    <span class="">Organizadores</span>
                                                </a>
                                            </div>
                                        @endif

                                        @if (isAdmin())
                                            <div>
                                                <a href="{{ route('ultimas-vendas-campanhas') }}"
                                                    class="w-full flex justify-start items-center gap-2 border border-gray-100 bg-gray-100 hover:bg-gray-50 text-gray-500 hover:text-blue-500 rounded-md shadow-md p-2">
                                                    <x-icon name="cash" class="h-6" />
                                                    <span class="">Últimas Adesões</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if (isAdmin())
                                    <div><x-button rounded blue label="CONFIGURAÇÕES" href="{{ route('configuracoes') }}"
                                            class="w-full" /></div>

                                    <div><x-button rounded blue label="FATURAS"
                                            href="{{ route('plataforma-faturamento') }}" class="w-full" /></div>

                                    <div>
                                        <hr>
                                    </div>
                                @endif

                                {{-- SUPER ADMIN --}}
                                @if (App\Http\Middleware\EnsureSuperAdmin::check())
                                    <div><x-button rounded purple label="SUPER-ADMINISTRADOR"
                                            href="{{ route('super-administrador.dashboard') }}" class="w-full" /></div>
                                    <div>
                                        <hr>
                                    </div>
                                @endif

                                {{-- MEU PERFIL --}}
                                <div><x-button rounded blue label="PERFIL" href="{{ route('dashboard-user-profile') }}"
                                        class="w-full" /></div>

                                <div>
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <x-button rounded red outline label="SAIR" href="{{ route('logout') }}"
                                            @click.prevent="$root.submit();" class="w-full" />
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                    <script>
                        // Seleção dos elementos
                        const openBtn = document.getElementById('openOffcanvas');
                        const closeBtn = document.getElementById('closeOffcanvas');
                        const offcanvas = document.getElementById('offcanvas');
                        const overlay = document.getElementById('overlay');

                        // Abrir o Offcanvas e ativar o Blur
                        openBtn.addEventListener('click', () => {
                            offcanvas.classList.add('active');
                            overlay.classList.add('active');
                        });

                        // Fechar o Offcanvas e remover o Blur
                        closeBtn.addEventListener('click', () => {
                            offcanvas.classList.remove('active');
                            overlay.classList.remove('active');
                        });

                        // Fechar ao clicar fora
                        overlay.addEventListener('click', () => {
                            offcanvas.classList.remove('active');
                            overlay.classList.remove('active');
                        });
                    </script>

                @endauth
            </div>

        </div>
    </div>

</div>
