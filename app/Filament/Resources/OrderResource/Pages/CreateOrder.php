<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\ProcessTemplate;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        /** @var Order $order */
        $order = $this->record;

        $templates = ProcessTemplate::where('product_type_id', $order->product_type_id)
            ->orderBy('sort_order')
            ->get();

        foreach ($templates as $template) {
            $order->steps()->create([
                'process_template_id' => $template->id,
                'sort_order' => $template->sort_order,
                'step_name' => $template->step_name,
                'status' => 'pending',
            ]);
        }
    }
}
