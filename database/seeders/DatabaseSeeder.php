<?php

namespace Database\Seeders;

use App\Enums\OrderPriority;
use App\Enums\OrderStatus;
use App\Enums\ScanEventType;
use App\Enums\StepStatus;
use App\Enums\WorkstationType;
use App\Models\Company;
use App\Models\Lab;
use App\Models\Order;
use App\Models\OrderStep;
use App\Models\ProcessTemplate;
use App\Models\ProductType;
use App\Models\ScanEvent;
use App\Models\User;
use App\Models\Workstation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->createRoles();
        $companies = $this->createCompanies();
        $this->createLabsAndWorkstations($companies);
        $this->createProductTypes($companies);
        $this->createUsers($companies);
        $this->createOrders($companies);
    }

    private function createRoles(): void
    {
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'company_admin']);
        Role::create(['name' => 'lab_manager']);
        Role::create(['name' => 'technician']);
    }

    /**
     * @return array<int, Company>
     */
    private function createCompanies(): array
    {
        return [
            Company::create([
                'name' => 'PrecisionDent Labs',
                'slug' => 'precisiondent',
                'address' => '123 Dental Ave, Lahore, Pakistan',
                'settings' => ['idle_timeout_hours' => 8, 'strict_process_order' => false],
            ]),
            Company::create([
                'name' => 'SmileCraft Dental',
                'slug' => 'smilecraft',
                'address' => '456 Medical Plaza, Karachi, Pakistan',
                'settings' => ['idle_timeout_hours' => 6, 'strict_process_order' => true],
            ]),
        ];
    }

    /**
     * @param  array<int, Company>  $companies
     */
    private function createLabsAndWorkstations(array $companies): void
    {
        $stationNames = [
            'Impression Bay',
            'Model Pouring Station',
            'Wax Modeling Bay',
            'Casting Station',
            'Metal Finishing',
            'Porcelain Application',
            'Glazing Oven',
            'Polishing Station',
            'Quality Control',
        ];

        foreach ($companies as $company) {
            $lab = Lab::create([
                'company_id' => $company->id,
                'name' => $company->name.' - Main Lab',
                'location' => 'Ground Floor',
            ]);

            foreach (array_slice($stationNames, 0, 5) as $name) {
                Workstation::create([
                    'lab_id' => $lab->id,
                    'name' => $name,
                    'qr_code' => 'WS-'.Str::ulid(),
                    'type' => WorkstationType::Station,
                ]);
            }

            Workstation::create([
                'lab_id' => $lab->id,
                'name' => 'Waiting Area A',
                'qr_code' => 'WA-'.Str::ulid(),
                'type' => WorkstationType::WaitingArea,
            ]);

            Workstation::create([
                'lab_id' => $lab->id,
                'name' => 'Waiting Area B',
                'qr_code' => 'WA-'.Str::ulid(),
                'type' => WorkstationType::WaitingArea,
            ]);
        }
    }

    /**
     * @param  array<int, Company>  $companies
     */
    private function createProductTypes(array $companies): void
    {
        $productTemplates = [
            'Full Denture' => [
                ['step_name' => 'Impression Taking', 'expected_minutes' => 30],
                ['step_name' => 'Model Pouring', 'expected_minutes' => 45],
                ['step_name' => 'Base Plate & Wax Rim', 'expected_minutes' => 60],
                ['step_name' => 'Jaw Registration', 'expected_minutes' => 30],
                ['step_name' => 'Teeth Setting', 'expected_minutes' => 90],
                ['step_name' => 'Wax Try-In', 'expected_minutes' => 30],
                ['step_name' => 'Processing (Flasking)', 'expected_minutes' => 120],
                ['step_name' => 'Deflasking & Trimming', 'expected_minutes' => 45],
                ['step_name' => 'Polishing', 'expected_minutes' => 30],
                ['step_name' => 'Quality Check', 'expected_minutes' => 15],
            ],
            'Crown (PFM)' => [
                ['step_name' => 'Die Preparation', 'expected_minutes' => 30],
                ['step_name' => 'Wax Pattern', 'expected_minutes' => 45],
                ['step_name' => 'Sprueing & Investing', 'expected_minutes' => 30],
                ['step_name' => 'Casting', 'expected_minutes' => 60],
                ['step_name' => 'Metal Finishing', 'expected_minutes' => 30],
                ['step_name' => 'Porcelain Build-Up', 'expected_minutes' => 90],
                ['step_name' => 'Glazing', 'expected_minutes' => 30],
                ['step_name' => 'Quality Check', 'expected_minutes' => 15],
            ],
            'Bridge (3-Unit)' => [
                ['step_name' => 'Die Preparation', 'expected_minutes' => 45],
                ['step_name' => 'Wax Pattern', 'expected_minutes' => 60],
                ['step_name' => 'Casting', 'expected_minutes' => 75],
                ['step_name' => 'Metal Try-In', 'expected_minutes' => 30],
                ['step_name' => 'Porcelain Application', 'expected_minutes' => 120],
                ['step_name' => 'Glazing & Finishing', 'expected_minutes' => 45],
                ['step_name' => 'Quality Check', 'expected_minutes' => 15],
            ],
            'Partial Denture (Acrylic)' => [
                ['step_name' => 'Impression & Model', 'expected_minutes' => 40],
                ['step_name' => 'Design & Survey', 'expected_minutes' => 30],
                ['step_name' => 'Teeth Setting', 'expected_minutes' => 60],
                ['step_name' => 'Wax Try-In', 'expected_minutes' => 30],
                ['step_name' => 'Processing', 'expected_minutes' => 90],
                ['step_name' => 'Finishing & Polishing', 'expected_minutes' => 45],
                ['step_name' => 'Quality Check', 'expected_minutes' => 15],
            ],
        ];

        foreach ($companies as $company) {
            foreach ($productTemplates as $productName => $steps) {
                $productType = ProductType::create([
                    'company_id' => $company->id,
                    'name' => $productName,
                ]);

                foreach ($steps as $index => $step) {
                    ProcessTemplate::create([
                        'product_type_id' => $productType->id,
                        'sort_order' => $index + 1,
                        'step_name' => $step['step_name'],
                        'expected_minutes' => $step['expected_minutes'],
                    ]);
                }
            }
        }
    }

    /**
     * @param  array<int, Company>  $companies
     */
    private function createUsers(array $companies): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@dentaltrack.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
        $superAdmin->assignRole('super_admin');

        $technicianNames = [
            ['Ahmed Khan', 'ahmed@dentaltrack.com', '1234'],
            ['Sara Malik', 'sara@dentaltrack.com', '2345'],
            ['Usman Ali', 'usman@dentaltrack.com', '3456'],
            ['Fatima Noor', 'fatima@dentaltrack.com', '4567'],
            ['Hassan Raza', 'hassan@dentaltrack.com', '5678'],
            ['Ayesha Siddiqui', 'ayesha@dentaltrack.com', '6789'],
            ['Bilal Ahmed', 'bilal@dentaltrack.com', '7890'],
            ['Zainab Shah', 'zainab@dentaltrack.com', '8901'],
        ];

        foreach ($companies as $index => $company) {
            $companyAdmin = User::create([
                'company_id' => $company->id,
                'name' => "Admin {$company->name}",
                'email' => "admin{$index}@{$company->slug}.com",
                'password' => Hash::make('password'),
                'role' => 'company_admin',
                'is_active' => true,
            ]);
            $companyAdmin->assignRole('company_admin');

            $labManager = User::create([
                'company_id' => $company->id,
                'name' => "Manager {$company->name}",
                'email' => "manager{$index}@{$company->slug}.com",
                'password' => Hash::make('password'),
                'role' => 'lab_manager',
                'is_active' => true,
            ]);
            $labManager->assignRole('lab_manager');

            $start = $index * 4;
            for ($i = $start; $i < $start + 4 && $i < count($technicianNames); $i++) {
                $tech = User::create([
                    'company_id' => $company->id,
                    'name' => $technicianNames[$i][0],
                    'email' => $technicianNames[$i][1],
                    'password' => Hash::make('password'),
                    'pin' => $technicianNames[$i][2],
                    'role' => 'technician',
                    'is_active' => true,
                ]);
                $tech->assignRole('technician');
            }
        }
    }

    /**
     * @param  array<int, Company>  $companies
     */
    private function createOrders(array $companies): void
    {
        $doctorNames = ['Dr. Ahmad', 'Dr. Fatima', 'Dr. Khan', 'Dr. Ali', 'Dr. Malik', 'Dr. Shah', 'Dr. Raza', 'Dr. Noor'];
        $priorities = [OrderPriority::Low, OrderPriority::Normal, OrderPriority::High, OrderPriority::Urgent];
        $scanEventCount = 0;

        foreach ($companies as $company) {
            $lab = $company->labs->first();
            $productTypes = $company->productTypes;
            $workstations = $lab->workstations()->where('type', 'station')->get();
            $technicians = $company->users()->where('role', 'technician')->get();

            for ($i = 0; $i < 25; $i++) {
                $productType = $productTypes->random();
                $status = $this->randomStatus($i);
                $dueDate = Carbon::now()->addDays(rand(-5, 14));
                $createdAt = Carbon::now()->subDays(rand(0, 10))->subHours(rand(0, 12));

                $order = Order::create([
                    'company_id' => $company->id,
                    'lab_id' => $lab->id,
                    'product_type_id' => $productType->id,
                    'patient_ref' => 'PAT-'.strtoupper(Str::random(6)),
                    'doctor_name' => $doctorNames[array_rand($doctorNames)],
                    'qr_code' => 'ORD-'.Str::ulid(),
                    'priority' => $priorities[array_rand($priorities)],
                    'due_date' => $dueDate,
                    'status' => $status,
                    'created_at' => $createdAt,
                    'completed_at' => $status === OrderStatus::Completed ? Carbon::now()->subDays(rand(0, 3)) : null,
                ]);

                $templates = $productType->processTemplates()->orderBy('sort_order')->get();
                $stepsToComplete = match ($status) {
                    OrderStatus::Completed => $templates->count(),
                    OrderStatus::InProgress => max(1, rand(1, $templates->count() - 1)),
                    default => 0,
                };

                foreach ($templates as $tIndex => $template) {
                    $stepStatus = match (true) {
                        $tIndex < $stepsToComplete => StepStatus::Done,
                        $tIndex === $stepsToComplete && $status === OrderStatus::InProgress => StepStatus::InProgress,
                        default => StepStatus::Pending,
                    };

                    $assignedTo = null;
                    if (in_array($stepStatus, [StepStatus::Done, StepStatus::InProgress]) && $technicians->isNotEmpty()) {
                        $assignedTo = $technicians->random()->id;
                    }

                    $step = OrderStep::create([
                        'order_id' => $order->id,
                        'process_template_id' => $template->id,
                        'sort_order' => $template->sort_order,
                        'step_name' => $template->step_name,
                        'status' => $stepStatus,
                        'assigned_to' => $assignedTo,
                    ]);

                    if (in_array($stepStatus, [StepStatus::Done, StepStatus::InProgress]) && $workstations->isNotEmpty() && $technicians->isNotEmpty()) {
                        $workstation = $workstations->random();
                        $technician = $technicians->random();
                        $scanTime = $createdAt->copy()->addMinutes(($tIndex + 1) * rand(20, 120));
                        $duration = $template->expected_minutes ? rand(
                            (int) ($template->expected_minutes * 0.7),
                            (int) ($template->expected_minutes * 1.5)
                        ) * 60 : null;

                        ScanEvent::create([
                            'order_id' => $order->id,
                            'order_step_id' => $step->id,
                            'workstation_id' => $workstation->id,
                            'user_id' => $technician->id,
                            'event_type' => ScanEventType::Start,
                            'scanned_at' => $scanTime,
                        ]);
                        $scanEventCount++;

                        if ($stepStatus === StepStatus::Done && $duration !== null) {
                            ScanEvent::create([
                                'order_id' => $order->id,
                                'order_step_id' => $step->id,
                                'workstation_id' => $workstation->id,
                                'user_id' => $technician->id,
                                'event_type' => ScanEventType::Complete,
                                'scanned_at' => $scanTime->copy()->addSeconds($duration),
                                'duration_seconds' => $duration,
                            ]);
                            $scanEventCount++;
                        }
                    }
                }
            }
        }
    }

    private function randomStatus(int $index): OrderStatus
    {
        if ($index < 8) {
            return OrderStatus::Completed;
        }
        if ($index < 18) {
            return OrderStatus::InProgress;
        }
        if ($index < 22) {
            return OrderStatus::Pending;
        }
        if ($index < 24) {
            return OrderStatus::OnHold;
        }

        return OrderStatus::Cancelled;
    }
}
