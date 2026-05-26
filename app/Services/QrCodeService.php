<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Workstation;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    public function generateForOrder(Order $order): string
    {
        $result = QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->generate($order->qrUrl());

        return (string) $result;
    }

    public function generateForWorkstation(Workstation $workstation): string
    {
        $result = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($workstation->qrUrl());

        return (string) $result;
    }

    public function generateSvg(string $url, int $size = 200): string
    {
        $result = QrCode::format('svg')
            ->size($size)
            ->errorCorrection('H')
            ->generate($url);

        return (string) $result;
    }
}
