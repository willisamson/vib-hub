<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** Frei angelegte Inhaltsseite unter /seite/{slug}. */
class Page extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'slug', 'excerpt', 'body', 'en', 'is_published'];

    protected function casts(): array
    {
        return [
            'en' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
