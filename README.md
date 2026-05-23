# track-any-device/admin

Filament v4 admin panel for the Track Any Device platform. Provides the central admin surface — resources, pages, auth middleware, and domain gating — as a standalone Composer package consumed by the host Laravel application.

## Requirements

| Dependency | Version |
|---|---|
| PHP | ^8.3 |
| Laravel | ^13.7 |
| Filament | ^4.0 |
| track-any-device/core | ^0.0.2 |

## Installation

```bash
composer require track-any-device/admin
```

The package is auto-discovered by Laravel. No manual provider registration is needed.

## Configuration

### Environment variables

| Variable | Required | Description |
|---|---|---|
| `ADMIN_DOMAIN` | No | Full hostname for the admin panel (e.g. `admin.yourdomain.com`). When set the panel is served at the root of that host. When unset the panel falls back to the `/admin` path on any host listed in `tenancy.central_domains`. |

### Routes the host app must register

The package redirects unauthenticated requests and the SSO login page to a named route that the host application is responsible for registering:

```php
// In your host app's routes/web.php or a dedicated routes file:
Route::get('/auth/sso/redirect', SsoRedirectController::class)->name('admin.sso.redirect');
```

The route must be named `admin.sso.redirect`. The package will throw a `RouteNotFoundException` at runtime if it is missing.

## How it works

### Domain gating

The `EnsureAdminDomain` middleware runs on every panel request.

- When `ADMIN_DOMAIN` is set: only requests whose `Host` header matches exactly are allowed through. All other hosts — including tenant subdomains — receive a 404.
- When `ADMIN_DOMAIN` is unset: any host listed in `tenancy.central_domains` is accepted. This covers local development and CI where a dedicated admin domain is not yet configured.

### Authentication

The panel uses its own session-based auth rather than the tenant OAuth pipeline, making it usable as a break-glass surface if the identity provider is misconfigured.

1. An unauthenticated request hits the panel.
2. `AdminAuthenticate` redirects to `route('admin.sso.redirect')`.
3. The host app's SSO controller exchanges the callback for a session and redirects back to the panel.
4. `AdminUser::canAccessPanel()` confirms the authenticated user holds a central-staff role. Non-staff users are rejected at this point regardless of session state.

### AdminUser

`AdminUser` extends the core `User` model and implements Filament's `FilamentUser` contract. It reads from the same `users` table but restricts panel access to users whose role returns `true` from `role->isCentralStaff()`.

```php
// Defined in src/Models/AdminUser.php
public function canAccessPanel(Panel $panel): bool
{
    return $this->role?->isCentralStaff() ?? false;
}
```

To use this model as the Filament auth guard, configure the admin panel guard in the host app's `config/auth.php` to point at `AdminUser`.

## Included resources

### Administration
| Resource | Model | Notes |
|---|---|---|
| Organisations | `Tenant` | Slug `organisations` avoids Filament's reserved `tenants` keyword. Badge shows pending-approval count. Approve / reject actions inline. |
| Users | `User` | Role filter, resend-invite action (sends password-reset link). |
| Policy Versions | `PolicyVersion` | |
| Countries | `Country` | |

### Devices
| Resource | Model | Notes |
|---|---|---|
| Devices | `Device` | Onboard action dispatches `OnboardDeviceJob`. Filters: status, type, approval, visibility, stock. |
| Device Types | `DeviceType` | |
| Device Commands | `DeviceCommand` | Read-only list and view. |
| Chips | `Chip` | |
| Compute Boards | `ComputeBoard` | |
| Connecting Cables | `ConnectingCable` | |
| Sensors | `Sensor` | |
| GSM Networks | `GsmNetwork` | |
| Signals | `Signal` | List only. |
| Incoming Messages | `IncomingMessage` | List and view only. |

### Operations
| Resource | Model | Notes |
|---|---|---|
| Beats | `Beat` | Beat-assignment relation manager. |
| Beat Templates | `BeatTemplate` | |
| Drivers | `Driver` | |
| Assignees | `Assignee` | |
| Assignee Types | `AssigneeType` | |
| Incidents | `Incident` | |
| Device Orders | `DeviceOrder` | |
| Charging Sets | `ChargingSet` | |
| Domains | `Domain` | |
| Subscribers | `Subscriber` | List only. |
| Contact Submissions | `ContactSubmission` | List and edit only. |

### CMS
| Resource | Model | Notes |
|---|---|---|
| Pages | `Page` | Sections relation manager. |
| Blog | `Blog` | Sections relation manager, featured flag, scheduled publish. |
| Solutions | `Solution` | |
| Industries | `Industry` | |
| Testimonials | `Testimonial` | List only. |
| Nav Links | `NavLink` | List only. |

### Pages
| Page | Path | Description |
|---|---|---|
| Device Logs | `/device-logs` | Real-time log viewer subscribed to the `private-admin.device-logs` broadcast channel. Ring buffer — no persistence. |

## Release workflow

Releases are automated via GitHub Actions (`.github/workflows/release.yml`). On every push to `main` the workflow reads conventional commit messages since the last tag and bumps the version accordingly:

| Commit prefix | Bump |
|---|---|
| Any type with `!` (e.g. `feat!:`, `fix!:`) | Major |
| `feat:` | Minor |
| `fix:`, `chore:`, `ci:`, `docs:`, etc. | Patch |
| No new commits | Skipped |

You can also trigger a release manually from the GitHub Actions tab and choose the bump type explicitly.

## Licence

MIT
