# Monorepo Structure Documentation

## Overview
This project uses a monorepo approach where core/boilerplate code is separated from project-specific code.

## Directory Structure

### Core (Boilerplate - DO NOT MODIFY in child projects)

```
app/
├── Contracts/Core/          # Core repository interfaces
├── Repositories/Core/        # Core repository implementations
├── Models/Core/              # Core Eloquent models
├── Http/Controllers/Core/    # Core controllers (API & FE) + base Controller
│   └── Cron/                 # Core cron controllers
├── Console/Commands/Core/    # Core console commands
├── Helpers/Core/             # Helper classes (core utilities)
├── Events/Core/              # Event handlers (core)
├── Observers/Core/           # Model observers (core)
├── Mail/Core/                # Mail classes (core)
├── Http/Middleware/Core/     # Middleware (core)
├── Http/Requests/Core/       # Form request validators (core)
├── Http/Resources/Core/      # API resources (core)
└── View/Components/          # Blade components (core)

database/
├── migrations/core/          # Core migrations (use old timestamps: 0000_00_00_...)
├── seeders/core/             # Core seeders
└── factories/
    └── Core/                 # Core model factories

routes/
├── core/
│   ├── web.php              # Core web routes
│   ├── api.php              # Core API routes
│   └── breadcrumbs.php      # Core breadcrumbs
├── breadcrumbs.php          # Project breadcrumbs
├── channels.php             # Broadcast channels (core)
└── console.php             # Console commands schedule (core)

resources/
└── views/core/               # Core Blade views
```

### Project (Safe to modify/add)

```
app/
├── Contracts/Project/        # Project-specific interfaces
├── Repositories/Project/     # Project-specific repositories
├── Models/Project/           # Project-specific models
├── Http/Controllers/        # Project-specific controllers (outside Core/)
└── [Other project-specific code]

database/
├── migrations/project/       # Project migrations (use current timestamps)
└── seeders/project/          # Project seeders

routes/
├── web.php                   # Project web routes
└── api.php                   # Project API routes

resources/
└── views/                    # Project-specific views (outside core/)
```

## Namespace Conventions

### Core Namespaces
- Models: `App\Models\Core\...`
- Repositories: `App\Repositories\Core\...`
- Contracts: `App\Contracts\Core\...`
- Controllers: `App\Http\Controllers\Core\...` (includes base Controller)
- Commands: `App\Console\Commands\Core\...`
- Seeders: `Database\Seeders\Core\...`
- Helpers: `App\Helpers\Core\...`
- Events: `App\Events\Core\...`
- Observers: `App\Observers\Core\...`
- Mail: `App\Mail\Core\...`
- Middleware: `App\Http\Middleware\Core\...`
- Requests: `App\Http\Requests\Core\...`
- Resources: `App\Http\Resources\Core\...`

### Project Namespaces
- Models: `App\Models\Project\...`
- Repositories: `App\Repositories\Project\...`
- Contracts: `App\Contracts\Project\...`
- Controllers: `App\Http\Controllers\...` (or custom namespace)
- Seeders: `Database\Seeders\Project\...`

## Migration Strategy

### Core Migrations
- Location: `database/migrations/core/`
- Timestamp format: `0000_00_00_...` or `0001_01_02_...` (old timestamps)
- Purpose: Boilerplate database structure
- Rule: **DO NOT modify** in child projects

### Project Migrations
- Location: `database/migrations/project/`
- Timestamp format: Current date (e.g., `2025_12_13_...`)
- Purpose: Project-specific database changes
- Rule: Safe to add/modify

### Execution Order
Laravel automatically sorts migrations by timestamp, ensuring:
1. Core migrations run first (old timestamps)
2. Project migrations run after (current timestamps)
3. New core migrations can be added later with old timestamps and will run in correct order

## Route Loading

Routes are loaded in this order:
1. `routes/core/web.php` (core web routes)
2. `routes/web.php` (project web routes)
3. `routes/core/api.php` (core API routes)
4. `routes/api.php` (project API routes)

## View Resolution

Views are resolved in this order:
1. `resources/views/core/` (core views - prioritized)
2. `resources/views/` (project views - fallback)

## Service Provider Bindings

Core contracts are bound to core repositories in `AppServiceProvider`:
- `App\Contracts\Core\*Interface` → `App\Repositories\Core\*Repository`

## Best Practices

### Adding New Core Components
1. Use Core namespace: `App\...\Core\...`
2. Place in Core directory
3. Use old migration timestamps if adding migrations
4. Update service provider if adding new bindings

### Adding Project Components
1. Use Project namespace or root namespace
2. Place in Project directory or appropriate location
3. Use current timestamps for migrations
4. Can extend/override core components if needed

### Migration Naming
- Core: `0001_01_02_000XXX_create_core_feature.php`
- Project: `2025_12_13_XXXXXX_create_project_feature.php`

## Verification Checklist

- [x] All core models in `app/Models/Core/`
- [x] All core repositories in `app/Repositories/Core/`
- [x] All core contracts in `app/Contracts/Core/`
- [x] All core controllers in `app/Http/Controllers/Core/` (includes base Controller)
- [x] All core console commands in `app/Console/Commands/Core/`
- [x] All core factories in `database/factories/Core/`
- [x] Core breadcrumbs in `routes/core/breadcrumbs.php`
- [x] All core seeders in `database/seeders/core/`
- [x] All core migrations in `database/migrations/core/`
- [x] All core routes in `routes/core/`
- [x] All core views in `resources/views/core/`
- [x] All namespaces updated correctly
- [x] Service provider bindings updated
- [x] Config files updated (auth.php, etc.)
- [x] No leftover files in wrong locations

## Notes

- All core components are now in Core directories with Core namespaces
- Base Controller (`Controller.php`) is located at `app/Http/Controllers/Core/Controller.php` with namespace `App\Http\Controllers\Core`
- View Components remain at `app/View/Components/` (not in Core) as they're framework-level components
- Console Commands are located at `app/Console/Commands/Core/` with namespace `App\Console\Commands\Core\...`
- Project-specific versions can be added in Project directories when needed

