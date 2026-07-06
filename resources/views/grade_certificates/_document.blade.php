{{-- HU-13 (13.2): Formato del documento. Partial compartido por la vista previa y la versión imprimible. --}}
@php
    $months = [1=>'enero',2=>'febrero',3=>'marzo',4=>'abril',5=>'mayo',6=>'junio',7=>'julio',8=>'agosto',9=>'setiembre',10=>'octubre',11=>'noviembre',12=>'diciembre'];
    $issue  = \Carbon\Carbon::parse($certificate->issue_date);
    $issueText = $issue->day . ' de ' . $months[$issue->month] . ' de ' . $issue->year;
    $article    = $certificate->student->gender == 'F' ? 'la estudiante' : 'el estudiante';
    $identified = $certificate->student->gender == 'F' ? 'identificada' : 'identificado';
@endphp

<div class="certificate-sheet">
    <div class="certificate-header">
        <h2>I.E.P. "DIVINO SALVADOR"</h2>
        <p class="certificate-subtitle">"Educando con fe y sabiduría"</p>
        <p class="certificate-code">N° {{ $certificate->certificate_code }}</p>
    </div>

    <h1 class="certificate-title">CONSTANCIA DE NOTAS</h1>

    <div class="certificate-body">
        <p><strong>LA DIRECCIÓN DE LA I.E.P. "DIVINO SALVADOR"</strong></p>
        <p class="certificate-hace-constar"><strong>HACE CONSTAR:</strong></p>

        <p class="certificate-text">
            Que, {{ $article }} <strong>{{ mb_strtoupper($certificate->student->full_name) }}</strong>,
            {{ $identified }} con DNI N° <strong>{{ $certificate->student->dni }}</strong>,
            @if(!empty($academic['degree']))
                del grado <strong>{{ $academic['degree'] }}</strong>@if(!empty($academic['subgrade'])) — <strong>{{ $academic['subgrade'] }}</strong>@endif,
            @endif
            @if(!empty($academic['section']))
                sección <strong>"{{ $academic['section'] }}"</strong>,
            @endif
            registra las siguientes calificaciones en el
            <strong>{{ $report['period_name'] ?? ($certificate->period->period_name ?? 'periodo escolar') }}</strong>:
        </p>

        @if($report)
            <table class="grades-table">
                <thead>
                    <tr>
                        <th style="text-align:left;">Curso</th>
                        @foreach($report['evaluations'] as $evaluation)
                            <th>{{ $evaluation }}</th>
                        @endforeach
                        <th>Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['courses'] as $row)
                        <tr>
                            <td style="text-align:left;">{{ $row['course'] }}</td>
                            @foreach($report['evaluations'] as $evaluation)
                                <td>{{ isset($row['grades'][$evaluation]) ? number_format($row['grades'][$evaluation], 2) : '—' }}</td>
                            @endforeach
                            <td><strong>{{ $row['average'] !== null ? number_format($row['average'], 2) : '—' }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="{{ count($report['evaluations']) + 1 }}" style="text-align:right;"><strong>PROMEDIO GENERAL:</strong></td>
                        <td><strong>{{ $report['general_average'] !== null ? number_format($report['general_average'], 2) : '—' }}</strong></td>
                    </tr>
                </tfoot>
            </table>
            <p class="grades-scale">Calificaciones expresadas en escala vigesimal (0 – 20).</p>
        @else
            <p class="certificate-text"><em>No se encontraron calificaciones registradas para el periodo indicado.</em></p>
        @endif

        <p class="certificate-text">
            Se expide la presente constancia a solicitud de la parte interesada, para los fines de:
            <strong>{{ $certificate->purpose }}</strong>.
        </p>

        @if($certificate->observations)
            <p class="certificate-text">
                <strong>Observaciones:</strong> {{ $certificate->observations }}
            </p>
        @endif

        <p class="certificate-date">Emitido el {{ $issueText }}.</p>
    </div>

    <div class="certificate-signature">
        <div class="signature-line">
            <hr>
            <p><strong>DIRECCIÓN</strong></p>
            <p>I.E.P. "Divino Salvador"</p>
        </div>
    </div>

    <div class="certificate-footer">
        <small>
            Documento emitido por: {{ $certificate->issuer->username ?? 'Administración' }} |
            Código de verificación: {{ $certificate->certificate_code }}
            @if($certificate->status != 1) | <strong style="color:#c00;">DOCUMENTO ANULADO</strong> @endif
        </small>
    </div>
</div>

<style>
    .certificate-sheet {
        background: #fff;
        max-width: 800px;
        margin: 0 auto;
        padding: 55px 65px;
        border: 1px solid #ccc;
        font-family: 'Times New Roman', Times, serif;
        color: #000;
    }
    .certificate-header { text-align: center; border-bottom: 3px double #000; padding-bottom: 12px; }
    .certificate-header h2 { margin: 0; font-size: 22px; letter-spacing: 1px; }
    .certificate-subtitle { margin: 4px 0 0; font-style: italic; font-size: 13px; }
    .certificate-code { text-align: right; margin: 10px 0 0; font-weight: bold; font-size: 14px; }
    .certificate-title {
        text-align: center; font-size: 25px; letter-spacing: 4px;
        margin: 35px 0 25px; text-decoration: underline; font-weight: bold;
    }
    .certificate-body { font-size: 15px; line-height: 1.8; }
    .certificate-hace-constar { text-align: center; margin: 16px 0; letter-spacing: 2px; }
    .certificate-text { text-align: justify; text-indent: 40px; margin-bottom: 16px; }
    .grades-table {
        width: 100%; border-collapse: collapse; margin: 10px 0 6px;
        font-size: 14px;
    }
    .grades-table th, .grades-table td {
        border: 1px solid #000; padding: 6px 8px; text-align: center;
    }
    .grades-table thead th { background: #f0f0f0; }
    .grades-scale { font-size: 12px; font-style: italic; margin: 0 0 16px; }
    .certificate-date { text-align: right; margin-top: 25px; }
    .certificate-signature { margin-top: 60px; display: flex; justify-content: center; }
    .signature-line { text-align: center; width: 260px; }
    .signature-line hr { border: none; border-top: 1px solid #000; margin-bottom: 6px; }
    .signature-line p { margin: 0; font-size: 14px; }
    .certificate-footer { margin-top: 40px; text-align: center; border-top: 1px solid #999; padding-top: 8px; color: #444; }
</style>
