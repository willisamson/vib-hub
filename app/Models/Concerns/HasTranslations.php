<?php

namespace App\Models\Concerns;

/**
 * Englische Fassung in der JSON-Spalte "en". tr('title') liefert in der
 * englischen Website den englischen Text, sonst (oder wenn leer) den deutschen.
 */
trait HasTranslations
{
    public function tr(string $field): mixed
    {
        if (app()->getLocale() === 'en') {
            $value = data_get($this->en ?? [], $field);

            if (filled($value) && $value !== [] && $value !== '<p></p>') {
                return $value;
            }
        }

        return $this->{$field};
    }
}
