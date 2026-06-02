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

    /**
     * Generate a base64-encoded image tag suitable for DomPDF rendering.
     * Uses PNG via Imagick if available, otherwise falls back to base64 SVG data URI.
     */
    public function generateBase64Image(string $url, int $size = 200): string
    {
        if (extension_loaded('imagick')) {
            $png = QrCode::format('png')
                ->size($size)
                ->errorCorrection('H')
                ->generate($url);

            return 'data:image/png;base64,' . base64_encode((string) $png);
        }

        $svg = $this->generateSvg($url, $size);

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
