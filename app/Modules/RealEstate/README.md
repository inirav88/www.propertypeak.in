# Real Estate Module

A comprehensive Laravel module for managing developers, agents, properties, and projects with approval workflows.

## Features

- **Multi-role System**: Admin, Developer, Agent roles with approval workflows
- **Property Management**: CRUD operations with image uploads and approval
- **Project Management**: Developer projects with gallery, floor plans, brochures
- **SEO Ready**: Slug-based URLs, meta tags, Schema.org structured data
- **Secure**: Global scopes, policies, middleware protection
- **Modular**: Clean architecture with Repository pattern and Service layer

## Installation

1. Run migrations:
```bash
php artisan migrate
```

2. The service provider is auto-registered via `bootstrap/app.php`.

## Module Structure

```
app/Modules/RealEstate/
├── Contracts/           # Repository interfaces
├── database/
│   └── migrations/      # All database migrations
├── Http/
│   ├── Controllers/     # Admin, Dashboard, Frontend controllers
│   ├── Middleware/      # Role, Approval, Public profile middleware
│   └── Requests/        # Form request validation classes
├── Models/              # Developer, Agent, Project, Property
├── Notifications/       # Email & database notifications
├── Policies/            # Authorization policies
├── Providers/           # Service providers
├── Repositories/        # Repository implementations
├── resources/
│   └── views/           # Blade templates
├── routes/
│   └── web.php          # All module routes
├── Services/            # Business logic services
├── Models/
│   ├── Agent.php
│   ├── Developer.php
│   ├── DeveloperProject.php
│   └── Property.php
├── Policies/
│   ├── DeveloperProjectPolicy.php
│   └── PropertyPolicy.php
└── SCOPES.md            # Global scope documentation
```

## Routes

### Public Routes
- `GET /developers` - List approved developers
- `GET /developers/{slug}` - Developer public profile
- `GET /agents` - List approved agents
- `GET /agents/{slug}` - Agent public profile

### Developer Dashboard (Requires: auth + developer role + approved)
- `GET /developer/dashboard`
- `GET /developer/profile`
- `PUT /developer/profile`
- Resource routes for projects and properties

### Agent Dashboard (Requires: auth + agent role + approved)
- `GET /agent/dashboard`
- `GET /agent/profile`
- `PUT /agent/profile`
- Resource routes for properties

### Admin Panel (Requires: auth + admin role)
- `GET /admin/developers` - Manage developers
- `GET /admin/developers/pending`
- `POST /admin/developers/{id}/approve`
- `POST /admin/developers/{id}/reject`
- `POST /admin/developers/bulk-approve`
- Similar routes for agents
- `GET /admin/properties/pending`
- `POST /admin/properties/{id}/approve`
- `POST /admin/properties/bulk-approve`

## Key Features

### 1. Global Property Visibility
All property queries automatically filter for `approval_status = 'approved'` via a global scope. See `SCOPES.md` for details.

### 2. Approval Workflow
1. User registers as developer/agent
2. Admin receives notification
3. Admin approves/rejects account
4. User receives email notification
5. Approved users can add properties/projects
6. Properties/projects pending admin approval

### 3. File Uploads
- Images: Logo, banner, property images, project gallery
- Documents: Project brochures (PDF)
- Storage: `storage/app/public/` with subdirectories

### 4. SEO
- Unique slugs for all entities
- Meta title/description/keywords
- Schema.org structured data
- Eager loading for performance

## Usage Examples

### Register a Developer
```php
use App\Modules\RealEstate\Services\RegistrationService;

$registrationService->registerDeveloper(
    ['name' => 'John', 'email' => 'john@example.com', 'password' => 'secret'],
    ['company_name' => 'ABC Builders', 'phone' => '1234567890']
);
```

### Create Property
```php
use App\Modules\RealEstate\Services\DeveloperService;

$developerService->createProperty($userId, [
    'title' => '2 BHK Apartment',
    'description' => '...',
    'price' => 5000000,
    // ...
]);
```

### Approve Property
```php
use App\Modules\RealEstate\Services\PropertyApprovalService;

$propertyApprovalService->approveProperty($propertyId, $adminId);
```

## Testing

Run the module tests:
```bash
php artisan test --filter=RealEstate
```

## License

This module is part of the PropertyPeak application.
