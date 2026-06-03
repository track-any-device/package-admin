# package-admin — AI Instructions

This is the **Filament v4 admin panel package** for the Track Any Device platform.
Packagist: `track-any-device/admin` | Namespace: `TrackAnyDevice\Admin\`

All Filament resources, pages, widgets, and relation managers live here. The `server-admin`
Laravel shell app is intentionally thin — it only handles SSO routes and defers everything
to this package.

Read this file before making any change.

---

## Platform-Wide Rules

These three rules apply in every repository under the `track-any-device` organisation.

**Cross-repo changes: file a GitHub issue first.**
If a task in this repository requires a change in another package or server app — stop. Open a
GitHub issue in the target repository describing exactly what is needed and why. Reference that
issue number in your commit message (`ref track-any-device/{repo}#{n}`). Do not directly edit
files in another repository. When picking up a cross-repo issue, run Claude locally inside that
repository's working directory and work only within its scope.

**Release order: packages before server apps.**
Tag this package before bumping its version in `server-admin/composer.json`.
This package depends on `package-core` — ensure core is released first if core also changed.

**Database layer lives in `package-core` only.**
No migrations or model classes here. All models come from `track-any-device/core`.
If a new Filament resource needs a model that doesn't exist yet, file an issue against
`package-core` first.

---

## Rule 1 — Plan before implementing

Before writing any code, ask clarifying questions. Present a plan and get explicit agreement.
Only begin once the approach is confirmed.

---

## What lives in this package

| Directory | Contents |
|---|---|
| `src/AdminPanelProvider.php` | Panel registration — domain, nav, resources, middleware |
| `src/Models/AdminUser.php` | Wraps core `User`, implements `canAccessPanel()` |
| `src/Http/Middleware/` | `EnsureAdminDomain` |
| `src/Filament/Resources/` | One directory per resource (User, Tenant, Device, Beat…) |
| `src/Filament/Pages/` | Custom full-page Filament pages |
| `src/Filament/Widgets/` | Dashboard widgets |

---

## Rule 2 — Never use `'tenants'` as a Filament resource slug

Filament v4 reserves `tenants` as an internal keyword. Any resource with that slug has its
routes silently dropped, causing `Route [filament.admin.resources.tenants.index] not defined`.

```php
// Wrong
protected static ?string $slug = 'tenants';

// Right — always use this
protected static ?string $slug = 'organisations';
```

---

## Rule 3 — Use `->toolbarActions()`, not `->bulkActions()`

Filament v4 removed `bulkActions()`. Multi-row actions belong in `->toolbarActions([...])`.

---

## Rule 4 — Admin panel is the only surface that shows sensitive device fields

`device.sim_number` and `device.gsm_number` are visible only in this package's resources.
Never expose these fields in `server-tenant` or `web` pages.

---

## Dependencies

```
track-any-device/core
filament/filament ^4.0
```

---

## Versioning

Tags are created automatically on merge to `main`. Default bump is `patch`.
