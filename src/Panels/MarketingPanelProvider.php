<?php

namespace TrackAnyDevice\Admin\Panels;

use TrackAnyDevice\Admin\DepartmentPanelProvider;
use TrackAnyDevice\Admin\Filament\Resources\Cms\BlogResource;
use TrackAnyDevice\Admin\Filament\Resources\Cms\IndustryResource;
use TrackAnyDevice\Admin\Filament\Resources\Cms\NavLinkResource;
use TrackAnyDevice\Admin\Filament\Resources\Cms\PageResource;
use TrackAnyDevice\Admin\Filament\Resources\Cms\SolutionResource;
use TrackAnyDevice\Admin\Filament\Resources\Cms\TestimonialResource;
use TrackAnyDevice\Admin\Filament\Resources\ContactSubmissions\ContactSubmissionResource;
use TrackAnyDevice\Admin\Filament\Resources\Subscribers\SubscriberResource;
use Filament\Support\Colors\Color;

class MarketingPanelProvider extends DepartmentPanelProvider
{
    protected static function departmentId(): string
    {
        return 'marketing';
    }

    protected static function departmentResources(): array
    {
        return [
            BlogResource::class,
            PageResource::class,
            IndustryResource::class,
            SolutionResource::class,
            TestimonialResource::class,
            NavLinkResource::class,
            SubscriberResource::class,
            ContactSubmissionResource::class,
        ];
    }

    protected static function departmentColor(): array
    {
        return [
            'primary' => Color::Purple,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
            'danger' => Color::Red,
            'info' => Color::Sky,
            'gray' => Color::Neutral,
        ];
    }
}
