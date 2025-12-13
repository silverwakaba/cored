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
        Schema::create('app_installations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('app_id');
            $table->foreign('app_id')->references('id')->on('marketplace_apps')->onDelete('cascade');
            
            $table->string('status', 20)->default('active'); // active, suspended, uninstalled
            
            $table->json('app_configuration')->nullable();
            
            $table->text('api_key')->nullable();
            $table->text('webhook_url')->nullable();
            
            $table->ulid('installed_by');
            $table->foreign('installed_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('installed_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'app_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_installations');
    }
};
