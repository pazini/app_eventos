<div class="w-full max-w-7xl mx-auto mb-6">

    <div class="mb-3">
        <?php if (isset($component)) { $__componentOriginal522a59481d8bfd4d44478643bc3270fb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal522a59481d8bfd4d44478643bc3270fb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'jetstream::components.validation-errors','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('jet-validation-errors'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $attributes = $__attributesOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__attributesOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal522a59481d8bfd4d44478643bc3270fb)): ?>
<?php $component = $__componentOriginal522a59481d8bfd4d44478643bc3270fb; ?>
<?php unset($__componentOriginal522a59481d8bfd4d44478643bc3270fb); ?>
<?php endif; ?>
    </div>

    <?php if($target ?? false): ?>

        
        <div class="mb-4 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid-pattern-participantes" width="8" height="8" patternUnits="userSpaceOnUse">
                            <path d="M 8 0 L 0 0 0 8" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid-pattern-participantes)"/>
                </svg>
            </div>
            <div class="relative z-10 p-4">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-3">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <div>
                                <h1 class="text-lg font-bold text-white">Participantes do Evento</h1>
                                <p class="text-white/90 text-xs mt-0.5"><?php echo e($target->event_name); ?> - Consulta: <?php echo e(now()->format('d/m/Y H:i')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(isAdmin()): ?>
                                <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['outline' => true,'label' => 'FILTAR','rightIcon' => 'filter'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'xs' => true,'class' => 'hover:bg-white/20']); ?>
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
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal53cf851b4d6af185b0b5e0467ca69b92 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53cf851b4d6af185b0b5e0467ca69b92 = $attributes; } ?>
<?php $component = WireUi\View\Components\Button::resolve(['label' => 'LER QR CODE','rightIcon' => 'qrcode','href' => ''.e(route('checkin-target',['ref_target' => 'event','ref_target_slug' => $target->event_slug])).''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(WireUi\View\Components\Button::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['white' => true,'xs' => true,'class' => 'hover:bg-white/20']); ?>
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
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('evento-by-uuid', $target->id)); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-white/20 border border-white/40 rounded hover:bg-white/30 hover:border-white/60 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                VOLTAR
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if($target_tickets ?? false): ?>

            <?php
                $ingressoQtd           = 0;
                // Inclui participantes e reservas temporárias
                $participantes         = $target_tickets->whereIn('ticket_status',array_merge(ticketStatusCapacidade('participantes'), ['reserva_temp','reserva_temp_boleto']));
                $ingressoQtdVendidos   = $participantes->count() ?? 0;
                $ingressoQtdDisponivel = $ingressoQtd - $ingressoQtdVendidos;

                // CONTADORES - incluindo reservas temporárias
                $contador_disponivel = $participantes->whereIn('ticket_status',['disponivel','disponível'])->count();
                $contador_utilizado  = $participantes->whereIn('ticket_status',['utilizado'])->count();
                $contador_cancelado  = $participantes->whereIn('ticket_status',['cancelado','canceled'])->count();
                $contador_reserva_temp = $participantes->whereIn('ticket_status',['reserva_temp','reserva_temp_boleto','gerado'])->count();

                // INI COLUNAS - DEFINE COLUNAS RELATORIO
                $table_coluns = [];
                $table_coluns[] = 'localizador / status'; // Combinado
                $table_coluns[] = 'utilizador';
                $table_coluns[] = 'telefone';

                // SE QUESTIONS
                $questions_user_json = false;
                if($target->questions_user_json ?? false)
                {
                    $filtros = [];
                    $questions_user_json = json_decode($target->questions_user_json ?? '{}', true);
                    if($questions_user_json['campos'] ?? false)
                    {
                        $questions_user_json = $questions_user_json['campos'];
                        foreach ($questions_user_json ?? [] as $question_key => $question_values)
                        {
                            $table_coluns[] = $question_values['input_label'];
                            if($question_values['input_filter'] ?? false)
                            {
                                $filtros[] = $question_values;
                            }
                        }
                    }
                }

                // TIPO INGRESSO
                $table_coluns[] = 'tipo';
                $table_coluns[] = 'Gerado Em';
                $table_coluns[] = 'Comprador';
                $table_coluns[] = 'Email';
                $table_coluns[] = 'Documento';
            ?>

            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 mb-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 px-4 py-3 border-b border-gray-200">
                        <div class="text-xs font-medium text-gray-300 uppercase tracking-wide">Total</div>
                        <div class="text-2xl font-bold text-white mt-1"><?php echo e($ingressoQtdVendidos); ?></div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 px-4 py-3 border-b border-gray-200">
                        <div class="text-xs font-medium text-blue-100 uppercase tracking-wide">Disponível</div>
                        <div class="text-2xl font-bold text-white mt-1"><?php echo e($contador_disponivel); ?></div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 px-4 py-3 border-b border-gray-200">
                        <div class="text-xs font-medium text-yellow-100 uppercase tracking-wide">Reserva Temp</div>
                        <div class="text-2xl font-bold text-white mt-1"><?php echo e($contador_reserva_temp); ?></div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 px-4 py-3 border-b border-gray-200">
                        <div class="text-xs font-medium text-green-100 uppercase tracking-wide">Utilizado</div>
                        <div class="text-2xl font-bold text-white mt-1"><?php echo e($contador_utilizado); ?></div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-br from-red-500 to-red-600 px-4 py-3 border-b border-gray-200">
                        <div class="text-xs font-medium text-red-100 uppercase tracking-wide">Cancelado</div>
                        <div class="text-2xl font-bold text-white mt-1"><?php echo e($contador_cancelado); ?></div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
                <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                    <h2 class="text-base font-semibold text-gray-800">Lista de Participantes</h2>
                </div>
                <div class="p-4 overflow-x-auto">
                    <div class="w-full">
                        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
                        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>

                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <?php $__currentLoopData = $table_coluns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center text-xs font-semibold text-gray-700 uppercase bg-gray-100 px-3 py-2 whitespace-nowrap">
                                            <?php echo e(mb_strtoupper(__($column))); ?>

                                        </th>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $participantes->sortBy('user_name') ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $statusClass = 'disponivel';
                                        $statusBadgeClass = 'bg-blue-100 text-blue-800';
                                        $statusOrder = 1; // Para ordenação: 1=disponivel, 2=reserva_temp, 3=utilizado, 4=cancelado

                                        if(in_array($participante->ticket_status, ['utilizado']))
                                        {
                                            $statusClass = 'utilizado';
                                            $statusBadgeClass = 'bg-green-100 text-green-800';
                                            $statusOrder = 3;
                                        }
                                        elseif(in_array($participante->ticket_status, ['reserva_temp','reserva_temp_boleto','gerado']))
                                        {
                                            $statusClass = 'reserva_temp';
                                            $statusBadgeClass = 'bg-yellow-100 text-yellow-800';
                                            $statusOrder = 2;
                                        }
                                        elseif(in_array($participante->ticket_status, ['cancelado','canceled']))
                                        {
                                            $statusClass = 'cancelado';
                                            $statusBadgeClass = 'bg-red-100 text-red-800';
                                            $statusOrder = 4;
                                        }

                                        $href = route('checkin-target',['ref_target' => $target_ref, 'ref_target_slug' => $target_slug, 'control' => $participante->ticket_control ?? null]);
                                        if(in_array($participante->ticket_status, ['cancelado','canceled']))
                                        {
                                            $href = '#';
                                        }

                                        $user_json_answers = [];
                                        if ($participante->user_json_answers ?? false)
                                        {
                                            $user_json_answers = json_decode($participante->user_json_answers ?? '{}', true);
                                        }
                                    ?>

                                    <tr class="hover:bg-gray-50 transition-colors">
                                        
                                        <td class="px-3 py-2 text-xs whitespace-nowrap" data-order="<?php echo e($statusOrder); ?>">
                                            <div class="flex flex-col gap-1">
                                                <?php if($href != '#'): ?>
                                                    <a href="<?php echo e($href); ?>" class="text-blue-600 hover:text-blue-800 font-mono font-semibold hover:underline whitespace-nowrap">
                                                        <?php echo e($participante->ticket_control); ?>

                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-gray-400 font-mono font-semibold whitespace-nowrap"><?php echo e($participante->ticket_control); ?></span>
                                                <?php endif; ?>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($statusBadgeClass); ?> whitespace-nowrap">
                                                    <?php echo e(mb_strtoupper(__($participante->ticket_status))); ?>

                                                </span>
                                            </div>
                                        </td>

                                        
                                        <td class="px-3 py-2 text-xs text-gray-900 uppercase whitespace-nowrap">
                                            <?php echo e(mb_strtoupper($participante->user_name)); ?>

                                        </td>

                                        
                                        <td class="px-3 py-2 text-xs text-gray-600 whitespace-nowrap">
                                            (<?php echo e($participante->user_contact_ddd ?? '--'); ?>) <?php echo e($participante->user_contact_num ?? '---- - ----'); ?>

                                        </td>

                                        
                                        <?php if($questions_user_json ?? false): ?>
                                            <?php $__currentLoopData = $questions_user_json ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question_key => $question_values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $valor = $user_json_answers[$question_key] ?? '---';
                                                    if(is_array($valor))
                                                    {
                                                        $valor = '---';
                                                    }
                                                ?>
                                                <td class="px-3 py-2 text-xs text-gray-700 uppercase whitespace-nowrap">
                                                    <?php echo e($valor ?? '---'); ?>

                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>

                                        
                                        <td class="px-3 py-2 text-xs text-gray-700 uppercase whitespace-nowrap">
                                            <?php echo e($participante->event_ticket_name); ?>

                                        </td>

                                        
                                        <td class="px-3 py-2 text-xs text-gray-600 whitespace-nowrap">
                                            <?php echo e($participante->created_at ? $participante->created_at->format('d/m/Y H:i') : '---'); ?>

                                        </td>

                                        
                                        <td class="px-3 py-2 text-xs text-gray-900 uppercase whitespace-nowrap">
                                            <?php echo e($participante->order->buyer_name ?? '---'); ?>

                                        </td>

                                        
                                        <td class="px-3 py-2 text-xs text-gray-600 lowercase whitespace-nowrap">
                                            <?php echo e(mb_strtolower($participante->order->buyer_email ?? '---')); ?>

                                        </td>

                                        
                                        <td class="px-3 py-2 text-xs text-gray-700 whitespace-nowrap">
                                            <?php echo e($participante->order->buyer_doc_num ? putMask($participante->order->buyer_doc_num,$participante->order->buyer_doc_type) : '---'); ?>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>

                        <script>
                            document.title = '<?php echo e(strtoupper($target_ref)); ?>-<?php echo e(strtoupper($target->event_name)); ?>-PARTICIPANTES-<?php echo e(now()->format("Ymd-Hi")); ?>';

                            $(document).ready(function () {
                                $.fn.dataTable.moment('DD/MM/YYYY hh:mm');
                                $('#example').DataTable({
                                    autoWidth: true,
                                    scrollX: true,
                                    dom: 'Bfrtip',
                                    buttons: [
                                        {
                                            extend: 'excelHtml5',
                                            text: 'Excel',
                                            className: 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded'
                                        },
                                        {
                                            extend: 'pdfHtml5',
                                            text: 'PDF',
                                            className: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded'
                                        }
                                    ],
                                    language: {
                                        url:"https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
                                    },
                                    lengthMenu: [[-1], ["Todos"]],
                                    order: [[0, 'asc']], // Ordena pela primeira coluna (localizador/status) que tem data-order
                                    columnDefs: [
                                        { width: "200px", targets: 0 },
                                        { className: "dt-head-center", targets: "_all"},
                                        { type: "num", targets: 0 } // Permite ordenação numérica pelo data-order
                                    ],
                                    pageLength: -1,
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p class="mt-2 text-sm font-medium text-gray-900">Por enquanto não temos participantes</p>
                    <p class="mt-1 text-sm text-gray-500">Os participantes aparecerão aqui quando houver vendas</p>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        
        <div class="mb-4 bg-gradient-to-r from-purple-500 via-indigo-500 to-blue-500 rounded-lg relative overflow-hidden shadow-md">
            <div class="relative z-10 p-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h1 class="text-lg font-bold text-white">Participantes do Evento</h1>
                        <p class="text-white/90 text-xs mt-0.5">É preciso selecionar um evento</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-sm border-x border-b rounded-b-lg">
            <div class="p-6 text-center">
                <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Página Principal
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>
<?php /**PATH /home/igrnc24/public_html_sistemas/campanhas-eventos/resources/views/livewire/dashboard/vendas/dashboard-vendas-participantes-v2.blade.php ENDPATH**/ ?>