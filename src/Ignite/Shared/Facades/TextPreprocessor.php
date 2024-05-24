<?php

namespace Ignite\Shared\Facades;

use Ignite\Shared\Support\TextPreprocessor as TextPreprocessorInstance;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string preprocess(string $text)
 */
class TextPreprocessor extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return TextPreprocessorInstance::class;
    }
}
