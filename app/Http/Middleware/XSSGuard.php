<?php

namespace App\Http\Middleware;

use Closure;
use GrahamCampbell\SecurityCore\Security;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XSSGuard
{
    /**
     * The security instance.
     *
     * @var \GrahamCampbell\SecurityCore\Security
     */
    protected $security;

    /**
     * Create a new instance.
     *
     * @param \GrahamCampbell\SecurityCore\Security $security
     *
     * @return void
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request                                                        $request
     * @param Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $values = $request->all();

        $this->clean($values, true, true);

        $request->merge($values);

        return $next($request);
    }

    /**
     * Clean a specified value or values.
     *
     * @param mixed $value
     * @param bool  $trim
     * @param bool  $clean
     *
     * @return mixed
     */
    public function clean(
        mixed $value,
        bool $trim = true,
        bool $clean = true
    ): mixed {
        if (
            $value === null
            || is_bool($value)
            || is_int($value)
            || is_float($value)
        ) {
            return $value;
        }

        if (! is_array($value)) {
            return $this->process((string) $value, $trim, $clean);
        }

        $final = [];

        foreach ($value as $k => $v) {
            if ($v !== null) {
                $final[$k] = $this->clean($v, $trim, $clean);
            }
        }

        return $final;
    }

    /**
     * Process a specified value.
     *
     * @param string $value
     * @param bool   $trim
     * @param bool   $clean
     *
     * @return string
     */
    protected function process(string $value, bool $trim, bool $clean): string
    {
        if ($trim) {
            $value = trim($value);
        }

        if ($clean) {
            $value = $this->security->clean($value);
        }

        if ($trim) {
            $value = trim($value);
        }

        return $value;
    }
}
