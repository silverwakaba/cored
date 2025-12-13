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
        Schema::create('marketplace_apps', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->string('name', 255)->unique();
            $table->text('description')->nullable();
            $table->string('developer_name', 255)->nullable();
            
            $table->boolean('is_free')->default(true);
            $table->decimal('monthly_price', 10, 2)->nullable();
            
            $table->decimal('rating', 3, 2)->nullable();
            $table->integer('review_count')->default(0);
            
            $table->boolean('is_published')->default(false);
            
            $table->text('icon_url')->nullable();
            $table->text('documentation_url')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_apps');
    }
};
