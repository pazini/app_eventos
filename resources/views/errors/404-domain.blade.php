<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Domínio Não Encontrado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-red-500 to-orange-500 p-8 text-center">
                <div class="text-white text-8xl font-bold mb-4">404</div>
                <h1 class="text-white text-3xl font-bold">Domínio Não Encontrado</h1>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-red-800 font-semibold mb-1">Domínio não cadastrado</h3>
                            <p class="text-red-700 text-sm">
                                O domínio <strong class="font-mono">{{ $domain ?? 'desconhecido' }}</strong> não está configurado no sistema.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-gray-600 text-center">
                        Este domínio não foi encontrado ou não está cadastrado como um domínio válido da aplicação.
                    </p>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-blue-900 font-semibold mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            O que pode ter acontecido?
                        </h4>
                        <ul class="text-blue-800 text-sm space-y-1 ml-7">
                            <li>• O domínio ainda não foi configurado no sistema</li>
                            <li>• O domínio foi digitado incorretamente</li>
                            <li>• O DNS ainda não propagou corretamente</li>
                            <li>• A aplicação foi desativada temporariamente</li>
                        </ul>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <a href="javascript:history.back()"
                       class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition-colors duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Voltar
                    </a>
                    <a href="/"
                       class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-lg text-center transition-all duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Ir para Página Inicial
                    </a>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    Se você é o administrador, acesse o
                    <a href="/super-administrador/domains" class="text-blue-600 hover:text-blue-700 font-semibold">
                        Gerenciador de Domínios
                    </a>
                    para configurar este domínio.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
