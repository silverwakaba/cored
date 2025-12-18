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
        Schema::table('base_modules', function (Blueprint $table) {
            if (!Schema::hasColumn('base_modules', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('base_modules', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('description');
            }
            if (!Schema::hasColumn('base_modules', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });

        Schema::table('base_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('base_requests', 'value')) {
                $table->string('value')->nullable()->after('name');
            }
            if (!Schema::hasColumn('base_requests', 'description')) {
                $table->text('description')->nullable()->after('value');
            }
            if (!Schema::hasColumn('base_requests', 'display_order')) {
                $table->integer('display_order')->default(0)->after('description');
            }
            if (!Schema::hasColumn('base_requests', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('display_order');
            }
            if (!Schema::hasColumn('base_requests', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('base_requests', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });

        // Add unique constraint if not exists
        // Note: This might fail if there are existing duplicate records
        try {
            Schema::table('base_requests', function (Blueprint $table) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexesFound = $sm->listTableIndexes('base_requests');
                if (!isset($indexesFound['base_requests_base_modules_id_value_unique'])) {
                    $table->unique(['base_modules_id', 'value'], 'base_requests_base_modules_id_value_unique');
                }
            });
        } catch (\Exception $e) {
            // Unique constraint might already exist or there are duplicates
            // Log error but don't fail migration
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('base_modules', function (Blueprint $table) {
            $table->dropColumn(['description', 'created_at', 'updated_at']);
        });

        Schema::table('base_requests', function (Blueprint $table) {
            // Drop unique constraint if exists
            try {
                $table->dropUnique('base_requests_base_modules_id_value_unique');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
            $table->dropColumn(['value', 'description', 'display_order', 'is_active', 'created_at', 'updated_at']);
        });
    }
};
