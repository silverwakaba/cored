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
        Schema::create('training_programs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            
            // Program Info
            $table->string('code', 50);
            $table->string('title');
            $table->text('description')->nullable();
            
            // Details
            $table->integer('duration_hours')->nullable();
            $table->text('content')->nullable();
            $table->string('trainer_name')->nullable();
            
            // Dates
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->timestamp('created_at')->useCurrent();
            
            $table->unique(['tenant_id', 'company_id', 'code']);
            $table->index('tenant_id');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_programs');
    }
};

