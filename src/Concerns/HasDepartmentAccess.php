<?php

namespace TrackAnyDevice\Admin\Concerns;

/**
 * Filament admin access — role-based (single-Role model, Workstream F).
 *
 * Admin + Core may view/manage every resource; only Admin may delete. Department gating is gone;
 * the per-resource getAllowedDepartments() overrides are now vestigial (ignored) and get deleted
 * in the final StaffDepartment cleanup once the enum is removed from package-core.
 */
trait HasDepartmentAccess
{
    public static function canAccess(): bool
    {
        return (bool) (auth()->user()?->role?->canAccessFilament());
    }

    public static function canDeleteAny(): bool
    {
        return (bool) (auth()->user()?->role?->canDeleteInFilament());
    }

    public static function canDelete($record): bool
    {
        return (bool) (auth()->user()?->role?->canDeleteInFilament());
    }

    /** @deprecated department gating removed; retained so legacy overrides still compile. */
    protected static function getAllowedDepartments(): array
    {
        return [];
    }
}
