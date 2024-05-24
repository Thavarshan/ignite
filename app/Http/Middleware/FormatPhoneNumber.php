<?php

namespace App\Http\Middleware;

use App\Support\Loggable;
use Closure;
use Illuminate\Http\Request;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpFoundation\Response;

class FormatPhoneNumber
{
    use Loggable;

    /**
     * The default region for phone numbers.
     */
    public const REGION = 'GB';

    /**
     * The attributes that should be formatted as phone numbers.
     *
     * @var array<string>
     */
    protected array $attributes = [
        'phone',
        'mobile',
        'telephone',
    ];

    /**
     * The phone number util instance.
     *
     * @var \libphonenumber\PhoneNumberUtil
     */
    protected PhoneNumberUtil $util;

    /**
     * Create a new middleware instance.
     *
     * @param \libphonenumber\PhoneNumberUtil|null $util
     *
     * @return void
     */
    public function __construct(?PhoneNumberUtil $util = null)
    {
        $this->util = $util ? $util : PhoneNumberUtil::getInstance();
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
        collect($this->getAttributes())
            ->each(function ($attribute) use ($request) {
                if (! $request->has($attribute)) {
                    return;
                }

                try {
                    $numberProto = $this->util->parse(
                        $request->input($attribute),
                        self::REGION // Set to 'GB' currently.
                    ); // Change 'US' to the default region you expect the numbers to come from if not specified

                    $formattedNumber = $this->util->format(
                        $numberProto,
                        PhoneNumberFormat::E164
                    );

                    // Replace the original phone number with the formatted one
                    $request->merge([$attribute => $formattedNumber]);
                } catch (NumberParseException $e) {
                    $this->logThrowable($e);
                }
            });

        return $next($request);
    }

    /**
     * Get the attributes that should be formatted as phone numbers.
     *
     * @return array<string>
     */
    public function getAttributes(): array
    {
        return array_merge($this->attributes, config('attributes.phone', []));
    }

    /**
     * Set the attributes that should be formatted as phone numbers.
     *
     * @param array<string> $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }
}
