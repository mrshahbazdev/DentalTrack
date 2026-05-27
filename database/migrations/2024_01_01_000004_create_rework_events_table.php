<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rework_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_step_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flagged_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('original_technician')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('cause', ['material_defect', 'technique_error', 'equipment_issue', 'design_error', 'other'])->default('other');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_rework', 'resolved'])->default('pending');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('order_step_id');
            $table->index('status');
            $table->index('cause');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_code', 8)->nullable()->unique()->after('qr_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('tracking_code');
        });

        Schema::dropIfExists('rework_events');
    }
};
