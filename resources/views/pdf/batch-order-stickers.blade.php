<!DOCTYPE html>
<html>
<head>
    <style>
        body { margin: 0; padding: 5mm; font-family: Arial, sans-serif; }
        .grid { display: table; width: 100%; }
        .row { display: table-row; }
        .cell {
            display: table-cell;
            width: 33%;
            padding: 3mm;
            text-align: center;
            vertical-align: top;
            border: 0.5pt dashed #ccc;
        }
        .qr svg { width: 20mm; height: 20mm; }
        .order-num { font-weight: bold; font-size: 8pt; margin-top: 1mm; }
        .patient-ref { font-size: 6pt; color: #555; }
        .product-type { font-size: 6pt; color: #333; }
    </style>
</head>
<body>
    <div class="grid">
        @foreach($stickers->chunk(3) as $row)
            <div class="row">
                @foreach($row as $sticker)
                    <div class="cell">
                        <div class="qr">{!! $sticker['qrSvg'] !!}</div>
                        <div class="order-num">Order #{{ $sticker['order']->id }}</div>
                        @if($sticker['order']->patient_ref)
                            <div class="patient-ref">{{ $sticker['order']->patient_ref }}</div>
                        @endif
                        <div class="product-type">{{ $sticker['order']->productType->name }}</div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</body>
</html>
