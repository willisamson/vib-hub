<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use App\Support\Catalog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Norm extends Model
{
    use HasTranslations;

    protected $fillable = [
        'code', 'slug', 'title', 'category', 'excerpt', 'body', 'parameters',
        'is_published', 'sort', 'en',
    ];

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
            'is_published' => 'boolean',
            'sort' => 'integer',
            'en' => 'array',
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

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort')->orderBy('code');
    }

    public function categoryLabel(): string
    {
        return Catalog::label(Catalog::NORM_CATEGORIES, $this->category);
    }
}
