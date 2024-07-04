<?php

namespace Ignite\Settings\Services;

use ArrayObject;
use Ignite\Interfaces\Settings\HasSettings;
use Ignite\Interfaces\Settings\SettingsFilter;
use Ignite\Interfaces\Settings\SettingsRepository;
use Ignite\Interfaces\Settings\SettingsService as SettingsServiceInterface;
use Ignite\Shared\Data\Services\Service;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class SettingsService extends Service implements SettingsServiceInterface
{
    /**
     * The entity considered by the service.
     *
     * @var \Ignite\Interfaces\Settings\HasSettings
     */
    protected $entity;

    /**
     * Create a new service instance.
     *
     * @param \Ignite\Interfaces\Settings\SettingsRepository $repository
     *
     * @return void
     */
    public function __construct(SettingsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List all data entries after performing requested filters.
     *
     * @param \Ignite\Interfaces\Settings\SettingsFilter $filter
     * @param bool                                       $paginated
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Contracts\Pagination\Paginator
     */
    public function list(
        SettingsFilter $filter,
        bool $paginated = false
    ): Builder|Paginator {
        $query = $this->repository
            ->list($filter)
            ->latest();

        if ($paginated) {
            return $query->paginate();
        }

        return $query;
    }

    /**
     * Set the entity considered by the service to extraxt settings for.
     *
     * @param \Ignite\Interfaces\Settings\HasSettings $entity
     *
     * @return self
     */
    public function of(HasSettings $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get the settings items for the entity considered by the service.
     *
     * @return array
     */
    public function get(): array
    {
        $items = $this->repository // @phpstan-ignore-line
            ->getModel()
            ->whereHasMorph(
                'subject',
                get_class($this->entity),
                function (Builder $query) {
                    $query->where(
                        'subject_id',
                        $this->entity->getSubjectId()
                    );
                }
            )
            ->first()
            ?->getItems() ?? [];

        if ($items instanceof ArrayObject) {
            $items = $items->getArrayCopy();
        }

        return $items;
    }
}
