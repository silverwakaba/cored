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
        Schema::create('white_label_configs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id')->unique();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            $table->string('primary_color', 7)->nullable();
            $table->string('secondary_color', 7)->nullable();
            $table->string('accent_color', 7)->nullable();
            
            $table->text('logo_url')->nullable();
            $table->text('favicon_url')->nullable();
            $table->text('banner_url')->nullable();
            
            $table->string('app_name', 255)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->string('support_email', 255)->nullable();
            $table->string('support_phone', 20)->nullable();
            
            $table->string('custom_domain', 255)->nullable()->unique();
            
            $table->boolean('hide_powered_by')->default(false);
            $table->text('custom_footer_text')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('white_label_configs');
    }
};
