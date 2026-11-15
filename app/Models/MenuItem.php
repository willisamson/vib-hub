<?php

namespace App\Models;

use App\Support\Site;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Route;

/** Menüpunkt für Kopf- oder Fußzeile, im Admin unter „Menüs“ pflegbar. */
class MenuItem extends Model
{
    protected $fillable = [
        'location', 'label', 'label_en', 'type', 'route', 'page_id', 'url',
        'new_tab', 'highlight', 'is_active', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'new_tab' => 'boolean',
            'highlight' => 'boolean',
            'is_active' => 'boolean',
            'sort' => 'integer',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('id');
    }

    /** Beschriftung in der aktuellen Sprache. */
    public function text(): string
    {
        if (app()->getLocale() === 'en') {
            return filled($this->label_en) ? $this->label_en : __($this->label);
        }

        return $this->label;
    }

    /** Ziel-URL oder null, wenn das Ziel (noch) nicht erreichbar ist. */
    public function href(): ?string
    {
        return match ($this->type) {
            'route' => $this->routeHref(),
            'page' => $this->page && $this->page->is_published ? lroute('page', $this->page) : null,
            'url' => filled($this->url) ? $this->url : null,
            default => null,
        };
    }

    protected function routeHref(): ?string
    {
        if (! $this->route || ! Route::has($this->route)) {
            return null;
        }

        if (str_starts_with($this->route, 'account.') && ! app(Site::class)->accountsEnabled()) {
            return null;
        }

        // Die App-Seite gibt es nur mit hinterlegtem Store-Link.
        if ($this->route === 'app' && ! app(Site::class)->appUrl()) {
            return null;
        }

        return lroute($this->route);
    }

    public function isCurrent(): bool
    {
        if ($this->type === 'page') {
            return request()->routeIs('page', 'en.page')
                && optional(request()->route('page'))->getKey() === $this->page_id;
        }

        if ($this->type !== 'route' || ! $this->route) {
            return false;
        }

        $base = str_ends_with($this->route, '.index') ? substr($this->route, 0, -6).'.*' : $this->route;
        if ($this->route === 'account.dashboard') {
            $base = 'account.*';
        }

        return request()->routeIs($this->route, $base, 'en.'.$this->route, 'en.'.$base);
    }

    public function isExternal(): bool
    {
        return $this->type === 'url' && preg_match('#^https?://#i', (string) $this->url)
            && ! str_starts_with((string) $this->url, url('/'));
    }
}
