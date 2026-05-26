<!DOCTYPE html>
<html>
<head>
    <style>
        body { margin: 0; padding: 2mm; font-family: Arial, sans-serif; font-size: 8pt; }
        .sticker { text-align: center; }
        .qr { margin-bottom: 1mm; }
        .qr svg { width: 15mm; height: 15mm; }
        .order-num { font-weight: bold; font-size: 7pt; }
        .patient-ref { font-size: 6pt; color: #555; }
        .product-type { font-size: 6pt; color: #333; }
    </style>
</head>
<body>
    <div class="sticker">
        <div class="qr">{!! $qrSvg !!}</div>
        <div class="order-num">Order #{{ $order->id }}</div>
        @if($order->patient_ref)
            <div class="patient-ref">{{ $order->patient_ref }}</div>
        @endif
        <div class="product-type">{{ $order->productType->name }}</div>
    </div>
</body>
</html>
