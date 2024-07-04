<?php

namespace Ignite\Shared\Data\DTO;

use Ignite\Interfaces\Shared\Vehicle as VehicleInterface;

class Vehicle extends DTO implements VehicleInterface
{
    /**
     * Get the vehicle registration.
     *
     * @return string
     */
    public function getRegistration(): string
    {
        return $this->getData('registration');
    }

    /**
     * Get the vehicle make.
     *
     * @return string
     */
    public function getMake(): string
    {
        return $this->getData('make');
    }

    /**
     * Get the vehicle model.
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->getData('model');
    }
}
