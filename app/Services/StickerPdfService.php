<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Workstation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class StickerPdfService
{
    public function __construct(
        private readonly QrCodeService $qrCodeService,
    ) {}

    public function generateOrderSticker(Order $order): Response
    {
        $order->load('productType');

        $qrSvg = $this->qrCodeService->generateSvg($order->qrUrl(), 150);

        $pdf = Pdf::loadView('pdf.order-sticker', [
            'order' => $order,
            'qrSvg' => $qrSvg,
        ])->setPaper([0, 0, 70.87, 42.52], 'portrait'); // ~25x15mm

        return $pdf->download("order-{$order->id}-sticker.pdf");
    }

    public function generateWorkstationSticker(Workstation $workstation): Response
    {
        $qrSvg = $this->qrCodeService->generateSvg($workstation->qrUrl(), 250);

        $pdf = Pdf::loadView('pdf.workstation-sticker', [
            'workstation' => $workstation,
            'qrSvg' => $qrSvg,
        ])->setPaper([0, 0, 141.73, 141.73], 'portrait'); // ~50x50mm

        return $pdf->download("workstation-{$workstation->id}-sticker.pdf");
    }

    /**
     * @param  Collection<int, Order>  $orders
     */
    public function generateBatchOrderStickers(Collection $orders): Response
    {
        $orders->load('productType');

        $stickers = $orders->map(function (Order $order) {
            return [
                'order' => $order,
                'qrSvg' => $this->qrCodeService->generateSvg($order->qrUrl(), 120),
            ];
        });

        $pdf = Pdf::loadView('pdf.batch-order-stickers', [
            'stickers' => $stickers,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('batch-order-stickers.pdf');
    }
}
