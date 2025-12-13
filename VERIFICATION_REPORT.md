# Verifikasi Database Schema & Model

## Status Migrasi

### ✅ Sudah Ada
- `users` (0001_01_02_000001_create_users_table.php)
- `user_requests` (0001_01_02_000002_create_user_requests_table.php)
- `user_cta_messages` (0001_01_02_000003_create_user_cta_messages_table.php)
- `permission_tables` (0000_00_00_000002_create_permission_tables.php) - Spatie
- `menus` (0000_00_00_000003_create_menu_navigation_table.php)

### ❌ Belum Ada (Perlu Dibuat)

#### 1. Platform / Global (9 tabel)
- [ ] `tenants`
- [ ] `subscription_plans`
- [ ] `subscriptions`
- [ ] `payment_methods`
- [ ] `invoices`
- [ ] `usage_metrics`
- [ ] `tenant_onboarding`
- [ ] `notifications`
- [ ] `support_tickets`

#### 2. Tenant / Company & HR Core (8 tabel)
- [ ] `companies`
- [ ] `locations`
- [ ] `departments`
- [ ] `cost_centers`
- [ ] `salary_grades`
- [ ] `positions`
- [ ] `employees`
- [ ] `user_companies`

#### 3. Time, Leave, Attendance (5 tabel)
- [ ] `holidays`
- [ ] `leave_types`
- [ ] `leave_balances`
- [ ] `leave_requests`
- [ ] `attendance_records`

#### 4. Payroll & Remaining (40+ tabel)
- [ ] `salary_components`
- [ ] `employee_salaries`
- [ ] `payroll_runs`
- [ ] `payroll_details`
- [ ] `job_postings`
- [ ] `applicants`
- [ ] `performance_goals`
- [ ] `performance_reviews`
- [ ] `training_programs`
- [ ] `employee_trainings`
- [ ] `succession_plans`
- [ ] `compensation_grades`
- [ ] `benefits_plans`
- [ ] `employee_benefits`
- [ ] `documents`
- [ ] `workflows`
- [ ] `workflow_steps`
- [ ] `approvals`
- [ ] `analytics_snapshots`
- [ ] `system_configurations`
- [ ] `integrations`
- [ ] `api_keys`
- [ ] `data_exports`
- [ ] `compliance_checklists`
- [ ] `backup_logs`
- [ ] `usage_events` (dari migration list)
- [ ] `usage_quotas` (dari migration list)
- [ ] `usage_alerts` (dari migration list)
- [ ] `feature_access` (dari migration list)
- [ ] `custom_fields` (dari migration list)
- [ ] `custom_field_values` (dari migration list)
- [ ] `white_label_configs` (dari migration list)
- [ ] `marketplace_apps` (dari migration list)
- [ ] `app_installations` (dari migration list)
- [ ] `ai_predictions` (dari migration list)
- [ ] `chatbot_conversations` (dari migration list)
- [ ] `chatbot_messages` (dari migration list)
- [ ] `wellness_programs` (dari migration list)
- [ ] `employee_wellness_tracking` (dari migration list)

## Status Model

### ✅ Sudah Ada
- `User` - dengan relasi ke `UserRequest`
- `Menu` - dengan relasi parent/children dan roles
- `BaseRequest`
- `UserRequest` - dengan relasi ke `User` dan `BaseRequest`
- `UserCtaMessage`
- `D1Test`

### ❌ Belum Ada (Perlu Dibuat)
Semua model untuk tabel di atas perlu dibuat dengan relasi yang sesuai.

## Tindakan yang Diperlukan

1. Buat semua migrasi yang belum ada (60+ migrasi)
2. Buat semua model yang belum ada (60+ model)
3. Verifikasi relasi antar model
4. Pastikan semua foreign key constraints benar
5. Pastikan semua index sudah dibuat

