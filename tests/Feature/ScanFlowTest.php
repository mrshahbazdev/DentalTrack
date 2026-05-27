<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\ScanEventType;
use App\Enums\StepStatus;
use App\Models\Company;
use App\Models\Lab;
use App\Models\Order;
use App\Models\OrderStep;
use App\Models\ProductType;
use App\Models\User;
use App\Models\Workstation;
use App\Services\ScanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ScanFlowTest extends TestCase
{
    use RefreshDatabase;

    private ScanService $scanService;

    private Company $company;

    private Lab $lab;

    private ProductType $productType;

    private User $technician;

    private Workstation $workstation;

    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->scanService = app(ScanService::class);

        $this->company = Company::create([
            'name' => 'Test Lab',
            'slug' => 'test-lab',
        ]);

        $this->lab = Lab::create([
            'company_id' => $this->company->id,
            'name' => 'Main Lab',
            'location' => 'Floor 1',
        ]);

        $this->productType = ProductType::create([
            'company_id' => $this->company->id,
            'name' => 'Full Denture',
        ]);

        $this->technician = User::create([
            'name' => 'Test Tech',
            'email' => 'tech@test.com',
            'password' => bcrypt('password'),
            'company_id' => $this->company->id,
            'role' => 'technician',
            'is_active' => true,
        ]);

        $this->workstation = Workstation::create([
            'lab_id' => $this->lab->id,
            'name' => 'Wax Modeling Bay',
            'qr_code' => 'WS-'.Str::ulid(),
            'type' => 'station',
        ]);

        $this->order = Order::create([
            'company_id' => $this->company->id,
            'lab_id' => $this->lab->id,
            'product_type_id' => $this->productType->id,
            'patient_ref' => 'PATIENT-001',
            'doctor_name' => 'Dr. Test',
            'priority' => 'normal',
            'status' => 'pending',
            'due_date' => now()->addDays(5),
        ]);

        OrderStep::create([
            'order_id' => $this->order->id,
            'sort_order' => 1,
            'step_name' => 'Wax Modeling',
            'status' => 'pending',
        ]);

        OrderStep::create([
            'order_id' => $this->order->id,
            'sort_order' => 2,
            'step_name' => 'Casting',
            'status' => 'pending',
        ]);
    }

    public function test_start_work_creates_scan_event(): void
    {
        $event = $this->scanService->startWork($this->order, $this->workstation, $this->technician);

        $this->assertEquals(ScanEventType::Start, $event->event_type);
        $this->assertEquals($this->order->id, $event->order_id);
        $this->assertEquals($this->workstation->id, $event->workstation_id);
        $this->assertEquals($this->technician->id, $event->user_id);
    }

    public function test_start_work_changes_order_to_in_progress(): void
    {
        $this->assertEquals(OrderStatus::Pending, $this->order->status);

        $this->scanService->startWork($this->order, $this->workstation, $this->technician);

        $this->order->refresh();
        $this->assertEquals(OrderStatus::InProgress, $this->order->status);
    }

    public function test_start_work_assigns_technician_to_step(): void
    {
        $this->scanService->startWork($this->order, $this->workstation, $this->technician);

        $step = $this->order->steps()->orderBy('sort_order')->first();
        $this->assertNotNull($step);
        $this->assertEquals(StepStatus::InProgress, $step->status);
        $this->assertEquals($this->technician->id, $step->assigned_to);
    }

    public function test_complete_work_marks_step_done(): void
    {
        $this->scanService->startWork($this->order, $this->workstation, $this->technician);
        $event = $this->scanService->completeWork($this->order, $this->workstation, $this->technician);

        $this->assertEquals(ScanEventType::Complete, $event->event_type);
        $this->assertNotNull($event->duration_seconds);

        $step = $this->order->steps()->where('sort_order', 1)->first();
        $this->assertNotNull($step);
        $this->assertEquals(StepStatus::Done, $step->status);
    }

    public function test_completing_all_steps_marks_order_completed(): void
    {
        $this->scanService->startWork($this->order, $this->workstation, $this->technician);
        $this->scanService->completeWork($this->order, $this->workstation, $this->technician);

        $this->scanService->startWork($this->order, $this->workstation, $this->technician);
        $this->scanService->completeWork($this->order, $this->workstation, $this->technician);

        $this->order->refresh();
        $this->assertEquals(OrderStatus::Completed, $this->order->status);
        $this->assertNotNull($this->order->completed_at);
    }

    public function test_pause_work_creates_pause_event(): void
    {
        $this->scanService->startWork($this->order, $this->workstation, $this->technician);
        $event = $this->scanService->pauseWork($this->order, $this->workstation, $this->technician);

        $this->assertEquals(ScanEventType::Pause, $event->event_type);
        $this->assertNotNull($event->duration_seconds);
    }

    public function test_transfer_to_waiting_creates_event(): void
    {
        $waitingArea = Workstation::create([
            'lab_id' => $this->lab->id,
            'name' => 'Waiting Area A',
            'qr_code' => 'WA-'.Str::ulid(),
            'type' => 'waiting_area',
        ]);

        $this->scanService->startWork($this->order, $this->workstation, $this->technician);
        $event = $this->scanService->transferToWaiting($this->order, $waitingArea, $this->technician);

        $this->assertEquals(ScanEventType::TransferToWaiting, $event->event_type);
        $this->assertEquals($waitingArea->id, $event->workstation_id);
    }

    public function test_cannot_start_work_at_different_station_while_active(): void
    {
        $otherStation = Workstation::create([
            'lab_id' => $this->lab->id,
            'name' => 'Casting Bay',
            'qr_code' => 'WS-'.Str::ulid(),
            'type' => 'station',
        ]);

        $this->scanService->startWork($this->order, $this->workstation, $this->technician);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('currently active at workstation');

        $this->scanService->startWork($this->order, $otherStation, $this->technician);
    }

    public function test_can_restart_at_same_station(): void
    {
        $this->scanService->startWork($this->order, $this->workstation, $this->technician);

        $event = $this->scanService->startWork($this->order, $this->workstation, $this->technician);

        $this->assertEquals(ScanEventType::Start, $event->event_type);
    }

    public function test_scan_event_records_notes(): void
    {
        $event = $this->scanService->startWork($this->order, $this->workstation, $this->technician, 'Test note');

        $this->assertEquals('Test note', $event->notes);
    }
}
