<?php

namespace App\Support;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Spatie\Csp\Nonce\NonceGenerator as NonceGeneratorInterface;

class NonceGenerator implements NonceGeneratorInterface
{
    /**
     * Generate a nonce string.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes/nonce
     *
     * @return string
     */
    public function generate(): string
    {
        return tap('nonce-' . base64_encode(Str::random(32)), function (string $nonce) {
            Vite::useCspNonce($nonce);
        });
    }
}
