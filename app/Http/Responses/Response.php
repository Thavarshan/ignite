<?php

namespace App\Http\Responses;

use App\Providers\RouteServiceProvider;
use App\Support\Arr;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inertia\Response as InertiaResponse;
use Inertia\ResponseFactory as InertiaResponseFactory;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

abstract class Response extends ResponseFactory
{
    /**
     * The name of the view / component that should be rendered.
     *
     * @var string|null
     */
    protected $viewName;

    /**
     * The optional banner message for the response.
     *
     * @var string|null
     */
    protected $message;

    /**
     * The resource key for the response.
     *
     * @var string|null
     */
    protected $resourceKey;

    /**
     * Create a new response factory instance.
     *
     * @param \Illuminate\Contracts\View\Factory $view
     * @param \Illuminate\Routing\Redirector     $redirector
     * @param mixed|null                         $content
     *
     * @return void
     */
    public function __construct(
        ViewFactory $view,
        Redirector $redirector,
        protected mixed $content = null
    ) {
        parent::__construct($view, $redirector);
    }

    /**
     * Dispatch response.
     *
     * @param mixed|null  $content
     * @param int|int     $status
     * @param string|null $viewName
     * @param string|null $message
     * @param string|null $resourceKey
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|static
     */
    public static function dispatch(
        mixed $content = null,
        ?int $status = SymfonyResponse::HTTP_OK,
        ?string $viewName = null,
        ?string $message = null,
        ?string $resourceKey = null
    ): SymfonyResponse|RedirectResponse|JsonResponse|Responsable|static {
        $response = static::create($content, $viewName, $message, $resourceKey);

        if ($response->expectedAsJson()) {
            return $response->asJson($status);
        }

        if ($response instanceof Responsable) {
            return $response->toResponse(request());
        }

        return $response;
    }

    /**
     * Create a new response instance.
     *
     * @param mixed|null  $content
     * @param string|null $viewName
     * @param string|null $message
     * @param string|null $resourceKey
     *
     * @return static
     */
    public static function create(
        mixed $content = null,
        ?string $viewName = null,
        ?string $message = null,
        ?string $resourceKey = null
    ): static {
        // @phpstan-ignore-next-line
        return tap(new static(
            app(InertiaResponseFactory::class),
            app(Redirector::class),
            $content,
        ), function ($instance) use ($viewName, $message, $resourceKey) {
            if (! is_null($viewName)) {
                $instance->setViewName($viewName);
            }

            if (! is_null($message)) {
                $instance->setMessage($message);
            }

            if (! is_null($resourceKey)) {
                $instance->setResourceKey($resourceKey);
            }
        });
    }

    /**
     * Determine the type of content and make an appropriate JSON response.
     *
     * @param int $status
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function asJson(
        int $status = SymfonyResponse::HTTP_OK
    ): SymfonyResponse|JsonResponse {
        if ($status === SymfonyResponse::HTTP_NO_CONTENT) {
            return $this->noContent();
        }

        if ($this->contentIsCollection()) {
            return JsonResource::collection(
                $this->getContentForJsonResponse()
            )
                ->response()
                ->setStatusCode($status);
        }

        return new JsonResponse(
            $this->getContentForJsonResponse(),
            $status
        );
    }

    /**
     * Determine if the content is a collection.
     *
     * @return bool
     */
    public function contentIsCollection(): bool
    {
        $content = $this->getContent();

        if (
            is_array($content)
            && count(array_keys($content)) === 1
            && (count(array_values($content)) > 1
                || Arr::first($content) instanceof Paginator)
        ) {
            return true;
        }

        return $content instanceof Collection
            || (! is_null(Arr::get($content, 'data'))
                && ! $content instanceof Model);
    }

    /**
     * Full URL path to home route.
     *
     * @return string
     */
    public function home(): string
    {
        return url(RouteServiceProvider::HOME);
    }

    /**
     * Get instance of routin redirector class.
     *
     * @return \Illuminate\Routing\Redirector
     */
    public function redirect(): Redirector
    {
        return $this->redirector;
    }

