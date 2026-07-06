<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- HU-13.6: Exportación en PDF. El navegador la exporta con Ctrl+P → "Guardar como PDF" --}}
    <title>{{ $certificate->certificate_code }} - Constancia de Notas</title>
    <style>
        body { margin: 0; background: #e9e9e9; }

        .print-toolbar {
            text-align: center;
            padding: 12px;
            background: #333;
        }
        .print-toolbar button {
            background: #28a745; color: #fff; border: none;
            padding: 10px 22px; font-size: 15px; border-radius: 4px; cursor: pointer;
        }
        .print-toolbar button:hover { background: #218838; }
        .print-toolbar a { color: #ddd; margin-left: 18px; font-size: 14px; }

        .sheet-wrapper { padding: 25px 10px; }

        @page { size: A4; margin: 0; }

        @media print {
            .print-toolbar { display: none; }
            body { background: #fff; }
            .sheet-wrapper { padding: 0; }
            .certificate-sheet { border: none !important; max-width: 100% !important; }
        }
    </style>
</head>
<body>
    <div class="print-toolbar">
        <button type="button" onclick="window.print()">Imprimir / Guardar como PDF</button>
        <a href="{{ route('gradeCertificates.index') }}">Volver al listado</a>
    </div>

    <div class="sheet-wrapper">
        @include('grade_certificates._document')
    </div>
</body>
</html>
