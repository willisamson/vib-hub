<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** Shaker-Modell für die Beispielauslegung (Admin → Shaker-Modelle). */
class ShakerModel extends Model
{
    protected $fillable = [
        'manufacturer', 'name', 'sine_force_kn', 'random_force_kn', 'shock_force_kn',
        'displacement_mm', 'shock_displacement_mm', 'velocity_ms', 'shock_velocity_ms',
        'moving_mass_kg', 'max_load_kg', 'f_min', 'f_max', 'table_mm', 'cooling', 'is_active', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'sine_force_kn' => 'float', 'random_force_kn' => 'float', 'shock_force_kn' => 'float',
            'displacement_mm' => 'float', 'shock_displacement_mm' => 'float',
            'velocity_ms' => 'float', 'shock_velocity_ms' => 'float',
            'moving_mass_kg' => 'float', 'max_load_kg' => 'float',
            'f_min' => 'integer', 'f_max' => 'integer', 'table_mm' => 'integer',
            'is_active' => 'boolean', 'sort' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sine_force_kn')->orderBy('sort');
    }

    /** Daten für die Auslegung im Browser. */
    public function toPlanner(): array
    {
        return [
            'name' => trim($this->manufacturer.' '.$this->name),
            'sine' => $this->sine_force_kn,
            'random' => $this->random_force_kn,
            'shock' => $this->shock_force_kn,
            'disp' => $this->displacement_mm,
            'dispShock' => $this->shock_displacement_mm ?: $this->displacement_mm,
            'vel' => $this->velocity_ms,
            'velShock' => $this->shock_velocity_ms ?: $this->velocity_ms,
            'moving' => $this->moving_mass_kg,
            'load' => $this->max_load_kg,
            'fMin' => $this->f_min,
            'fMax' => $this->f_max,
            'table' => $this->table_mm,
            'cooling' => $this->cooling,
        ];
    }
}
