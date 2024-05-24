<?php

namespace App\Clients;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Illuminate\Http\Client\PendingRequest as IlluminatePendingRequest;

class PendingRequest extends IlluminatePendingRequest
{
    /**
     * The custom handler stack callback.
     *
     * @var \GuzzleHttp\HandlerStack|null
     */
    protected $customHandlerStack;

    /**
     * Build the Guzzle client handler stack.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    public function buildHandlerStack()
    {
        return $this->pushHandlers(
            $this->createCustomHandlerStack() ?? HandlerStack::create($this->handler)
        );
    }

    /**
     * Set the custom handler stack.
     *
     * @param \GuzzleHttp\HandlerStack $handlerStack
     *
     * @return self
     */
    public function setCustomHandlerStack(HandlerStack $handlerStack): self
    {
        $this->customHandlerStack = $handlerStack;

        return $this;
    }

    /**
     * Get the underlying Guzzle client instance.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getClientInstance(): ClientInterface
    {
        return $this->getReusableClient();
    }

    /**
     * Create a custom handler stack.
     *
     * @return \GuzzleHttp\HandlerStack|null
     */
    protected function createCustomHandlerStack(): ?HandlerStack
    {
        return $this->customHandlerStack;
    }
}
