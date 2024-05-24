<?php

namespace App\View;

use App\Support\Loggable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\Factory as FactoryInterface;
use Illuminate\View\Concerns\ManagesEvents;
use Illuminate\View\ViewFinderInterface;
use Inertia\ResponseFactory;
use InvalidArgumentException;

class Factory extends ResponseFactory implements FactoryInterface
{
    use ManagesEvents;
    use Loggable;

    /**
     * The view finder implementation.
     *
     * @var \Illuminate\View\ViewFinderInterface
     */
    protected $finder;

    /**
     * Create a new view factory instance.
     *
     * @param \Illuminate\View\ViewFinderInterface $finder
     *
     * @return void
     */
    public function __construct(ViewFinderInterface $finder)
    {
        $this->configureFinder($finder);
    }

    /**
     * Configure the view finder implementation.
     *
     * @param \Illuminate\View\ViewFinderInterface $finder
     *
     * @return void
     */
    public function configureFinder(ViewFinderInterface $finder): void
    {
        $finder->addLocation(resource_path('js/pages'));

        $this->finder = $finder;
    }

    /**
     * Determine if a given view exists.
     *
     * @param string $view
     *
     * @return bool
     */
    public function exists($view)
    {
        try {
            $this->finder->find($view);
        } catch (InvalidArgumentException $e) {
            $this->logError($e->getMessage(), [
                'exception' => $e,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Get the evaluated view contents for the given path.
     *
     * @param string                                        $path
     * @param \Illuminate\Contracts\Support\Arrayable|array $data
     * @param array                                         $mergeData
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function file($path, $data = [], $mergeData = [])
    {
        return $this->make(
            $this->finder->find($path),
            $data,
            $mergeData
        );
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string                                        $view
     * @param \Illuminate\Contracts\Support\Arrayable|array $data
     * @param array                                         $mergeData
     *
     * @return \Illuminate\Contracts\View\View|\Inertia\Response
     */
    public function make($view, $data = [], $mergeData = [])
    {
        return $this->render(
            $view,
            array_merge($mergeData, $this->parseData($data))
        );
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param string       $namespace
     * @param string|array $hints
     *
     * @return self
     */
    public function addNamespace($namespace, $hints)
    {
        $this->finder->addNamespace($namespace, $hints);

        return $this;
    }

    /**
     * Replace the namespace hints for the given namespace.
     *
     * @param string       $namespace
     * @param string|array $hints
     *
     * @return self
     */
    public function replaceNamespace($namespace, $hints)
    {
        $this->finder->replaceNamespace($namespace, $hints);

        return $this;
    }

    /**
     * Parse the given data into a raw array.
     *
     * @param mixed $data
     *
     * @return array
     */
    protected function parseData(mixed $data): array
    {
        return $data instanceof Arrayable ? $data->toArray() : $data;
    }
}
