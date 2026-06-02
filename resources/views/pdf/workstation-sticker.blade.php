<!DOCTYPE html>
<html>
<head>
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 2mm; font-family: Arial, sans-serif; text-align: center; }
        .sticker { text-align: center; page-break-inside: avoid; }
        .qr { margin-bottom: 1mm; }
        .qr img { width: 30mm; height: 30mm; }
        .station-name { font-weight: bold; font-size: 10pt; line-height: 1.1; }
        .station-type { font-size: 7pt; color: #666; text-transform: uppercase; margin-top: 1mm; }
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
