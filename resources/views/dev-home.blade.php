<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProEventPay - Ambiente de Desenvolvimento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-800 mb-4">
                    ProEventPay
                </h1>
                <p class="text-xl text-gray-600">
                    Ambiente de Desenvolvimento Local
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Acesso via <code class="bg-gray-200 px-2 py-1 rounded">http://127.0.0.1:8000</code>
                </p>
            </div>

            <!-- Cards de Acesso -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">

                <!-- Painel Administrativo -->
                <a href="/painel" class="group">
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border-2 border-transparent hover:border-blue-500">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500 transition-colors">
                                <svg class="w-8 h-8 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">
                                Painel Administrativo
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Gestão completa do sistema
                            </p>
                            <div class="text-xs font-mono bg-gray-100 px-3 py-1 rounded text-gray-700">
                                /painel
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Campanhas -->
                <a href="/campanhas" class="group">
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border-2 border-transparent hover:border-green-500">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-500 transition-colors">
                                <svg class="w-8 h-8 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">
                                Campanhas
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Campanhas promocionais
                            </p>
                            <div class="text-xs font-mono bg-gray-100 px-3 py-1 rounded text-gray-700">
                                /campanhas
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Eventos -->
                <a href="/eventos" class="group">
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-8 border-2 border-transparent hover:border-amber-500">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-500 transition-colors">
                                <svg class="w-8 h-8 text-amber-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">
                                Eventos
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Gestão de eventos e ingressos
                            </p>
                            <div class="text-xs font-mono bg-gray-100 px-3 py-1 rounded text-gray-700">
                                /eventos
                            </div>
                        </div>
                    </div>
                </a>

            </div>

            <!-- Informações Importantes -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    ℹ️ Informações Importantes
                </h2>

                <div class="space-y-4">
                    <!-- Credenciais -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <h3 class="font-bold text-blue-900 mb-2">🔐 Credenciais de Acesso</h3>
                        <div class="text-sm text-blue-800 space-y-1">
                            <p><strong>Email:</strong> <code class="bg-blue-100 px-2 py-1 rounded">admin@empresateste.com</code></p>
                            <p><strong>Senha:</strong> <code class="bg-blue-100 px-2 py-1 rounded">admin123</code></p>
                            <p class="text-xs text-blue-600 mt-2">⚠️ Altere a senha após o primeiro login!</p>
                        </div>
                    </div>

                    <!-- URLs de Desenvolvimento -->
                    <div class="bg-gray-50 border-l-4 border-gray-500 p-4 rounded">
                        <h3 class="font-bold text-gray-900 mb-2">🔗 URLs de Desenvolvimento</h3>
                        <div class="text-sm text-gray-700 space-y-1 font-mono">
                            <p>• Painel: <a href="/painel" class="text-blue-600 hover:underline">http://127.0.0.1:8000/painel</a></p>
                            <p>• Campanhas: <a href="/campanhas" class="text-blue-600 hover:underline">http://127.0.0.1:8000/campanhas</a></p>
                            <p>• Eventos: <a href="/eventos" class="text-blue-600 hover:underline">http://127.0.0.1:8000/eventos</a></p>
                            <p>• Super Admin: <a href="/super-administrador" class="text-blue-600 hover:underline">http://127.0.0.1:8000/super-administrador</a></p>
                        </div>
                    </div>

                    <!-- Produção -->
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                        <h3 class="font-bold text-green-900 mb-2">🌐 URLs de Produção</h3>
                        <div class="text-sm text-green-800 space-y-1 font-mono">
                            <p>• Painel: https://painel.proeventpay.com</p>
                            <p>• Campanhas: https://campanhas.proeventpay.com</p>
                            <p>• Eventos: https://eventos.proeventpay.com</p>
                        </div>
                    </div>

                    <!-- Nota Técnica -->
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded">
                        <h3 class="font-bold text-amber-900 mb-2">💡 Nota Técnica</h3>
                        <div class="text-sm text-amber-800">
                            <p>Em <strong>desenvolvimento local</strong>, a aplicação usa <strong>prefixos de URL</strong> (<code>/painel</code>, <code>/campanhas</code>, <code>/eventos</code>) ao invés de subdomínios.</p>
                            <p class="mt-2">Em <strong>produção</strong>, a aplicação automaticamente muda para <strong>subdomínios</strong> (<code>painel.*</code>, <code>campanhas.*</code>, <code>eventos.*</code>).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documentação -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">📚 Documentação</h3>
                        <p class="text-blue-100">
                            Consulte SETUP.md e ANALISE_SCHEMA_SQL.md para mais informações
                        </p>
                    </div>
                    <svg class="w-16 h-16 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                    </svg>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center text-gray-600 text-sm pb-8">
        <p>ProEventPay - Plataforma de Gestão de Eventos e Campanhas</p>
        <p class="mt-1">Ambiente de Desenvolvimento - {{ now()->format('Y') }}</p>
    </footer>
</body>
</html>
