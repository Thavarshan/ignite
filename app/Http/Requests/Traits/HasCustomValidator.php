<?php

namespace App\Http\Requests\Traits;

use App\Support\InteractsWithContainer;
use Closure;
use Illuminate\Contracts\Validation\Factory as FactoryInterface;
use Illuminate\Contracts\Validation\Validator as ValidatorInterface;

trait HasCustomValidator
{
    use InteractsWithContainer;

    /**
     * Validator after hook functions.
     *
     * @var Closure
     */
    protected $afterValidationHook;

    /**
     * The custom key to be used for the view error bag.
     *
     * @var string
     */
    protected $customErrorBag;

    /**
     * Get request validator instance.
     *
     * @param \Illuminate\Contracts\Validation\Factory $factory
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(FactoryInterface $factory): ValidatorInterface
    {
        $validator = $this->makeValidator($factory);

        if ($this->afterValidationHook !== null) {
            $validator = $this->addAfterValidationHook($validator);
        }

        if ($this->customErrorBag !== null) {
            $validator = $this->addCustomErrorBag($validator);
        }

        return $validator;
    }

    /**
     * Set callbacks to be run after validation is completed.
     *
     * @param Closure $callback
     *
     * @return self
     */
    public function afterValidation(Closure $callback): self
    {
        $this->afterValidationHook = $callback;

        return $this;
    }

    /**
     * Specify custom error message bag.
     *
     * @param string $bag
     *
     * @return self
     */
    public function setErrorBag(string $bag): self
    {
        $this->customErrorBag = $bag;

        return $this;
    }

    /**
     * Make new instance of validator.
     *
     * @param \Illuminate\Contracts\Validation\Factory $factory
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function makeValidator(FactoryInterface $factory): ValidatorInterface
    {
        return $factory->make(
            $this->validationData(),
            $this->rules(), // @phpstan-ignore-line
            $this->messages(),
            $this->attributes()
        );
    }

    /**
     * Attach callbacks to be run after validation is completed.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function addAfterValidationHook(
        ValidatorInterface $validator
    ): ValidatorInterface {
        $validator->after($this->afterValidationHook);

        return $validator;
    }

    /**
     * Add custom error message bag.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function addCustomErrorBag(
        ValidatorInterface $validator
    ): ValidatorInterface {
        $validator->validateWithBag($this->customErrorBag); // @phpstan-ignore-line

        return $validator;
    }
}
