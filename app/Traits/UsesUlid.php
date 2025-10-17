<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UsesUlid
{
    public static function bootUsesUlid(): void
    {
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::ulid();
            }
        });
    }

    public function initializeUsesUlid(): void
    {
        $this->incrementing = false;
        $this->keyType = 'string';
    }
}
