<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scan_events', function (Blueprint $table) {
            $table->index(['order_id', 'scanned_at']);
            $table->index(['user_id', 'event_type', 'scanned_at']);
            $table->index(['workstation_id', 'event_type', 'scanned_at']);
            $table->index(['event_type', 'scanned_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['product_type_id', 'status']);
            $table->index(['status', 'due_date']);
            $table->index(['company_id', 'created_at']);
            $table->index('predicted_completion_at');
        });

        Schema::table('predictions', function (Blueprint $table) {
            $table->index(['order_id', 'created_at']);
            $table->index('model_version');
        });

        Schema::table('rework_events', function (Blueprint $table) {
            $table->index(['order_id', 'created_at']);
            $table->index('original_technician');
        });
    }

    public function down(): void
    {
        Schema::table('scan_events', function (Blueprint $table) {
            $table->dropIndex(['order_id', 'scanned_at']);
            $table->dropIndex(['user_id', 'event_type', 'scanned_at']);
            $table->dropIndex(['workstation_id', 'event_type', 'scanned_at']);
            $table->dropIndex(['event_type', 'scanned_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['product_type_id', 'status']);
            $table->dropIndex(['status', 'due_date']);
            $table->dropIndex(['company_id', 'created_at']);
            $table->dropIndex(['predicted_completion_at']);
        });

        Schema::table('predictions', function (Blueprint $table) {
            $table->dropIndex(['order_id', 'created_at']);
            $table->dropIndex(['model_version']);
        });

        Schema::table('rework_events', function (Blueprint $table) {
            $table->dropIndex(['order_id', 'created_at']);
            $table->dropIndex(['original_technician']);
        });
    }
};
