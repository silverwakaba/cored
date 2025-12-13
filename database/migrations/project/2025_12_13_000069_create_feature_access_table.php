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
        Schema::create('feature_access', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            $table->string('feature_code', 100);
            $table->string('feature_name', 255)->nullable();
            
            $table->boolean('is_enabled')->default(false);
            $table->date('enabled_since')->nullable();
            $table->date('enabled_until')->nullable();
            
            $table->bigInteger('usage_count')->default(0);
            $table->timestamp('last_used_at')->nullable();
            
            $table->ulid('granted_by')->nullable();
            $table->foreign('granted_by')->references('id')->on('users')->onDelete('set null');
            
            $table->timestamps();
            
            $table->unique(['tenant_id', 'feature_code']);
            $table->index('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_access');
    }
};
