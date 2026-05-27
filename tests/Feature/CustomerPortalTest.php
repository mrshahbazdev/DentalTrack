<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Lab;
use App\Models\Order;
use App\Models\ProductType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_page_loads(): void
    {
        $response = $this->get('/track');

        $response->assertStatus(200);
    }

    public function test_tracking_page_supports_locale(): void
    {
        $response = $this->get('/track?lang=ur');

        $response->assertStatus(200);
    }

    public function test_order_has_tracking_code(): void
    {
        $company = Company::create(['name' => 'Test', 'slug' => 'test']);
        $lab = Lab::create(['company_id' => $company->id, 'name' => 'Lab', 'location' => 'A']);
        $productType = ProductType::create(['company_id' => $company->id, 'name' => 'Crown']);

        $order = Order::create([
            'company_id' => $company->id,
            'lab_id' => $lab->id,
            'product_type_id' => $productType->id,
            'priority' => 'normal',
            'status' => 'pending',
        ]);

        $this->assertNotNull($order->tracking_code);
        $this->assertEquals(8, strlen($order->tracking_code));
    }
}
