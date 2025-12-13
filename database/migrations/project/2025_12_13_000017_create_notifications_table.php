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
        Schema::create('notifications', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('recipient_user_id');
            $table->foreign('recipient_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('notification_type', 100)->nullable();
            $table->ulid('related_entity_id')->nullable();
            $table->string('related_entity_type', 50)->nullable();
            $table->boolean('is_read')->default(false);
            $table->boolean('is_deleted')->default(false);
            
            // Phase 1: Basic channels
            $table->boolean('send_via_email')->default(true);
            $table->timestamp('email_sent_at')->nullable();
            
            // Phase 5: Advanced channels
            $table->boolean('send_via_push')->default(false);
            $table->timestamp('push_sent_at')->nullable();
            $table->boolean('send_via_sms')->default(false);
            $table->timestamp('sms_sent_at')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('tenant_id');
            $table->index('recipient_user_id');
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

