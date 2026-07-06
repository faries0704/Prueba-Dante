<x-layouts.app-layout title="Bandeja de Salida">
    <div class="main-content">
        <div class="container-fluid">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('messages.index') }}" class="text-decoration-none">Mensajes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Bandeja de Salida</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #005187;">
                        <i class="material-icons align-middle fs-3 me-1">outbox</i> Mensajes Enviados
                    </h3>
                    <p class="text-muted small mb-0">Historial y estado de entrega de las comunicaciones emitidas a los usuarios.</p>
                </div>
                <div>
                    <a href="{{ route('messages.index') }}" class="btn text-white px-3 d-flex align-items-center shadow-sm gap-1" style="background-color: #005187;">
                        <i class="material-icons">inbox</i> <small class="fw-bold">Bandeja de Entrada</small>
                    </a>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    @if($messages->count() > 0)
                        <div class="list-group list-group-flush" id="outboxListContainer">
                            @foreach($messages as $row)
                                @php
                                    $fotoPath = $row->receiver->photo ? '/backend/img/subidas/'.$row->receiver->photo : '/backend/img/user-default.png';
                                    
                                    // Estado de lectura para badges analíticos
                                    $isRead = $row->is_read;
                                    $badgeColor = $isRead ? 'bg-success-subtle text-success border-success' : 'bg-secondary-subtle text-secondary border-secondary';
                                    $iconCheck = $isRead ? 'done_all' : 'done';
                                    $txtStatus = $isRead ? 'Visto' : 'Entregado';
                                @endphp
                                
                                <div class="list-group-item list-group-item-action p-3 transition-all outbox-row-item border-left-indicator" 
                                     style="cursor: pointer;"
                                     data-id-msg="{{ $row->idmessage }}">
                                    
                                    <div class="row align-items-center g-3">
                                        <div class="col-12 col-md-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $fotoPath }}" width="38" height="38" class="rounded-circle me-2.5 border object-fit-cover shadow-sm" onerror="this.src='/backend/img/user-default.png'">
                                                <div class="text-truncate">
                                                    <span class="d-block text-dark text-truncate fw-semibold" style="font-size: 14px;">{{ $row->receiver->username }}</span>
                                                    <span class="badge border py-0.5 px-2 d-inline-flex align-items-center gap-0.5 {{ $badgeColor }}" style="font-size: 10px;">
                                                        <i class="material-icons style" style="font-size:12px;">{{ $iconCheck }}</i> {{ $txtStatus }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="text-truncate pe-md-3">
                                                <span class="text-secondary d-block text-truncate" style="font-size: 14.5px;">
                                                    {{ $row->subject }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-3 text-md-end d-flex justify-content-between align-items-center justify-content-md-end gap-3">
                                            <span class="text-muted d-inline-flex align-items-center small" style="font-size: 12px;">
                                                <i class="material-icons text-secondary fs-6 me-1">calendar_today</i>
                                                {{ \Carbon\Carbon::parse($row->sent_at)->format('d/m/Y H:i') }}
                                            </span>
                                            <button type="button" class="btn btn-light btn-sm btn-show-message shadow-sm border rounded-2 p-1.5 d-flex align-items-center" data-id="{{ $row->idmessage }}">
                                                <i class="material-icons text-info fs-5">visibility</i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-warning border-0 m-4 d-flex align-items-center p-4 rounded-3" role="alert">
                            <i class="material-icons text-warning me-3 fs-2">mail_lock</i>
                            <div>
                                <h5 class="fw-bold mb-1">¡Sin mensajes en salida!</h5>
                                <p class="mb-0 text-muted small">Aún no registra correspondencia o circulares emitidas desde su cuenta.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL PARA VER DETALLES DEL MENSAJE ENVIADO --}}
    <div class="modal fade" id="infoMessageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header text-white" style="background-color: #005187;">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <i class="material-icons">outbox</i> 
                        <span style="font-size:15px; font-weight:400;" class="text-white-50">Asunto:</span> 
                        <span id="info_span_subject" class="fw-bold text-wrap"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 pb-3 border-bottom gap-3">
                        <div class="d-flex align-items-center">
                            <img id="info_img_receiver" src="/backend/img/user-default.png" width="46" height="46" class="rounded-circle me-3 border shadow-sm object-fit-cover">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" id="info_span_receiver"></h6>
                                <small class="text-muted small">Destinatario asignado</small>
                            </div>
                        </div>
                        <div class="text-sm-end d-flex flex-column align-items-sm-end gap-1">
                            <span class="text-muted small d-inline-flex align-items-center gap-1" style="font-size:12px;">
                                <i class="material-icons text-secondary" style="font-size:15px;">schedule</i>
                                <span id="info_span_date"></span>
                            </span>
                            <span id="info_span_status" class="badge py-1.5 px-2.5 rounded border"></span>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-light rounded-3 border border-light-subtle">
                        <div id="info_p_message" class="text-dark" style="font-size: 14.5px; line-height: 1.6; white-space: pre-line;"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary px-4 rounded-2 small" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .transition-all {
            transition: background-color 0.15s ease;
        }
        .outbox-row-item:hover {
            background-color: #f8fafc !important;
        }
        .border-left-indicator {
            border-left: 4px solid transparent;
        }
        .outbox-row-item:hover .btn-show-message {
            background-color: #e2e8f0;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const infoModal = new bootstrap.Modal('#infoMessageModal');

            // Habilitar la apertura del mensaje al pulsar sobre cualquier sección de la tarjeta
            $(document).on('click', '.outbox-row-item', function(e) {
                if($(e.target).closest('.btn-show-message').length) return; // Evita doble trigger si se hace clic en el botón
                const idmessage = $(this).data('id-msg');
                openOutboxMessage(idmessage);
            });

            // Trigger al botón nativo de visibilidad
            $('.btn-show-message').on('click', function() {
                const idmessage = $(this).data('id');
                openOutboxMessage(idmessage);
            });

            // Función unificada Fetch de lectura externa
            function openOutboxMessage(idmessage) {
                $('#info_span_subject').text('Cargando asunto...');
                $('#info_span_receiver').text('...');
                $('#info_span_date').text('...');
                $('#info_span_status').text('').removeClass();
                $('#info_p_message').html('<div class="text-center py-3"><div class="spinner-border text-secondary" role="status"></div></div>');

                infoModal.show();

                $.get("{{ route('messages.outboxDetails') }}", { idmessage: idmessage }, function(response) {
                    if(response.success) {
                        $('#info_span_subject').text(response.subject);
                        $('#info_span_receiver').text(response.receiver_name);
                        $('#info_span_date').text(response.date);
                        $('#info_p_message').html(response.message);
                        
                        let fotoPath = response.receiver_photo ? `/backend/img/subidas/${response.receiver_photo}` : `/backend/img/user-default.png`;
                        $('#info_img_receiver').attr('src', fotoPath);

                        // Renderización del status condicional dentro del modal
                        $('#info_span_status').removeClass('bg-success bg-secondary text-white bg-success-subtle bg-secondary-subtle text-success text-secondary border');

                        if (response.status === 'Leído por el destinatario' || response.status === 'Leído') {
                            $('#info_span_status')
                                .addClass('bg-success-subtle text-success border border-success px-2 py-1 small')
                                .text('Leído');
                        } else {
                            $('#info_span_status')
                                .addClass('bg-secondary-subtle text-secondary border border-secondary px-2 py-1 small')
                                .text('Entregado');
                        }
                    }
                }).fail(function() {
                    $('#info_p_message').html('<span class="text-danger">Error al cargar el mensaje de salida.</span>');
                });
            }
        });
    </script>
    @endpush
</x-layouts.app-layout>