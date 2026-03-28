<?php

namespace App\Filters;

use Filterable\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ThreadFilter extends Filter
{
    /**
     * Request keys that map straight to filter methods.
     *
     * @var array<int, string>
     */
    protected array $filters = ['channel', 'author', 'q', 'pinned', 'locked', 'unanswered', 'sort_by'];

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->enableFeature('validation');

        $this->setValidationRules([
            'channel' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'q' => ['nullable', 'string', 'max:255'],
            'pinned' => ['nullable', Rule::in(['0', '1'])],
            'locked' => ['nullable', Rule::in(['0', '1'])],
            'unanswered' => ['nullable', Rule::in(['0', '1'])],
            'sort_by' => ['nullable', Rule::in(['latest', 'oldest', 'popular'])],
        ]);
    }

    /**
     * Override getFilterables to keep "0" string values that array_filter()
     * would drop as falsy, while still excluding null and empty strings.
     *
     * @return array<string, mixed>
     */
    public function getFilterables(): array
    {
        $filterKeys = array_merge(
            $this->getFilters(),
            array_keys($this->filterMethodMap ?? [])
        );

        $this->filterables = array_merge(
            $this->filterables,
            array_filter(
                $this->request->only($filterKeys),
                fn (mixed $value) => $value !== null && $value !== '',
            )
        );

        $this->currentFilters = array_keys($this->filterables);

        return $this->filterables;
    }

    /**
     * Filter threads by channel slug.
     */
    protected function channel(string $slug): void
    {
        $this->getBuilder()->whereHas('channel', function (Builder $query) use ($slug): void {
            $query->where('slug', $slug);
        });
    }

    /**
     * Filter threads by author name.
     */
    protected function author(string $name): void
    {
        $this->getBuilder()->whereHas('user', function (Builder $query) use ($name): void {
            $query->where('name', 'like', "%{$name}%");
        });
    }

    /**
     * Search threads by title or body.
     */
    protected function q(string $term): void
    {
        $this->getBuilder()->where(function (Builder $query) use ($term): void {
            $query->where('title', 'like', "%{$term}%")
                ->orWhere('body', 'like', "%{$term}%");
        });
    }

    /**
     * Filter threads by pinned state.
     * ?pinned=1 → only pinned, ?pinned=0 → only unpinned, omit → all.
     */
    protected function pinned(string $value): void
    {
        $this->getBuilder()->where('pinned', $value === '1');
    }

    /**
     * Filter threads by locked state.
     * ?locked=1 → only locked, ?locked=0 → only unlocked, omit → all.
     */
    protected function locked(string $value): void
    {
        $this->getBuilder()->where('locked', $value === '1');
    }

    /**
     * Filter threads by answered state.
     * ?unanswered=1 → no replies, ?unanswered=0 → has replies, omit → all.
     */
    protected function unanswered(string $value): void
    {
        if ($value === '1') {
            $this->getBuilder()->where('replies_count', 0);
        } else {
            $this->getBuilder()->where('replies_count', '>', 0);
        }
    }

    /**
     * Sort threads using a forum-friendly ordering strategy.
     */
    protected function sortBy(string $value): void
    {
        match ($value) {
            'popular' => $this->getBuilder()->orderByDesc('replies_count')->orderByDesc('visits'),
            'oldest' => $this->getBuilder()->orderBy('created_at'),
            default => $this->getBuilder()->orderByDesc('last_posted_at')->orderByDesc('created_at'),
        };
    }
}
