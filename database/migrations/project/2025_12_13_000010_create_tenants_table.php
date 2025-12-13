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
        Schema::create('tenants', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            // Basic Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('country', 2)->default('ID');
            $table->string('industry', 100)->nullable();
            
            // Subscription Info
            $table->ulid('subscription_id')->nullable();
            $table->enum('subscription_status', ['active', 'trial', 'suspended', 'cancelled'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_starts_at')->nullable();
            
            // Configuration
            $table->string('currency', 3)->default('IDR');
            $table->string('timezone', 50)->default('Asia/Jakarta');
            $table->string('date_format', 20)->default('DD/MM/YYYY');
            
            // Logo & Branding
            $table->text('logo_url')->nullable();
            $table->string('primary_color', 7)->nullable();
            
            // Contact
            $table->string('headquarters')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->ulid('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index('subscription_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};

