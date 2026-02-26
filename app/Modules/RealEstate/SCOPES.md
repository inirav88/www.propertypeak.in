# Global Property Visibility Rule

## Overview

The Property model implements a **Global Scope** that automatically filters out any properties that are not approved. This ensures that:

1. **Frontend never shows unapproved properties** - Any query on the Property model automatically includes `WHERE approval_status = 'approved'`
2. **Security by default** - Developers cannot accidentally expose pending/rejected properties
3. **Clean code** - No need to manually add `->where('approval_status', 'approved')` to every query

## Implementation

### Property Model Global Scope

```php
// app/Modules/RealEstate/Models/Property.php

protected static function boot(): void
{
    parent::boot();

    // Global scope to only show approved properties
    static::addGlobalScope('approved', function ($builder): void {
        $builder->where('approval_status', 'approved');
    });
}
```

## Bypassing the Global Scope

When you need to access properties regardless of approval status (e.g., in admin panels or user dashboards), use these methods:

### 1. Repository Methods (Recommended)

```php
// Get all properties including non-approved
$properties = $propertyRepository->paginateAll(20);

// Get only pending properties
$pending = $propertyRepository->paginatePending(20);

// Find by slug including non-approved
$property = $propertyRepository->findBySlugWithAll($slug);
```

### 2. Model Scope Methods

```php
// Include all properties (remove global scope)
$properties = Property::withoutGlobalScope('approved')->get();

// Include all properties using custom scope
$properties = Property::withAll()->get();

// Get only pending
$pending = Property::pending()->get();

// Get only rejected
$rejected = Property::rejected()->get();
```

### 3. Direct Query Building

```php
// Remove global scope for specific query
$properties = Property::withoutGlobalScope('approved')
    ->where('user_id', $userId)
    ->get();
```

## Where Global Scope is Automatically Applied

### ✅ Automatically Filters (Safe)

```php
// Frontend listings - ONLY approved properties
Property::all();
Property::where('city', 'Mumbai')->get();
Property::paginate(20);

// Relationships - ONLY approved properties
$developer->properties;
$agent->properties;
$project->properties;

// Search - ONLY approved properties
Property::search($filters)->get();
```

### ❌ Must Explicitly Bypass (Admin/Dashboard)

```php
// Admin viewing pending properties
Property::withoutGlobalScope('approved')
    ->where('approval_status', 'pending')
    ->get();

// User viewing their own properties
Property::withoutGlobalScope('approved')
    ->where('user_id', auth()->id())
    ->get();

// Approval workflow
$property = Property::withoutGlobalScope('approved')->find($id);
```

## Repository Pattern Enforcement

The Repository layer enforces proper filtering:

```php
class PropertyRepository implements PropertyRepositoryInterface
{
    // Respects global scope - ONLY approved
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Property::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    // Bypasses global scope - ALL properties
    public function paginateAll(int $perPage = 15): LengthAwarePaginator
    {
        return Property::withoutGlobalScope('approved')
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    // Bypasses global scope - PENDING only
    public function paginatePending(int $perPage = 15): LengthAwarePaginator
    {
        return Property::withoutGlobalScope('approved')
            ->with(['user'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
```

## Security Checklist

### ✅ Safe Routes (Global Scope Active)

| Route | Access | Scope |
|-------|--------|-------|
| `/properties` | Public | ✅ Only approved |
| `/properties/{slug}` | Public | ✅ Only approved |
| `/developers/{slug}` | Public | ✅ Only approved |
| `/agents/{slug}` | Public | ✅ Only approved |
| `/search` | Public | ✅ Only approved |

### ⚠️ Admin Routes (Scope Bypassed)

| Route | Access | Scope |
|-------|--------|-------|
| `/admin/properties` | Admin | ⚠️ All properties |
| `/admin/properties/pending` | Admin | ⚠️ Bypassed explicitly |
| `/developer/properties` | Owner | ⚠️ Bypassed for owner |
| `/agent/properties` | Owner | ⚠️ Bypassed for owner |

## Best Practices

### 1. Always Use Repositories

```php
// ✅ Good - Repository handles scope properly
$properties = $this->propertyRepository->paginate();
$all = $this->propertyRepository->paginateAll();
$pending = $this->propertyRepository->paginatePending();

// ❌ Avoid direct model queries in controllers
$properties = Property::all(); // May not be what you expect
```

### 2. Explicit Variable Naming

```php
// ✅ Good - Clear intent
$approvedProperties = Property::paginate();
$allProperties = Property::withoutGlobalScope('approved')->paginate();
$pendingProperties = Property::pending()->paginate();

// ❌ Ambiguous
$properties = Property::paginate(); // Depends on context
```

### 3. Document Scope Bypasses

```php
// ✅ Good - Document why scope is bypassed
// Bypass global scope: Admin needs to see all properties for moderation
$properties = Property::withoutGlobalScope('approved')->get();
```

## Testing the Global Scope

```php
// Test that global scope filters unapproved properties
public function test_global_scope_filters_unapproved_properties()
{
    Property::factory()->create(['approval_status' => 'approved']);
    Property::factory()->create(['approval_status' => 'pending']);
    Property::factory()->create(['approval_status' => 'rejected']);

    // Global scope active - only 1 result
    $this->assertEquals(1, Property::count());

    // Bypass scope - all 3 results
    $this->assertEquals(3, Property::withoutGlobalScope('approved')->count());
}
```

## Emergency Override

If you need to completely disable the global scope (not recommended):

```php
// In a service provider or bootstrap file
Property::withoutGlobalScope('approved');
```

## Summary

| Query | Result |
|-------|--------|
| `Property::all()` | Only approved |
| `Property::find($id)` | Only if approved |
| `Property::withoutGlobalScope('approved')->all()` | All properties |
| `Property::pending()->get()` | Only pending |
| `Property::rejected()->get()` | Only rejected |
| `$propertyRepository->paginate()` | Only approved |
| `$propertyRepository->paginateAll()` | All properties |

**Remember**: When in doubt, the global scope protects you. Always be explicit when bypassing it.
