<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0; }
        html, body { margin: 0; padding: 0; }
        .sticker { text-align: center; padding: 2mm; font-family: Arial, sans-serif; }
        .qr img { width: 20mm; height: 20mm; }
        .order-num { font-weight: bold; font-size: 8pt; margin-top: 1mm; }
        .patient-ref { font-size: 7pt; color: #555; }
        .product-type { font-size: 7pt; color: #333; }
    </style>
</head>
<body>
    <div class="sticker">
        <div class="qr"><img src="{{ $qrImage }}" alt="QR"></div>
        <div class="order-num">{{ __('app.sticker.order') }} #{{ $order->id }}</div>
        @if($order->patient_ref)
            <div class="patient-ref">{{ $order->patient_ref }}</div>
        @endif
        <div class="product-type">{{ $order->productType->name }}</div>
    </div>
</body>
</html>
