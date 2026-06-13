<div>

    @if ($target ?? false)

        <div class="{{ setClass('divContentHeader') }} ">
            <div class="w-full flex justify-between items-center">
                <div>
                    {!! setLabelHeader('Evento', $target->event_name, 'PARTICIPANTES - HORA CONSULTA ' . now()->format('d/m/Y H:i')) !!}
                </div>
                <div class="p-0">
                    @auth
                        <x-button flat white icon="reply" label="VOLTAR" href="{{ route('dashboard-evento') }}" class="hover:text-sky-500" />
                    @endauth
                </div>
            </div>
        </div>

        <div class="w-full max-w-7xl mx-auto">
            <x-jet-validation-errors />
        </div>

        {{-- DADOS --}}
        {{-- <div class="{{ setClass('divContent') }} bg-white pb-8"> --}}
        <div class="w-full max-w-7xl mx-auto bg-white py-8 px-2">
            <div class="w-full">
                @if ($pedidos ?? false)
                    @php
                        //
                        $ingressoQtd = 0;
                        foreach ($target->ticketsTypes as $ticketType)
                            $ingressoQtd += $ticketType->amount;

                        //
                        $participantes         = $target->tickets->whereIn('ticket_status',ticketStatusCapacidade('participantes'));
                        $ingressoQtdVendidos   = $participantes->count() ?? 0;
                        $ingressoQtdDisponivel = $ingressoQtd - $ingressoQtdVendidos;
                    @endphp


                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div class="w-full flex flex-col md:flex-row justify-between py-2 gap-2">
                            <div class="w-full md:w-auto text-xs md:text-sm font-normal">
                                <div class="flex justify-between gap-2 pb-0.5 px-3 bg-black text-white rounded-full shadow-sm uppercase">
                                    <div>TOTAL</div>
                                    <div class="font-bold">{{ $ingressoQtdVendidos }}</div>
                                </div>
                            </div>
                            <div>
                                <x-button href="{{ route('checkin-target',['ref_target' => 'app_event','ref_target_slug' => $target->event_slug]) }}" label="LER QR CODE" right-icon="qrcode" class="py-0.5 md:py-2 uppercase" />
                            </div>
                        </div>
                    </div>

                    {{-- <pre>
                        {{ print_r($target->toArray()) }}
                    </pre> --}}

                    <div class="w-full bg-white my-4">
                        {{--  --}}
                        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.2/css/uikit.min.css">
                        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

                        <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
                        {{--  --}}
                        <script type="text/javascript" charset="utf8" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
                        <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/plug-ins/1.10.10/sorting/datetime-moment.js"></script>
                        {{--  --}}
                        <table id="example" style="width:100%; border-spacing: 0px 10px;">
                            <thead>
                                <tr>
                                    @php
                                        // INI COLUNAS
                                        $table_coluns = ['localizador','nome','email'];

                                        // SE QUESTIONS
                                        $questions_user_json= false;
                                        //
                                        if($target->questions_user_json ?? false)
                                        {
                                            $questions_user_json = json_decode($target->questions_user_json ?? '{}', true);
                                            //
                                            if($questions_user_json['campos'] ?? false)
                                            {
                                                $questions_user_json = $questions_user_json['campos'];
                                                //
                                                foreach ($questions_user_json ?? [] as $question_key => $question_values)
                                                {
                                                    $table_coluns[] = $question_values['input_label'];
                                                }
                                            }
                                        }

                                        // TIPO INGRESSO
                                        $table_coluns[] = 'tipo ' . $target->sales_btn;
                                    @endphp

                                    @foreach ($table_coluns as $column)
                                        <th class="text-center text-sm uppercase whitespace-nowrap">{{ strtoupper(__($column)) }}</th>
                                    @endforeach

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($participantes->sortBy('user_name') ?? [] as $participante)

                                @php
                                $statusClass = (in_array($participante->ticket_status, ['utilizado'])) ? 'table_status_utilizado' : 'table_status_disponivel';
                                @endphp

                                <tr class="shadow-md hover:shadow-lg">
                                    <td class="{{ setClass($statusClass) }}  whitespace-nowrap">
<a href="{{ route('checkin-target',['ref_target' => $target_ref, 'ref_target_slug' => $target_slug, 'control' => $participante->ticket_control ?? null]) }}">
<div class="font-medium uppercase truncate flex justify-around items-center hover:opacity-80 gap-2 {{ setClass($statusClass) }}">
<div>{{ $participante->ticket_control }}</div> <div>{{ __($participante->ticket_status) }}</div>
</div>
</a>
                                    </td>
                                    <td class="{{ setClass($statusClass . '_bg') }} whitespace-nowrap">
<div class="uppercase truncate">{{ $participante->user_name }}</div>
                                    </td>
                                    <td class="{{ setClass($statusClass . '_bg') }} whitespace-nowrap">
<div class="lowercase truncate">{{ $participante->user_email }}</div>
                                    </td>


                                    {{-- QUETIONS --}}
                                    @if ($questions_user_json ?? false)

                                        @php
                                        $user_json_answers = [];

                                        if ($participante->user_json_answers ?? false)
                                        {
                                            $user_json_answers = json_decode($participante->user_json_answers ?? '{}', true);
                                        }
                                        @endphp

                                        @foreach ($questions_user_json ?? [] as $question_key => $question_values)

                                            <td class="{{ setClass($statusClass . '_bg') }} whitespace-nowrap">
<div class="uppercase truncate">{{ $user_json_answers[$question_key] ?? '---' }}</div>
                                            </td>
                                        @endforeach

                                    @endif




                                    {{-- TIPO INGRESSO --}}
                                    <td class="{{ setClass($statusClass . '_bg') }} whitespace-nowrap">
<div class="uppercase truncate">{{ $participante->event_ticket_name }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{--  --}}
                        <script>

                            document.title = '{{strtoupper($target_ref)}}-{{strtoupper($target->event_name)}}-PARTICIPANTES-{{now()->format("Ymd-Hi")}}';

                            $(document).ready(function () {
                                // $.fn.dataTable.moment('DD/MM/YYYY'); //Formatação sem Hora
                                $.fn.dataTable.moment('DD/MM/YYYY hh:mm'); //Formatação sem Hora
                                $('#example').DataTable({
                                    autoWidth: true,
                                    scrollX: true,
                                    dom: 'Bfrti',
                                    buttons: [
                                        'excelHtml5',
                                        'pdfHtml5'
                                    ],
                                    language: {
                                        url:"https://cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
                                    },
                                    lengthMenu: [[-1]],
                                    order: [[1, 'asc']],
                                    columnDefs: [
                                        { width: "170px", targets: 0 },
                                        { className: "dt-head-left", targets: "_all"}
                                    ],
                                    ordering: false,
                                });
                            });
                        </script>
                    </div>
                @else
                    <div class="w-full p-4 bg-red-100 shadow-sm">
                        <h2>POR ENQUANTO NÃO TEMOS PEDIDOS</h2>
                    </div>
                @endif
            </div>
        </div>

    @else
        <div class="w-full p-4 bg-red-100 shadow-sm">
            <h2>SEM DADOS PARA EXIBIR</h2>
        </div>
    @endif

</div>
