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
        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            
            $table->ulid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->ulid('conversation_id');
            $table->foreign('conversation_id')->references('id')->on('chatbot_conversations')->onDelete('cascade');
            
            $table->string('sender', 10); // user, chatbot, agent
            $table->text('message_text');
            
            $table->string('intent', 100)->nullable();
            $table->decimal('confidence', 5, 2)->nullable();
            
            $table->text('response_text')->nullable();
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['tenant_id', 'conversation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_messages');
    }
};
