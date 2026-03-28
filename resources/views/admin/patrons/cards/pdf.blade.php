<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thẻ Độc Giả</title>
    <style>
        @page {
            size: {{ $layout == 'single' ? '85.6mm 54mm' : 'A4' }};
            margin: 0;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        
        .card {
            width: 85.6mm;
            height: 54mm;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 3mm;
            color: white;
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(45deg);
        }
        
        .card-header {
            text-align: center;
            margin-bottom: 2mm;
            position: relative;
            z-index: 1;
        }
        
        .library-logo {
            width: 8mm;
            height: 8mm;
            background: white;
            border-radius: 50%;
            margin: 0 auto 1mm;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #667eea;
            font-size: 4mm;
        }
        
        .card-title {
            font-size: 3mm;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        
        .card-body {
            display: flex;
            gap: 3mm;
            position: relative;
            z-index: 1;
        }
        
        .photo-section {
            width: 18mm;
            height: 24mm;
            background: white;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 8mm;
        }
        
        .info-section {
            flex: 1;
            font-size: 2.5mm;
            line-height: 1.3;
        }
        
        .info-row {
            margin-bottom: 1mm;
            display: flex;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            margin-right: 1mm;
            min-width: 15mm;
        }
        
        .info-value {
            font-weight: normal;
        }
        
        .barcode-section {
            margin-top: 2mm;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        
        .barcode {
            height: 8mm;
            margin-bottom: 1mm;
        }
        
        .barcode-text {
            font-size: 2mm;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }
        
        .card-footer {
            margin-top: 2mm;
            text-align: center;
            font-size: 2mm;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .expiry-date {
            font-weight: bold;
        }
        
        /* Batch layout */
        .batch-container {
            padding: 10mm;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5mm;
        }
        
        .batch-card {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        
        /* Single layout */
        .single-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    @if($layout == 'batch')
        <div class="batch-container">
            @foreach($patrons as $patron)
                <div class="batch-card">
                    @include('admin.patrons.cards.single-card', ['patron' => $patron])
                </div>
            @endforeach
        </div>
    @else
        <div class="single-container">
            @foreach($patrons as $patron)
                <div style="page-break-after: always;">
                    @include('admin.patrons.cards.single-card', ['patron' => $patron])
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>
