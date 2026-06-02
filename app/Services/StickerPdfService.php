<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Workstation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StickerPdfService
{
    public function __construct(
        private readonly QrCodeService $qrCodeService,
    ) {}

    public function generateOrderSticker(Order $order): StreamedResponse
    {
        $order->load('productType');

        $qrImage = $this->qrCodeService->generateBase64Image($order->qrUrl(), 150);

        $pdf = Pdf::loadView('pdf.order-sticker', [
            'order' => $order,
            'qrImage' => $qrImage,
        ])->setPaper([0, 0, 70.87, 42.52], 'portrait'); // ~25x15mm

        $output = $pdf->output();

        return response()->streamDownload(
            fn () => print ($output),
            "order-{$order->id}-sticker.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }

    public function generateWorkstationSticker(Workstation $workstation): StreamedResponse
    {
        $qrImage = $this->qrCodeService->generateBase64Image($workstation->qrUrl(), 250);

        $pdf = Pdf::loadView('pdf.workstation-sticker', [
            'workstation' => $workstation,
            'qrImage' => $qrImage,
        ])->setPaper([0, 0, 141.73, 141.73], 'portrait'); // ~50x50mm

        $output = $pdf->output();

        return response()->streamDownload(
            fn () => print ($output),
            "workstation-{$workstation->id}-sticker.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * @param  Collection<int, Order>  $orders
     */
    public function generateBatchOrderStickers(Collection $orders): StreamedResponse
    {
        $orders->load('productType');

        $stickers = $orders->map(function (Order $order) {
            return [
                'order' => $order,
                'qrImage' => $this->qrCodeService->generateBase64Image($order->qrUrl(), 120),
            ];
        });

        $pdf = Pdf::loadView('pdf.batch-order-stickers', [
            'stickers' => $stickers,
        ])->setPaper('a4', 'portrait');

        $output = $pdf->output();

        return response()->streamDownload(
            fn () => print ($output),
            'batch-order-stickers.pdf',
            ['Content-Type' => 'application/pdf']
        );
    }
}