    /**
     * Redirect to home route.
     *
     * @param int   $status
     * @param array $headers
     * @param bool  $secure
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectHome(
        int $status = SymfonyResponse::HTTP_SEE_OTHER,
        array $headers = [],
        ?bool $secure = null
    ): RedirectResponse {
        return $this->redirect()->to(
            $this->home(),
            $status,
            $headers,
            $secure
        );
    }

    /**
     * Create a new redirect response to the previous location.
     *
     * @param int   $status
     * @param array $headers
     * @param bool  $fallback
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function back(
        int $status = SymfonyResponse::HTTP_SEE_OTHER,
        array $headers = [],
        bool $fallback = false
    ): RedirectResponse {
        return $this->redirect()->back($status, $headers, $fallback);
    }

    /**
     * Get the banner message for the response.
     *
     * @param \Illuminate\Http\Request $request
     * @param string                   $resource
     *
     * @return string
     */
    public function bannerMessage(Request $request, string $resource): string
    {
        return match (Str::lower($request->method())) {
            'post' => "{$resource} created successfully.",
            'put' => "{$resource} updated successfully.",
            'delete' => "{$resource} deleted successfully.",
            default => 'Changes saved successfully.',
        };
    }

    /**
     * Determine if the request expects a JSON response.
     *
     * @return bool
     */
    public function expectedAsJson(): bool
    {
        return request()->expectsJson();
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getContent(?string $key = null): mixed
    {
        if (! is_null($key)) {
            return Arr::get($this->content, $key);
        }

        return $this->content;
    }

    /**
     * Get the content and format it for a JSON response.
     *
     * @return mixed
     */
    public function getContentForJsonResponse(): mixed
    {
        $content = $this->getContent();

        if (is_null($content)) {
            return [];
        }

        if (is_object($content) && $content instanceof Arrayable) {
            return $content->toArray();
        }

        if (count(array_keys($content)) !== 1) {
            return $content;
        }

        return Arr::first($content);
    }

    /**
     * Render the view.
     *
     * @param string|null $viewName
     *
     * @return \Inertia\Response|\Illuminate\Contracts\View\View
     */
    public function renderView(?string $viewName = null): InertiaResponse|View
    {
        $viewName = $viewName ?? $this->getViewName();

        if (is_null($viewName)) {
            throw new InvalidArgumentException(
                'A view name must be set to render a view.'
            );
        }

        return $this->view->make(
            $viewName,
            $this->getContent() ? [
                $this->getResourceKey() => $this->getContent(),
            ] : []
        );
    }

    /**
     * Get the resource key for the response.
     *
     * @return string
     */
    public function getResourceKey(): string
    {
        if (! is_null($this->resourceKey)) {
            return $this->resourceKey;
        }

        $className = Str::of(static::class)->afterLast('\\');

        if (Str::endsWith($className, 'CollectionResponse')) {
            return $className->before('CollectionResponse')->plural()->lower();
        }

        if (Str::endsWith($className, 'ViewResponse')) {
            return $className->before('ViewResponse')->singular()->lower();
        }

        if (Str::endsWith($className, 'Response')) {
            return $className->before('Response')->singular()->lower();
        }

        $this->resourceKey = $className->lower();

        return $this->resourceKey;
    }

    /**
     * Set the resource key for the response.
     *
     * @param string $resourceKey
     *
     * @return self
     */
    public function setResourceKey(string $resourceKey): self
    {
        $this->resourceKey = $resourceKey;

        return $this;
    }

    /**
     * Get the name of the view / component that should be rendered.
     *
     * @return string|null
     */
    public function getViewName(): ?string
    {
        return $this->viewName;
    }

    /**
     * Set the name of the view / component that should be rendered.
     *
     * @param string $viewName
     *
     * @return self
     */
    public function setViewName(string $viewName): self
    {
        $this->viewName = $viewName;

        return $this;
    }

    /**
     * Get the optional banner message for the response.
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Set the optional banner message for the response.
     *
     * @param string $message
     *
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
