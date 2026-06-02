<!DOCTYPE html>
<html>
<head>
    <style>
        body { margin: 0; padding: 3mm; font-family: Arial, sans-serif; text-align: center; }
        .sticker { text-align: center; }
        .qr { margin-bottom: 3mm; }
        .qr img { width: 35mm; height: 35mm; }
        .station-name { font-weight: bold; font-size: 12pt; margin-top: 2mm; }
        .station-type { font-size: 8pt; color: #666; text-transform: uppercase; }
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
