<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('company_id');
        });

        Schema::create('process_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order');
            $table->string('step_name');
            $table->unsignedInteger('expected_minutes')->nullable();
            $table->timestamps();

            $table->index('product_type_id');
            $table->index(['product_type_id', 'sort_order']);
        });

        Schema::create('workstations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->uuid('qr_code')->unique();
            $table->enum('type', ['station', 'waiting_area'])->default('station');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('lab_id');
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lab_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_type_id')->constrained()->cascadeOnDelete();
            $table->string('patient_ref')->nullable();
            $table->string('doctor_name')->nullable();
            $table->uuid('qr_code')->unique();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled', 'on_hold'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('predicted_completion_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->index('lab_id');
            $table->index('status');
            $table->index('priority');
            $table->index('due_date');
            $table->index(['company_id', 'status']);
        });

        Schema::create('order_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('process_template_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('sort_order');
            $table->string('step_name');
            $table->enum('status', ['pending', 'in_progress', 'done', 'skipped'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('order_id');
            $table->index(['order_id', 'status']);
            $table->index(['order_id', 'sort_order']);
        });

        Schema::create('scan_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_step_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('workstation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('event_type', ['start', 'complete', 'pause', 'transfer_to_waiting']);
            $table->timestamp('scanned_at');
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('workstation_id');
            $table->index('user_id');
            $table->index('scanned_at');
            $table->index(['order_id', 'event_type']);
            $table->index(['workstation_id', 'scanned_at']);
            $table->index(['user_id', 'scanned_at']);
        });

        Schema::create('qr_print_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('printable_type');
            $table->unsignedBigInteger('printable_id');
            $table->enum('format', ['sticker_small', 'sticker_large'])->default('sticker_small');
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();

            $table->index(['printable_type', 'printable_id']);
        });

        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('model_version')->default('v1-weighted-avg');
            $table->unsignedInteger('predicted_minutes');
            $table->unsignedInteger('actual_minutes')->nullable();
            $table->decimal('accuracy_pct', 5, 2)->nullable();
            $table->timestamps();

            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predictions');
        Schema::dropIfExists('qr_print_jobs');
        Schema::dropIfExists('scan_events');
        Schema::dropIfExists('order_steps');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('workstations');
        Schema::dropIfExists('process_templates');
        Schema::dropIfExists('product_types');
    }
};
