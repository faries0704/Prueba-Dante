<x-layouts.app-layout title="Nuevo Bloque de Subgrados">
<div class="main-content">
    <div class="container-fluid">
        
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('subgrades.index') }}" class="text-decoration-none text-custom-blue">Subgrados Académicos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nuevo Bloque</li>
            </ol>
        </nav>

        <div class="card shadow-sm" style="min-height:485px">
            <div class="card-header bg-white border-bottom pt-4 pb-3">
                <h4 class="card-title mb-0 fw-bold">
                    <i class="material-icons align-middle text-custom-blue me-1">format_list_numbered</i> Asignación Múltiple de Subgrados
                </h4>
            </div>

            <div class="card-body p-4">
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('subgrades.store') }}" method="POST" autocomplete="off">
                    @csrf
                    
                    <h5 class="mb-3 fw-bold text-secondary">1. Seleccione el Grado Padre</h5>
                    <div class="row g-3 bg-light p-3 rounded mb-4 border">
                        <div class="col-md-6 col-lg-5">
                            <label class="form-label fw-bold">Nivel / Grado Académico <span class="text-danger">*</span></label>
                            <select class="form-select form-control border-custom-blue" name="iddegree" id="degreeSelect" required>
                                <option value="">Seleccione el nivel correspondiente...</option>
                                @foreach($degrees as $degree)
                                    <option value="{{ $degree->iddegree }}" data-name="{{ strtolower($degree->degree_name) }}">
                                        {{ $degree->semester->period->period_name ?? 'N/A' }} - {{ $degree->degree_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h5 class="mb-3 fw-bold text-secondary">2. Seleccione los Subgrados (Años / Grados)</h5>
                    
                    <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                        @php $tabIndex = 0; @endphp
                        @foreach($subgradosGrupos as $grupo => $subgrados)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $tabIndex == 0 ? 'active' : '' }} border" 
                                        id="pills-{{ Str::slug($grupo) }}-tab" 
                                        data-bs-toggle="pill" 
                                        data-bs-target="#pills-{{ Str::slug($grupo) }}" 
                                        type="button" role="tab" aria-selected="{{ $tabIndex == 0 ? 'true' : 'false' }}">
                                    {{ $grupo }}
                                </button>
                            </li>
                            @php $tabIndex++; @endphp
                        @endforeach
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        @php 
                            $tabIndex = 0; 
                            $globalIndex = 0; // Índice global para el arreglo del formulario
                        @endphp
                        
                        @foreach($subgradosGrupos as $grupo => $subgrados)
                            <div class="tab-pane fade {{ $tabIndex == 0 ? 'show active' : '' }}" 
                                 id="pills-{{ Str::slug($grupo) }}" role="tabpanel">
                                
                                <div class="row g-3">
                                    @foreach($subgrados as $subgrado)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <div class="card h-100 course-card inactive-card border" id="card_{{ $globalIndex }}">
                                            <div class="card-body p-3">
                                                
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="card-title mb-0 fw-bold text-dark"><i class="material-icons align-middle fs-6 me-1">school</i> Sugerencia</h6>
                                                    
                                                    <div class="form-check form-switch mb-0">
                                                        <input class="form-check-input subgrade-toggle fs-5" type="checkbox" role="switch" 
                                                               name="subgrades[{{ $globalIndex }}][selected]" value="1" 
                                                               id="toggle_{{ $globalIndex }}" data-index="{{ $globalIndex }}" style="cursor: pointer;">
                                                    </div>
                                                </div>

                                                <div>
                                                    <input type="text" class="form-control fw-bold text-center text-custom-blue" 
                                                           name="subgrades[{{ $globalIndex }}][name]" 
                                                           id="name_{{ $globalIndex }}" 
                                                           value="{{ $subgrado }}" 
                                                           readonly required style="background-color: transparent; border-style: dashed;">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    @php $globalIndex++; @endphp
                                    @endforeach
                                </div>
                            </div>
                            @php $tabIndex++; @endphp
                        @endforeach
                    </div>

                    <div class="mt-5 pt-3 border-top text-end">
                        <a href="{{ route('subgrades.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="material-icons align-middle fs-6">close</i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success px-4 text-white">
                            <i class="material-icons align-middle fs-6">save</i> Guardar Subgrados Seleccionados
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        
        // 1. Lógica para activar/desactivar el diseño de las tarjetas (Toggles)
        $('.subgrade-toggle').on('change', function() {
            let index = $(this).data('index');
            let card = $('#card_' + index);
            let inputName = $('#name_' + index);

            if ($(this).is(':checked')) {
                // Tarjeta Activa (Borde Verde, opacidad 100%)
                card.removeClass('inactive-card').addClass('active-card');
                inputName.removeAttr('readonly').css('border-style', 'solid'); 
            } else {
                // Tarjeta Inactiva (Fondo gris, opacidad reducida)
                card.removeClass('active-card').addClass('inactive-card');
                inputName.attr('readonly', true).css('border-style', 'dashed'); 
            }
        });

        // 2. Interactividad Extra: Cambiar pestaña automáticamente según el nivel seleccionado
        $('#degreeSelect').on('change', function() {
            let selectedName = $(this).find('option:selected').data('name');
            
            if(selectedName) {
                if(selectedName.includes('inicial')) {
                    $('#pills-inicial-tab').tab('show');
                } else if(selectedName.includes('primaria')) {
                    $('#pills-primaria-tab').tab('show');
                } else if(selectedName.includes('secundaria')) {
                    $('#pills-secundaria-tab').tab('show');
                }
            }
        });
    });
</script>
@endpush

</x-layouts.app-layout>