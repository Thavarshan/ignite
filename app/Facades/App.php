<?php

namespace App\Facades;

use App\Enums\AppMode;

class App
{
    /**
     * Get the app mode.
     *
     * @return array
     */
    public static function mode(): array
    {
        $modes = array_unique(explode(',', config('app.mode')));

        return array_filter($modes, function (string $mode) {
            return in_array($mode, AppMode::all());
        });
    }

    /**
     * Check if the app mode is enabled.
     *
     * @param \App\Enums\AppMode $mode
     *
     * @return bool
     */
    public static function hasModeEnabled(AppMode $mode): bool
    {
        return in_array($mode->value, self::mode());
    }
}
