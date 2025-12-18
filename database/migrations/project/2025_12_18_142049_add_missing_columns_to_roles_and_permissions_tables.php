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
        $tableNames = config('permission.table_names');
        
        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        // Add missing columns to roles table
        Schema::table($tableNames['roles'], function (Blueprint $table) use ($tableNames) {
            if (!Schema::hasColumn($tableNames['roles'], 'company_id')) {
                $table->string('company_id', 26)->nullable()->after('id');
                $table->index('company_id');
            }
            if (!Schema::hasColumn($tableNames['roles'], 'description')) {
                $table->text('description')->nullable()->after('guard_name');
            }
            if (!Schema::hasColumn($tableNames['roles'], 'is_system')) {
                $table->boolean('is_system')->default(false)->after('description');
            }
        });

        // Add unique constraint for roles (company_id, name) if not exists
        // Note: This might fail if there are existing duplicate records
        try {
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($tableNames) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexesFound = $sm->listTableIndexes($tableNames['roles']);
                if (!isset($indexesFound['roles_company_id_name_guard_name_unique'])) {
                    $table->unique(['company_id', 'name', 'guard_name'], 'roles_company_id_name_guard_name_unique');
                }
            });
        } catch (\Exception $e) {
            // Unique constraint might already exist or there are duplicates
            // Log error but don't fail migration
        }

        // Add missing columns to permissions table
        Schema::table($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
            if (!Schema::hasColumn($tableNames['permissions'], 'company_id')) {
                $table->string('company_id', 26)->nullable()->after('id');
                $table->index('company_id');
            }
            if (!Schema::hasColumn($tableNames['permissions'], 'description')) {
                $table->text('description')->nullable()->after('guard_name');
            }
            if (!Schema::hasColumn($tableNames['permissions'], 'module_name')) {
                $table->string('module_name', 100)->nullable()->after('description');
                $table->index(['company_id', 'module_name']);
            }
            if (!Schema::hasColumn($tableNames['permissions'], 'is_system')) {
                $table->boolean('is_system')->default(false)->after('module_name');
            }
        });

        // Add unique constraint for permissions (company_id, name) if not exists
        // Note: This might fail if there are existing duplicate records
        try {
            Schema::table($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexesFound = $sm->listTableIndexes($tableNames['permissions']);
                if (!isset($indexesFound['permissions_company_id_name_guard_name_unique'])) {
                    $table->unique(['company_id', 'name', 'guard_name'], 'permissions_company_id_name_guard_name_unique');
                }
            });
        } catch (\Exception $e) {
            // Unique constraint might already exist or there are duplicates
            // Log error but don't fail migration
        }

        // Add missing columns to model_has_roles table
        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames) {
            if (!Schema::hasColumn($tableNames['model_has_roles'], 'company_id')) {
                $table->string('company_id', 26)->nullable()->after('model_type');
                $table->index('company_id');
            }
        });

        // Add missing columns to role_has_permissions table
        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            if (!Schema::hasColumn($tableNames['role_has_permissions'], 'company_id')) {
                $table->string('company_id', 26)->nullable()->after('role_id');
            }
        });

        // Add missing columns to model_has_permissions table
        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames) {
            if (!Schema::hasColumn($tableNames['model_has_permissions'], 'company_id')) {
                $table->string('company_id', 26)->nullable()->after('model_type');
                $table->index('company_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');
        
        if (empty($tableNames)) {
            return;
        }

        Schema::table($tableNames['roles'], function (Blueprint $table) use ($tableNames) {
            // Drop unique constraint if exists
            try {
                $table->dropUnique('roles_company_id_name_guard_name_unique');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
            $table->dropColumn(['company_id', 'description', 'is_system']);
        });

        Schema::table($tableNames['permissions'], function (Blueprint $table) use ($tableNames) {
            // Drop unique constraint if exists
            try {
                $table->dropUnique('permissions_company_id_name_guard_name_unique');
            } catch (\Exception $e) {
                // Constraint might not exist
            }
            $table->dropColumn(['company_id', 'description', 'module_name', 'is_system']);
        });

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) {
            $table->dropColumn('company_id');
        });

        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
            $table->dropColumn('company_id');
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
};
