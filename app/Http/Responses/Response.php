<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inertia\Inertia;
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
    protected ?string $viewName;

    /**
     * The optional banner message for the response.
     *
     * @var string|null
     */
    protected ?string $message;

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
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|static
     */
    public static function dispatch(
        mixed $content = null,
        ?int $status = SymfonyResponse::HTTP_OK,
        ?string $viewName = null,
        ?string $message = null
    ): SymfonyResponse|RedirectResponse|JsonResponse|Responsable|static {
        $response = static::create($content, $viewName, $message);

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
     *
     * @return static
     */
    public static function create(
        mixed $content = null,
        ?string $viewName = null,
        ?string $message = null
    ): static {
        // @phpstan-ignore-next-line
        return tap(new static(
            app(InertiaResponseFactory::class),
            app(Redirector::class),
            $content,
        ), function ($instance) use ($viewName, $message) {
            if (! is_null($viewName)) {
                $instance->setViewName($viewName);
            }

            if (! is_null($message)) {
                $instance->setMessage($message);
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
            return JsonResource::collection($this->getContent())
                ->response()
                ->setStatusCode($status);
        }

        return new JsonResponse($this->getContent(), $status);
    }

    /**
     * Determine if the content is a collection.
     *
     * @return bool
     */
    public function contentIsCollection(): bool
    {
        return $this->getContent() instanceof Collection
            || ! is_null($this->getContent('data'));
    }

    /**
     * Full URL path to home route.
     *
     * @return string
     */
    public function home(): string
    {
        return url('/');
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
     * Render the view.
     *
     * @param string|null $viewName
     *
     * @return \Inertia\Response|\Illuminate\Contracts\View\View
     */
    public function renderView(?string $viewName = null): InertiaResponse|View
    {
        $viewName = ! is_null($viewName)
            ? Str::replace('.', '/', $viewName)
            : $this->getViewName();

        if (is_null($viewName)) {
            throw new InvalidArgumentException(
                'A view name must be set to render a view.'
            );
        }

        return Inertia::render($viewName, $this->getContent() ?? []);
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
        $this->viewName = Str::replace('.', '/', $viewName);

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
