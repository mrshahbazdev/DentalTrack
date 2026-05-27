<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CustomerPortal extends Component
{
    public string $trackingCode = '';

    public ?Order $order = null;

    public bool $searched = false;

    public string $errorMessage = '';

    public function search(): void
    {
        $this->searched = true;
        $this->errorMessage = '';
        $this->order = null;

        if (strlen($this->trackingCode) < 3) {
            $this->errorMessage = 'Please enter a valid tracking code.';

            return;
        }

        $order = Order::where('tracking_code', $this->trackingCode)
            ->with(['productType', 'steps', 'lab'])
            ->first();

        if ($order === null) {
            $this->errorMessage = 'No order found with this tracking code.';

            return;
        }

        $this->order = $order;
    }

    /** @return View */
    public function render()
    {
        return view('livewire.customer-portal')
            ->layout('components.layouts.portal');
    }
}
