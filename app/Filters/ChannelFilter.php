<?php

namespace App\Filters;

use Filterable\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ChannelFilter extends Filter
{
    /**
     * Request keys that map straight to filter methods.
     *
     * @var array<int, string>
     */
    protected array $filters = ['q', 'slug'];

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->enableFeature('validation');

        $this->setValidationRules([
            'q' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Search channels by name or description.
     */
    protected function q(string $term): void
    {
        $this->getBuilder()->where(function (Builder $query) use ($term): void {
            $query->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    /**
     * Filter channels by slug.
     */
    protected function slug(string $slug): void
    {
        $this->getBuilder()->where('slug', $slug);
    }
}
