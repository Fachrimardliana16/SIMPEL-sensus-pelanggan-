<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

/**
 * A single-button Quick Action card.
 * Configured via static properties on subclasses, or simply registered multiple times
 * in the panel provider for different buttons using the $panelAction array.
 *
 * We keep one generic widget rendered per-panel via a view that accepts all button data at once.
 * This replaces the old QuickAccessWidget which stacked vertically.
 */
class QuickActionButtonWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-action-button';
    protected static ?int $sort = 1;
    // Each button gets half the screen width (2 cols in a 2-col grid context)
    protected int | string | array $columnSpan = 1;

    /**
     * Override in panel provider widget registration via array config,
     * or by subclassing. Default: a profile link.
     */
    public ?string $label = 'Aksi';
    public ?string $url   = '#';
    public ?string $icon  = 'heroicon-o-bolt';
    public ?string $color = 'primary';
    public ?string $description = null;

    protected function getViewData(): array
    {
        return [
            'label'       => $this->label,
            'url'         => $this->url,
            'icon'        => $this->icon,
            'color'       => $this->color,
            'description' => $this->description,
        ];
    }
}
