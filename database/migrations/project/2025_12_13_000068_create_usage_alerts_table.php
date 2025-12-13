<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usage_alerts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            $table->string('alert_type', 50); // warning, limit_reached, overage
            $table->string('event_type', 100);
            
            $table->decimal('current_usage', 20, 4);
            $table->decimal('quota_limit', 20, 4);
            $table->decimal('usage_percent', 5, 2);
            
            $table->boolean('is_acknowledged')->default(false);
            $table->ulid('acknowledged_by')->nullable();
            $table->foreign('acknowledged_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('acknowledged_at')->nullable();
            
            $table->json('notification_sent_to')->nullable();
            
            $table->timestamps();
            
            $table->index(['tenant_id', 'is_acknowledged']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage_alerts');
    }
};
