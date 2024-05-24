<?php

namespace App\Clients;

use GuzzleHttp\HandlerStack;
use Illuminate\Http\Client\Factory as HttpFactory;

class Factory extends HttpFactory
{
    /**
     * The custom handler stack.
     *
     * @var \GuzzleHttp\HandlerStack|null
     */
    protected $handlerStack;

    /**
     * Set the custom handler stack.
     *
     * @param \GuzzleHttp\HandlerStack $handlerStack
     *
     * @return self
     */
    public function setHandlerStack(HandlerStack $handlerStack): self
    {
        $this->handlerStack = $handlerStack;

        return $this;
    }

    /**
     * Get the custom handler stack.
     *
     * @return \GuzzleHttp\HandlerStack|null
     */
    public function getHandlerStack(): ?HandlerStack
    {
        return $this->handlerStack;
    }

    /**
     * Create a new pending request instance for this factory.
     *
     * @return \App\Clients\PendingRequest
     */
    protected function newPendingRequest()
    {
        parent::newPendingRequest();

        return tap(new PendingRequest(
            $this,
            $this->globalMiddleware
        ), function (PendingRequest $request) {
            $handlerStack = $this->getHandlerStack();

            if ($handlerStack) {
                $request->setCustomHandlerStack($handlerStack);
            }
        });
    }
}
