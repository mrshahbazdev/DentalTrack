<?php

namespace App\Livewire;

use App\Enums\WorkstationType;
use App\Models\Order;
use App\Models\User;
use App\Models\Workstation;
use App\Services\ScanService;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QrScanner extends Component
{
    public string $step = 'scan_workstation';

    public ?int $workstationId = null;

    public ?int $orderId = null;

    public string $workstationName = '';

    public string $orderInfo = '';

    public string $currentStepName = '';

    public string $notes = '';

    public string $errorMessage = '';

    public string $successMessage = '';

    public function mount(?string $uuid = null): void
    {
        if ($uuid === null) {
            return;
        }

        $workstation = Workstation::where('qr_code', $uuid)->first();
        if ($workstation !== null) {
            if (! $workstation->is_active) {
                $this->errorMessage = 'This workstation is not active.';

                return;
            }
            $this->workstationId = $workstation->id;
            $this->workstationName = $workstation->name;
            $this->step = 'scan_order';

            return;
        }

        $order = Order::where('qr_code', $uuid)->with(['productType'])->first();
        if ($order !== null) {
            $this->orderId = $order->id;
            $productName = $order->productType->name ?? '';
            $this->orderInfo = "Order #{$order->id} — {$productName}";
            $currentStep = $order->currentStep();
            $this->currentStepName = $currentStep !== null ? $currentStep->step_name : 'All steps completed';
            $this->step = 'confirm_action';

            return;
        }

        $this->errorMessage = 'QR code not recognized.';
    }

    public function processQrCode(string $qrData): void
    {
        $this->errorMessage = '';
        $this->successMessage = '';

        $uuid = $this->extractUuidFromUrl($qrData);

        if ($uuid === null) {
            $this->errorMessage = 'Invalid QR code format.';

            return;
        }

        if ($this->step === 'scan_workstation') {
            $this->handleWorkstationScan($uuid);
        } elseif ($this->step === 'scan_order') {
            $this->handleOrderScan($uuid);
        } elseif ($this->step === 'scan_next_station') {
            $this->handleNextStationScan($uuid);
        }
    }

    public function performAction(string $action): void
    {
        $this->errorMessage = '';
        $this->successMessage = '';

        $order = Order::find($this->orderId);
        $workstation = Workstation::find($this->workstationId);

        if ($order === null || $workstation === null) {
            $this->errorMessage = 'Order or workstation not found.';

            return;
        }

        /** @var User|null $user */
        $user = auth()->user();

        if ($user === null) {
            $this->errorMessage = 'You must be logged in to perform this action.';

            return;
        }

        $scanService = app(ScanService::class);
        $notes = $this->notes !== '' ? $this->notes : null;

        try {
            if ($action === 'start') {
                $scanService->startWork($order, $workstation, $user, $notes);
                $this->successMessage = 'Work started on order #'.$order->id;
                $this->resetScanState();
            } elseif ($action === 'pause') {
                $scanService->pauseWork($order, $workstation, $user, $notes);
                $this->successMessage = 'Work paused on order #'.$order->id;
                $this->resetScanState();
            } elseif ($action === 'complete') {
                $this->handleComplete($order, $workstation, $user);
            } else {
                throw new \InvalidArgumentException("Unknown action: {$action}");
            }
        } catch (\RuntimeException $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function resetScanState(): void
    {
        $this->step = 'scan_workstation';
        $this->workstationId = null;
        $this->orderId = null;
        $this->workstationName = '';
        $this->orderInfo = '';
        $this->currentStepName = '';
        $this->notes = '';
    }

    public function render(): View
    {
        return view('livewire.qr-scanner');
    }

    private function extractUuidFromUrl(string $qrData): ?string
    {
        if (preg_match('/\/scan\/([A-Za-z0-9_-]+)$/', $qrData, $matches)) {
            return $matches[1];
        }

        if (str_starts_with($qrData, 'WS-') || str_starts_with($qrData, 'ORD-') || str_starts_with($qrData, 'WA-')) {
            return $qrData;
        }

        return null;
    }

    private function handleWorkstationScan(string $uuid): void
    {
        $workstation = Workstation::where('qr_code', $uuid)->first();

        if ($workstation === null) {
            $this->errorMessage = 'Workstation not found.';

            return;
        }

        if (! $workstation->is_active) {
            $this->errorMessage = 'This workstation is not active.';

            return;
        }

        $this->workstationId = $workstation->id;
        $this->workstationName = $workstation->name;
        $this->step = 'scan_order';
    }

    private function handleOrderScan(string $uuid): void
    {
        $order = Order::where('qr_code', $uuid)->with(['productType'])->first();

        if ($order === null) {
            $this->errorMessage = 'Order not found.';

            return;
        }

        $this->orderId = $order->id;
        $productName = $order->productType->name ?? '';
        $this->orderInfo = "Order #{$order->id} — {$productName}";

        $currentStep = $order->currentStep();
        $this->currentStepName = $currentStep !== null ? $currentStep->step_name : 'All steps completed';

        $this->step = 'confirm_action';
    }

    private function handleNextStationScan(string $uuid): void
    {
        $workstation = Workstation::where('qr_code', $uuid)->first();

        if ($workstation === null) {
            $this->errorMessage = 'Workstation/waiting area not found.';

            return;
        }

        $order = Order::find($this->orderId);
        $user = auth()->user();

        if ($order === null || $user === null) {
            $this->errorMessage = 'Order or user not found.';

            return;
        }

        $scanService = app(ScanService::class);

        if ($workstation->type === WorkstationType::WaitingArea) {
            $scanService->transferToWaiting($order, $workstation, $user, $this->notes ?: null);
            $this->successMessage = "Order #{$order->id} transferred to waiting area: {$workstation->name}";
        } else {
            $scanService->startWork($order, $workstation, $user, $this->notes ?: null);
            $this->successMessage = "Order #{$order->id} moved to {$workstation->name}";
        }

        $this->resetScanState();
    }

    private function handleComplete(Order $order, Workstation $workstation, User $user): void
    {
        $scanService = app(ScanService::class);
        $scanService->completeWork($order, $workstation, $user, $this->notes ?: null);
        $this->successMessage = "Step completed for order #{$order->id}. Scan next workstation or waiting area.";
        $this->step = 'scan_next_station';
    }
}
