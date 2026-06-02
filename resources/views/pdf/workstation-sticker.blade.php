<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0; }
        html, body { margin: 0; padding: 0; }
        .sticker { text-align: center; padding: 3mm; }
        .qr img { width: 35mm; height: 35mm; }
        .station-name { font-weight: bold; font-size: 11pt; margin-top: 2mm; font-family: Arial, sans-serif; }
        .station-type { font-size: 8pt; color: #666; text-transform: uppercase; margin-top: 1mm; font-family: Arial, sans-serif; }
    </style>
</head>
<body>
    <div class="sticker">
        <div class="qr"><img src="{{ $qrImage }}" alt="QR"></div>
        <div class="station-name">{{ $workstation->name }}</div>
        <div class="station-type">{{ $workstation->type->label() }}</div>
    </div>
</body>
</html>
