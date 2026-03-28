<?php

namespace App\Filters;

use Filterable\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReplyFilter extends Filter
{
    protected array $filters = ['author', 'q'];

    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->enableFeature('validation');

        $this->setValidationRules([
            'author' => ['nullable', 'string', 'max:255'],
            'q' => ['nullable', 'string', 'max:255'],
        ]);
    }

    protected function author(string $name): void
    {
        $this->getBuilder()->whereHas('user', function (Builder $query) use ($name): void {
            $query->where('name', 'like', "%{$name}%");
        });
    }

    protected function q(string $term): void
    {
        $this->getBuilder()->where('body', 'like', "%{$term}%");
    }
}
