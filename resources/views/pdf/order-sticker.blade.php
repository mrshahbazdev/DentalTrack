<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 1mm; font-family: Arial, sans-serif; font-size: 8pt; text-align: center; }
        .sticker { text-align: center; page-break-inside: avoid; }
        .qr { margin-bottom: 0.5mm; }
        .qr img { width: 12mm; height: 12mm; }
        .order-num { font-weight: bold; font-size: 6pt; line-height: 1.1; }
        .patient-ref { font-size: 5pt; color: #555; line-height: 1.1; }
        .product-type { font-size: 5pt; color: #333; line-height: 1.1; }
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
