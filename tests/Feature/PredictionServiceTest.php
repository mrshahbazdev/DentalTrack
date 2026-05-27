<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Lab;
use App\Models\Order;
use App\Models\OrderStep;
use App\Models\ProcessTemplate;
use App\Models\ProductType;
use App\Services\PredictionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PredictionServiceTest extends TestCase
{
    use RefreshDatabase;

    private PredictionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(PredictionService::class);
    }

    public function test_prediction_uses_template_estimates_when_no_history(): void
    {
        $company = Company::create(['name' => 'Test', 'slug' => 'test']);
        $lab = Lab::create(['company_id' => $company->id, 'name' => 'Lab', 'location' => 'A']);
        $productType = ProductType::create(['company_id' => $company->id, 'name' => 'Crown']);

        ProcessTemplate::create([
            'product_type_id' => $productType->id,
            'sort_order' => 1,
            'step_name' => 'Modeling',
            'expected_minutes' => 60,
        ]);

        ProcessTemplate::create([
            'product_type_id' => $productType->id,
            'sort_order' => 2,
            'step_name' => 'Casting',
            'expected_minutes' => 90,
        ]);

        $order = Order::create([
            'company_id' => $company->id,
            'lab_id' => $lab->id,
            'product_type_id' => $productType->id,
            'priority' => 'normal',
            'status' => 'pending',
        ]);

        OrderStep::create(['order_id' => $order->id, 'sort_order' => 1, 'step_name' => 'Modeling', 'status' => 'pending']);
        OrderStep::create(['order_id' => $order->id, 'sort_order' => 2, 'step_name' => 'Casting', 'status' => 'pending']);

        $result = $this->service->predictCompletion($order);

        $this->assertNotNull($result);
        $this->assertGreaterThan(0, $result['prediction']->predicted_minutes);
        $this->assertStringContainsString('template', $result['basis']);
    }

    public function test_prediction_returns_null_for_order_without_steps(): void
    {
        $company = Company::create(['name' => 'Test', 'slug' => 'test']);
        $lab = Lab::create(['company_id' => $company->id, 'name' => 'Lab', 'location' => 'A']);
        $productType = ProductType::create(['company_id' => $company->id, 'name' => 'Crown']);

        ProcessTemplate::create([
            'product_type_id' => $productType->id,
            'sort_order' => 1,
            'step_name' => 'Step',
            'expected_minutes' => 30,
        ]);

        $order = Order::create([
            'company_id' => $company->id,
            'lab_id' => $lab->id,
            'product_type_id' => $productType->id,
            'priority' => 'normal',
            'status' => 'pending',
        ]);

        $result = $this->service->predictCompletion($order);

        $this->assertNull($result);
    }

    public function test_prediction_sets_predicted_completion_at(): void
    {
        $company = Company::create(['name' => 'Test', 'slug' => 'test']);
        $lab = Lab::create(['company_id' => $company->id, 'name' => 'Lab', 'location' => 'A']);
        $productType = ProductType::create(['company_id' => $company->id, 'name' => 'Crown']);

        ProcessTemplate::create([
            'product_type_id' => $productType->id,
            'sort_order' => 1,
            'step_name' => 'Modeling',
            'expected_minutes' => 60,
        ]);

        $order = Order::create([
            'company_id' => $company->id,
            'lab_id' => $lab->id,
            'product_type_id' => $productType->id,
            'priority' => 'normal',
            'status' => 'pending',
        ]);

        OrderStep::create(['order_id' => $order->id, 'sort_order' => 1, 'step_name' => 'Modeling', 'status' => 'pending']);

        $this->service->predictCompletion($order);

        $order->refresh();
        $this->assertNotNull($order->predicted_completion_at);
    }
}
