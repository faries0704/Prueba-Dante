<x-layouts.app-layout title="Bandeja de Entrada">
    <div class="main-content">
        <div class="container-fluid">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mensajes</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1" style="color: #005187;">
                        <i class="material-icons align-middle fs-3 me-1">inbox</i> Bandeja de Entrada
                    </h3>
                    <p class="text-muted small mb-0">Gestione y revise la comunicación interna recibida en su plataforma.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('messages.outbox') }}" class="btn text-white px-3 d-flex align-items-center shadow-sm" style="background-color: #005187;" title="Mensajes Enviados">
                        <i class="material-icons me-1">send</i> <small class="fw-bold">Enviados</small>
                    </a>
                    <button type="button" class="btn btn-danger text-white px-3 d-flex align-items-center shadow-sm" data-bs-toggle="modal" data-bs-target="#newMessageModal" title="Redactar Mensaje">
                        <i class="material-icons me-1">add_circle</i> <small class="fw-bold">Redactar</small>
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    @if($messages->count() > 0)
                        <div class="list-group list-group-flush" id="messagesListContainer">
                            @foreach($messages as $row)
                                @php
                                    $fotoPath = $row->sender->photo ? '/backend/img/subidas/'.$row->sender->photo : '/backend/img/user-default.png';
                                    
                                    // Variables dinámicas si el mensaje es no leído
                                    $isUnread = !$row->is_read;
                                    $unreadClass = $isUnread ? 'message-unread bg-body-tertiary fw-bold' : '';
                                    $borderLeftStyle = $isUnread ? 'border-left: 4px solid #005187 !important;' : 'border-left: 4px solid transparent;';
                                @endphp
                                
                                <div class="list-group-item list-group-item-action p-3 position-relative transition-all message-row-item {{ $unreadClass }}" 
                                     style="{{ $borderLeftStyle }} cursor: pointer;"
                                     data-id-msg="{{ $row->idmessage }}">
                                    
                                    <div class="row align-items-center g-3">
                                        <div class="col-12 col-md-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $fotoPath }}" width="38" height="38" class="rounded-circle me-2.5 border object-fit-cover shadow-sm" onerror="this.src='/backend/img/user-default.png'">
                                                <div class="text-truncate">
                                                    <span class="d-block text-dark text-truncate small-title">{{ $row->sender->username }}</span>
                                                    @if($isUnread)
                                                        <span class="badge bg-success" style="font-size: 9px;" id="badge-status-{{ $row->idmessage }}">Nuevo</span>
                                                    @else
                                                        <span class="badge bg-secondary" style="font-size: 9px;" id="badge-status-{{ $row->idmessage }}">Leído</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div class="text-truncate pe-md-4">
                                                <span class="text-dark d-block mb-0 text-truncate" style="font-size: 14.5px;">
                                                    {{ $row->subject }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-3 text-md-end d-flex justify-content-between align-items-center justify-content-md-end gap-3">
                                            <span class="text-muted d-inline-flex align-items-center small" style="font-size: 12px;">
                                                <i class="material-icons text-secondary fs-6 me-1">schedule</i>
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
                        <div class="alert alert-info border-0 m-4 d-flex align-items-center p-4 rounded-3" role="alert">
                            <i class="material-icons text-info me-3 fs-2">mail_outline</i>
                            <div>
                                <h5 class="fw-bold mb-1">¡Bandeja de entrada vacía!</h5>
                                <p class="mb-0 text-muted small">No posees correspondencia ni notificaciones internas registradas en tu historial.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL PARA VER DETALLES DEL MENSAJE --}}
    <div class="modal fade" id="infoMessageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header text-white" style="background-color: #005187;">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <i class="material-icons">email</i> 
                        <span style="font-size:15px; font-weight:400;" class="text-white-50">Asunto:</span> 
                        <span id="info_span_subject" class="fw-bold text-wrap"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 pb-3 border-bottom gap-3">
                        <div class="d-flex align-items-center">
                            <img id="info_img_sender" src="/backend/img/user-default.png" width="46" height="46" class="rounded-circle me-3 border shadow-sm object-fit-cover">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark" id="info_span_sender"></h6>
                                <small class="text-muted small">Remitente de la Institución</small>
                            </div>
                        </div>
                        <div class="text-sm-end">
                            <span class="badge bg-light text-secondary border px-2.5 py-1.5 rounded-2 d-inline-flex align-items-center gap-1" style="font-size:12px;">
                                <i class="material-icons text-secondary style" style="font-size:15px;">calendar_today</i> 
                                <span id="info_span_date"></span>
                            </span>
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

    {{-- MODAL PARA REDACTAR NUEVO MENSAJE --}}
    <div class="modal fade" id="newMessageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header text-white" style="background-color: #005187;">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <i class="material-icons">add_circle_outline</i> REDACTAR NUEVO MENSAJE
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form action="{{ route('messages.store') }}" method="POST" id="formNewMessage">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="receiver_id" class="form-label fw-bold text-secondary small">Destinatario:</label>
                                <select name="receiver_id" id="receiver_id" class="form-select" style="width: 100%;" required></select>
                            </div>

                            <div class="col-12">
                                <label for="subject" class="form-label fw-bold text-secondary small">Asunto:</label>
                                <input type="text" name="subject" id="subject" class="form-control border-secondary-subtle" placeholder="Escriba el motivo del mensaje..." required maxlength="150">
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label fw-bold text-secondary small">Cuerpo del Mensaje:</label>
                                <textarea name="message" id="message" class="form-control border-secondary-subtle" rows="6" placeholder="Escriba su mensaje con claridad aquí..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-2">
                        <button type="button" class="btn btn-outline-secondary rounded-2 px-3 small" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn text-white px-4 rounded-2 d-flex align-items-center gap-1 shadow-sm small" style="background-color: #005187;">
                            <i class="material-icons fs-5">send</i> Enviar Mensaje
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .message-unread {
            background-color: #f1f5f9 !important; /* Fondo suave para los no leídos */
        }
        .transition-all {
            transition: background-color 0.15s ease, transform 0.15s ease;
        }
        .message-row-item:hover {
            background-color: #f8fafc !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            const infoModal = new bootstrap.Modal('#infoMessageModal');

            // Habilitar la apertura del mensaje haciendo click en CUALQUIER sector de la fila
            $(document).on('click', '.message-row-item', function(e) {
                // Evitamos que se dispare doble si hacen click directo en el botón visibility
                if($(e.target).closest('.btn-show-message').length) return;
                
                const idmessage = $(this).data('id-msg');
                openMessageDetails(idmessage, $(this));
            });

            // Apertura mediante botón nativo visibility
            $('.btn-show-message').on('click', function() {
                const idmessage = $(this).data('id');
                const row = $(this).closest('.message-row-item');
                openMessageDetails(idmessage, row);
            });

            // Función Unificada de Carga AJAX
            function openMessageDetails(idmessage, rowElement) {
                $('#info_span_subject').text('Cargando asunto...');
                $('#info_span_sender').text('...');
                $('#info_span_date').text('...');
                $('#info_p_message').html('<div class="text-center py-3"><div class="spinner-border text-secondary" role="status"></div></div>');

                infoModal.show();

                $.get("{{ route('messages.showDetails') }}", { idmessage: idmessage }, function(response) {
                    if(response.success) {
                        $('#info_span_subject').text(response.subject);
                        $('#info_span_sender').text(response.sender_name);
                        $('#info_span_date').text(response.date);
                        $('#info_p_message').html(response.message);
                        
                        let fotoPath = response.sender_photo ? `/backend/img/subidas/${response.sender_photo}` : `/backend/img/user-default.png`;
                        $('#info_img_sender').attr('src', fotoPath);

                        // Quitar el estado de "No leído" de forma instantánea
                        rowElement.removeClass('message-unread fw-bold bg-body-tertiary').css('border-left', '4px solid transparent');
                        let badge = $(`#badge-status-${idmessage}`);
                        badge.removeClass('bg-success').addClass('bg-secondary').text('Leído');
                    }
                }).fail(function() {
                    $('#info_p_message').html('<span class="text-danger">Error al cargar el mensaje.</span>');
                });
            }

            // Inicializar Select2 al abrir el modal de redacción
            $('#newMessageModal').on('shown.bs.modal', function () {
                if ($('#receiver_id').hasClass("select2-hidden-accessible")) return;

                $('#receiver_id').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#newMessageModal'),
                    placeholder: 'Escribe el nombre, usuario o email...',
                    minimumInputLength: 1,
                    width: '100%',
                    language: {
                        inputTooShort: function () { return "Ingrese al menos 1 carácter"; },
                        noResults: function () { return "No se encontraron usuarios"; },
                        searching: function () { return "Buscando destinatario..."; }
                    },
                    ajax: {
                        url: '{{ route("messages.searchUsers") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) { return { term: params.term }; },
                        processResults: function (data) { return { results: data }; },
                        cache: true
                    }
                });
            });

            // Limpieza del formulario al cerrar el modal de redacción
            $('#newMessageModal').on('hidden.bs.modal', function () {
                $('#receiver_id').val(null).trigger('change');
                $('#subject').val('');
                $('#message').val('');
            });

            // Guardado e inyección asíncrona mediante SweetAlert2
            $('#formNewMessage').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const submitBtn = $form.find('button[type="submit"]');

                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Enviando...');

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function(response) {
                        if(response.success) {
                            bootstrap.Modal.getInstance(document.getElementById('newMessageModal')).hide();
                            $form[0].reset();
                            $('#receiver_id').val(null).trigger('change');

                            Swal.fire({
                                icon: 'success',
                                title: '¡Mensaje Enviado!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Envío',
                            text: 'Hubo un problema de red al intentar despachar el mensaje.'
                        });
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html('<i class="material-icons align-middle" style="font-size:18px;">send</i> Enviar Mensaje');
                    }
                });
            });
        });
    </script>
    @endpush
</x-layouts.app-layout>