<div class="w-full max-w-7xl mx-auto mb-6">

    
    <div class="mx-2 md:mx-0 my-4 bg-gradient-to-r from-green-500 via-emerald-500 to-teal-500 rounded-lg relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-pattern-checkin" width="8" height="8" patternUnits="userSpaceOnUse">
                        <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-pattern-checkin)"/>
            </svg>
        </div>
        <div class="relative z-10 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 flex-1 justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <div>
                        <h1 class="flex flex-col md:flex-row gap-2 text-base md:text-3xl font-bold text-white">
                            <div class="uppercase">Check-in</div>
                            <?php if($target->event_name ?? false): ?>
                                <div class="text-white/90 font-light"><?php echo e($target->event_name); ?></div>
                            <?php endif; ?>
                        </h1>
                    </div>
                </div>
                <?php if(auth()->guard()->check()): ?>
                    <?php if($target->id ?? false): ?>
                        <a href="<?php echo e(route('evento-by-uuid', $target->id)); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-white/20 border border-white/40 rounded hover:bg-white/30 hover:border-white/60 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            VOLTAR
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="flex flex-col items-center">
        <div class="w-full max-w-2xl">

            
            <style>
                div#reader>div {
                    padding: 0 !important;
                    border-bottom: none !important;
                }
                #reader__status_span,
                div#reader>div {
                    padding: 5px 5px !important;
                }
                div#reader>div>span>span {
                    padding: 0 0 0 8px !important;
                    margin-bottom: 5px;
                    font-weight: 400;
                }
                #reader button,
                #reader__dashboard_section_csr>div>button {
                    padding: 10px;
                    background-color: #10b981;
                    color: #ffffff;
                    font-weight: 600;
                    border-radius: 8px;
                    transition: all 0.2s;
                }
                #reader__dashboard_section_csr>div>button:hover,
                #reader__dashboard_section_csr>div>button:hover {
                    background-color: #059669;
                }
            </style>

            
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4" id="dataQrCode" style="display: none;">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-base font-semibold text-gray-800">Informações do Ingresso</h2>
                </div>
                <div class="p-6 space-y-4">
                    
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 border border-gray-200">
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Localizador</div>
                        <div class="text-xl font-bold text-gray-900 font-mono" id="ticket_control">---</div>
                    </div>

                    
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                        <div class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">Evento</div>
                        <div class="text-lg font-semibold text-blue-900 uppercase" id="event_name">---</div>
                    </div>

                    
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                        <div class="text-xs font-medium text-purple-600 uppercase tracking-wide mb-1">Tipo de Ingresso</div>
                        <div class="text-lg font-semibold text-purple-900 uppercase" id="event_ticket_name">---</div>
                    </div>

                    
                    <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-4 border border-indigo-200">
                        <div class="text-xs font-medium text-indigo-600 uppercase tracking-wide mb-1">Participante</div>
                        <div class="text-lg font-semibold text-indigo-900 uppercase" id="user_name">---</div>
                    </div>

                    
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg p-4 border border-amber-200">
                        <div class="text-xs font-medium text-amber-600 uppercase tracking-wide mb-1">Status</div>
                        <div class="text-lg font-semibold text-amber-900 uppercase" id="ticket_status">---</div>
                    </div>

                    
                    <div id="statusDisponivel" style="display: none;">
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'REALIZAR CHECKIN'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['positive' => true,'onclick' => 'realizaCheckin()','class' => 'w-full py-3 text-base font-semibold']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                    </div>

                    
                    <div id="statusUtilizado" style="display: none;">
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                            <div class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">Check-in Realizado</div>
                            <div class="text-lg font-semibold text-green-900 uppercase" id="ticket_checkin_datetime">---</div>
                        </div>
                    </div>

                    
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white text-center font-semibold uppercase shadow-md" id="dataQrCodeSucesso" style="display: none;">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>CHECKIN REALIZADO COM SUCESSO</span>
                        </div>
                    </div>

                    
                    <div class="pt-2">
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['rounded' => true,'outline' => true,'label' => 'NOVO CHECKIN'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['primary' => true,'onclick' => 'novoCheckin()','class' => 'w-full py-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg mb-4" id="divReader">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <h2 class="text-base font-semibold text-gray-800">Leitura de QR Code</h2>
                </div>
                <div class="p-6">
                    <div class="bg-gray-50 rounded-lg p-4 border-2 border-dashed border-gray-300">
                        <div id="reader"></div>
                    </div>
                    <p class="text-center text-sm text-gray-500 mt-4">Posicione o QR Code do ingresso na frente da câmera</p>
                </div>
            </div>

            
            <?php if($target->event_slug ?? false): ?>
                <div class="mb-4">
                    <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['href' => ''.e(route('dashboard-vendas',['target_ref' => 'evento', 'target_slug' => $target->event_slug, 'target_id' => $target->id, 'view_status' => 'participantes'])).'','label' => 'LISTA DE PARTICIPANTES','icon' => 'clipboard-list'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-full py-3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $attributes = $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92)): ?>
<?php $component = $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92; ?>
<?php unset($__componentOriginal53cf851b4d6af185b0b5e0467ca69b92); ?>
<?php endif; ?>
                </div>
            <?php endif; ?>

            
            <div id="divBuscando" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center" style="display: none;">
                <div class="bg-white rounded-lg shadow-xl p-8 max-w-sm w-full mx-4">
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-green-600 mb-4"></div>
                        <p class="text-gray-700 font-medium">Buscando informações...</p>
                        <p class="text-gray-500 text-sm mt-2">Aguarde enquanto processamos o QR Code</p>
                    </div>
                </div>
            </div>

            
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-4" id="dataQrCodeErro" style="display: none;">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-red-700 font-semibold uppercase" id="dataQrCodeErroTexto">---</div>
                </div>
            </div>

        </div>
    </div>

    
    <script src="<?php echo e(asset('/js/mebjas_html5-qrcode_master_minified_html5-qrcode.min.js')); ?>"></script>

    <script type="text/javascript">
        var buscaCheckinQrCode = null;

        function popularDivs(data={})
        {
            // VALUES
            var ticket_control = document.getElementById('ticket_control');
                ticket_control.textContent = data.ticket_control ?? '---';

            var event_ticket_name = document.getElementById('event_ticket_name');
                event_ticket_name.textContent = data.event_ticket_name ?? '---';

            var event_name = document.getElementById('event_name');
                event_name.textContent = data.event_name ?? '---';

            var user_name = document.getElementById('user_name');
                user_name.textContent = data.user_name ?? '---';

            var ticket_status = document.getElementById('ticket_status');
                ticket_status.textContent = data.ticket_status ?? '---';

            var ticket_checkin_datetime = document.getElementById('ticket_checkin_datetime');
                if(data.ticket_checkin_datetime)
                {
                    var date = new Date(data.ticket_checkin_datetime);
                    var formatted = date.toLocaleString('pt-BR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    ticket_checkin_datetime.textContent = formatted;
                }
                else
                {
                    ticket_checkin_datetime.textContent = '---';
                }

            if(data.ticket_status == 'disponivel')
            {
                var divElement = document.getElementById('statusDisponivel');
                    divElement.style.display = 'block';

                var divElement = document.getElementById('statusUtilizado');
                    divElement.style.display = 'none';
            }
            else
            {
                var divElement = document.getElementById('statusDisponivel');
                    divElement.style.display = 'none';

                var divElement = document.getElementById('statusUtilizado');
                    divElement.style.display = 'block';
            }
        }

        function realizaCheckin()
        {
            if(confirm('Confirma o check-in? Esta ação é irreversível!'))
            {
                var ticket_control = document.getElementById('ticket_control');
                var qrCodeRealizar = ticket_control.textContent;
                var url = '<?php echo e(config('domains.eventos')); ?>/api/checkin/event/' + qrCodeRealizar + '/realizar';

                var request = new XMLHttpRequest();

                request.open('GET', url, true);

                request.onload = function() {
                    if (request.status >= 200 && request.status < 400)
                    {
                        var data = JSON.parse(request.responseText);
                        console.log('REALIZADO ',data.return);

                        popularDivs(data.return);

                        // Mostrar mensagem de sucesso
                        var divElement = document.getElementById('dataQrCodeSucesso');
                            divElement.style.display = 'block';

                        // Scroll para a mensagem de sucesso
                        divElement.scrollIntoView({ behavior: 'smooth', block: 'center' });

                        // Ocultar após 5 segundos
                        setTimeout(function() {
                            divElement.style.display = 'none';
                        }, 5000);
                    }
                    else
                    {
                        var data = JSON.parse(request.responseText);
                        console.error(request.status,data);

                        var divElement = document.getElementById('dataQrCodeSucesso');
                            divElement.style.display = 'none';

                        var divElement = document.getElementById('dataQrCodeErro');
                            divElement.style.display = 'block';

                        var divElementTexto = document.getElementById('dataQrCodeErroTexto');
                            divElementTexto.textContent = data.msg ?? 'ERRO AO REALIZAR CHECKIN';

                        var divElement = document.getElementById('divReader');
                            divElement.style.display = 'block';
                    }
                };

                request.onerror = function() {
                    console.error('Erro de conexão');
                    alert('Erro de conexão. Verifique sua internet e tente novamente.');
                };

                request.send();
            }
        }

        function buscaCheckin(qrCodeCheckin)
        {
            console.log('BUSCAR INI')

            if(qrCodeCheckin != buscaCheckinQrCode)
            {
                buscaCheckinQrCode = qrCodeCheckin;

                var divElement = document.getElementById('divReader');
                    divElement.style.display = 'none';

                var divElement = document.getElementById('divBuscando');
                    divElement.style.display = 'flex';

                try
                {
                    if(qrCodeCheckin.length > 30)
                    {
                        alert('QR-CODE INVÁLIDO')
                        novoCheckin()
                        return;
                    }

                    console.log('API - ENVIADO')

                    var url = '<?php echo e(config('domains.eventos')); ?>/api/checkin/event/' + qrCodeCheckin;

                    var request = new XMLHttpRequest();
                        request.open('GET', url, true);
                        request.onload = function() {

                            var divElement = document.getElementById('divBuscando');
                                divElement.style.display = 'none';

                            if (request.status >= 200 && request.status < 400)
                            {
                                var data = JSON.parse(request.responseText);

                                popularDivs(data.return);

                                console.log(data.return);

                                var divReader = document.getElementById('divReader');
                                    divReader.style.display = 'none';

                                var dataQrCodeErro = document.getElementById('dataQrCodeErro');
                                    dataQrCodeErro.style.display = 'none';

                                var dataQrCode = document.getElementById('dataQrCode');
                                    dataQrCode.style.display = 'block';

                                // Scroll suave para o card de informações
                                dataQrCode.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                            else
                            {
                                var data = JSON.parse(request.responseText);
                                console.error(request.status,data);

                                var divElement = document.getElementById('dataQrCodeErro');
                                    divElement.style.display = 'block';

                                var divElementTexto = document.getElementById('dataQrCodeErroTexto');
                                    divElementTexto.textContent = data.msg ?? 'ERRO AO BUSCAR CHECKIN';

                                novoCheckin()
                            }
                        };

                        request.onerror = function() {
                            var divElement = document.getElementById('divBuscando');
                                divElement.style.display = 'none';

                            alert('Erro de conexão. Verifique sua internet e tente novamente.');
                            console.error('Erro de conexão');

                            novoCheckin()
                        };

                        request.send();

                    console.log('API - RECEBIDO')
                }
                catch(err)
                {
                    var divElement = document.getElementById('divBuscando');
                        divElement.style.display = 'none';

                    alert(err.message);
                    console.error('ERRO', err, err.message)
                    novoCheckin()
                }
            }
            else
            {
                console.log('CONSULTADO')
            }

            console.log('BUSCAR FIM')
        }

        function novoCheckin()
        {
            buscaCheckinQrCode = null;

            var divElement = document.getElementById('divReader');
                divElement.style.display = 'block';

            var divElement = document.getElementById('divBuscando');
                divElement.style.display = 'none';

            var divElement = document.getElementById('dataQrCode');
                divElement.style.display = 'none';

            var divElement = document.getElementById('dataQrCodeErro');
                divElement.style.display = 'none';

            popularDivs()
        }

        var html5QrcodeScanner = new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: { width: 300, height: 300 },
            aspectRatio: 1.0,
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
        });

        function onScanSuccess(decodedText) {
            console.log('QRCODE:', decodedText);
            buscaCheckin(decodedText)
        }

        html5QrcodeScanner.render(onScanSuccess);
        novoCheckin()

    </script>
</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos-proeventpay-transicao/resources/views/livewire/checkin/checkin-target.blade.php ENDPATH**/ ?>