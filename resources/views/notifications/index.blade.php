<x-layouts.app-layout title="Mis Notificaciones">
    <div class="main-content">
        <div class="container-fluid">
            
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Notificaciones</li>
                </ol>
            </nav>

            <div class="mb-4">
                <h3 class="fw-bold mb-1" style="color: #005187;">
                    <i class="material-icons align-middle fs-3 me-1">notifications</i> Mis Notificaciones
                </h3>
                <p class="text-muted small mb-0">Mantente al tanto de tus actualizaciones de mensajería, calificaciones, asistencias y sistema.</p>
            </div>

            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush" id="notificationsListContainer">
                            @foreach($notifications as $row)
                                @php
                                    // Configuración de iconos y estilos por tipo
                                    $badgeClass = 'bg-secondary-subtle text-secondary border-secondary-subtle';
                                    $icon = 'notifications';
                                    
                                    if($row->type == 'MESSAGE') { $badgeClass = 'bg-info-subtle text-info border-info-subtle'; $icon = 'email'; }
                                    if($row->type == 'GRADE') { $badgeClass = 'bg-warning-subtle text-warning-emphasis border-warning-subtle'; $icon = 'star'; }
                                    if($row->type == 'ATTENDANCE') { $badgeClass = 'bg-success-subtle text-success border-success-subtle'; $icon = 'fact_check'; }
                                    if($row->type == 'ENROLLMENT') { $badgeClass = 'bg-primary-subtle text-primary border-primary-subtle'; $icon = 'person_add'; }
                                    if($row->type == 'SYSTEM') { $badgeClass = 'bg-secondary-subtle text-secondary border-secondary-subtle'; $icon = 'settings'; }
                                    
                                    // Estilo dinámico si no está leída (Nueva) para dar "aire" y distinción visual
                                    $isRead = $row->is_read;
                                    $rowStyle = $isRead ? 'border-start: 4px solid transparent;' : 'background-color: #f4f9fd; border-start: 4px solid #005187;';
                                    $titleClass = $isRead ? 'text-secondary' : 'fw-bold text-dark';
                                @endphp
                                
                                <div class="list-group-item list-group-item-action p-3.5 notification-row-item transition-all" 
                                     style="cursor: pointer; {{ $rowStyle }}"
                                     data-id-notif="{{ $row->idnotification }}">
                                    
                                    <div class="row align-items-center g-3">
                                        <div class="col-12 col-md-2.5 col-lg-2">
                                            <span class="badge border py-1.5 px-2.5 d-inline-flex align-items-center gap-1 {{ $badgeClass }}" style="font-size: 11px; letter-spacing: 0.3px;">
                                                <i class="material-icons" style="font-size: 14px;">{{ $icon }}</i> 
                                                {{ $row->type }}
                                            </span>
                                        </div>

                                        <div class="col-12 col-md-6 col-lg-7">
                                            <div class="text-truncate-2">
                                                <span class="{{ $titleClass }}" style="font-size: 14.5px;">
                                                    {{ $row->title }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-3.5 col-lg-3 text-md-end d-flex justify-content-between align-items-center justify-content-md-end gap-3">
                                            <div class="text-md-end">
                                                <span class="text-muted d-block small mb-1" style="font-size: 12px;">
                                                    <i class="material-icons text-muted align-middle" style="font-size:14px;">schedule</i>
                                                    {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}
                                                </span>
                                                
                                                <span class="badge rounded-pill {{ $isRead ? 'bg-light text-muted border' : 'bg-success text-white' }} p-status-badge" 
                                                      id="badge-status-{{ $row->idnotification }}" 
                                                      style="font-size: 10px; font-weight: 500;">
                                                    {{ $isRead ? 'Leída' : 'Nueva' }}
                                                </span>
                                            </div>

                                            <button type="button" class="btn btn-light btn-sm btn-show-notification shadow-sm border rounded-2 p-2 d-flex align-items-center" data-id="{{ $row->idnotification }}">
                                                <i class="material-icons text-info fs-5">visibility</i>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 px-4">
                            <i class="material-icons text-muted mb-3" style="font-size: 56px;">notifications_off</i>
                            <h5 class="fw-bold text-dark mb-1">¡Bandeja al día!</h5>
                            <p class="text-muted small mb-0">No tienes alertas pendientes de revisión en este periodo.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL PARA VER DETALLES DE LA NOTIFICACIÓN --}}
    <div class="modal fade" id="infoNotificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header text-white" id="modal_header_color" style="background-color: #005187; transition: background-color 0.3s ease;">
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2 text-wrap pe-3">
                        <i class="material-icons" id="info_icon">notifications</i> 
                        <span id="info_span_title">Cargando...</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                        <div>
                            <span class="badge bg-white text-dark border px-2.5 py-1.5 shadow-sm fw-semibold" id="info_span_type" style="font-size:11px;"></span>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small d-inline-flex align-items-center gap-1" style="font-size:12px;">
                                <i class="material-icons text-secondary" style="font-size: 16px;">schedule</i> 
                                <span id="info_span_date"></span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-3 bg-light rounded-3 border border-light-subtle">
                        <div id="info_p_description" class="text-dark" style="font-size: 14.5px; line-height: 1.6; white-space: pre-line;"></div>
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
        .p-3.5 { padding: 0.95rem 1.25rem !important; }
        .transition-all { transition: all 0.2s ease-in-out; }
        .notification-row-item:hover {
            background-color: #f8fafc !important;
            transform: translateX(2px);
        }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
        .p-status-badge {
            letter-spacing: 0.2px;
            padding: 0.25em 0.6em !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        $(document).ready(function() {
            const infoModal = new bootstrap.Modal('#infoNotificationModal');

            // Clic en toda el área de la notificación para mayor usabilidad
            $(document).on('click', '.notification-row-item', function(e) {
                if($(e.target).closest('.btn-show-notification').length) return; // Evita duplicar llamada si se clica el botón directamente
                const idnotification = $(this).data('id-notif');
                openNotification(idnotification, $(this));
            });

            // Clic en el botón nativo de ojo
            $('.btn-show-notification').on('click', function() {
                const idnotification = $(this).data('id');
                const row = $(this).closest('.notification-row-item');
                openNotification(idnotification, row);
            });

            // Función unificada para consultar detalles vía AJAX
            function openNotification(idnotification, row) {
                // Limpiar modal e indicar carga
                $('#info_span_title').text('Cargando alerta...');
                $('#info_span_type').text('...');
                $('#info_span_date').text('...');
                $('#info_p_description').html('<div class="text-center py-4"><div class="spinner-border text-secondary" role="status"></div></div>');

                infoModal.show();

                // Petición AJAX
                $.get("{{ route('notifications.showDetails') }}", { 
                    idnotification: idnotification 
                }, function(response) {
                    if(response.success) {
                        // Llenar datos reales en el modal
                        $('#info_span_title').text(response.title);
                        $('#info_span_type').text(response.type);
                        $('#info_span_date').text(response.date);
                        $('#info_p_description').html(response.description);
                        
                        // Personalizar cabecera del modal dinámicamente según el tipo de alerta devuelta
                        $('#modal_header_color').css('background-color', response.color);
                        $('#info_icon').text(response.icon);

                        // Actualización asíncrona de estilos en la lista (Quitar estado de "Nueva")
                        row.attr('style', 'border-start: 4px solid transparent; transition: all 0.2s;');
                        row.find('.text-truncate-2 span').removeClass('fw-bold text-dark').addClass('text-secondary');
                        
                        let badge = row.find(`#badge-status-${idnotification}`);
                        badge.removeClass('bg-success text-white')
                             .addClass('bg-light text-muted border')
                             .text('Leída');
                    }
                }).fail(function() {
                    $('#info_p_description').html('<span class="text-danger">Error crítico al consultar el servidor.</span>');
                });
            }
        });
    </script>
    @endpush
</x-layouts.app-layout>