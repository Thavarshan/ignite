<?php

namespace App\Watchers;

use App\Enums\Entry;
use App\Support\Newable;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Ignite\Interfaces\Users\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class IncomingEntry implements Arrayable
{
    use Newable;

    /**
     * The entry's batch ID.
     *
     * @var string
     */
    protected $batchId;

    /**
     * The entry's type.
     *
     * @var string
     */
    protected $type;

    /**
     * The entry's family hash.
     *
     * @var string|null
     */
    protected $familyHash;

    /**
     * The currently authenticated user, if applicable.
     *
     * @var mixed
     */
    protected $user;

    /**
     * The DateTime that indicates when the entry was recorded.
     *
     * @var \Carbon\CarbonInterface
     */
    protected $recordedAt;

    /**
     * Create a new incoming entry instance.
     *
     * @param array       $content
     * @param string|null $uuid
     *
     * @return void
     */
    public function __construct(
        protected array $content,
        protected $uuid = null
    ) {
        $this->uuid = $uuid ? $uuid : (string) Str::orderedUuid();

        $this->recordedAt = Carbon::now();
    }

    /**
     * Assign the entry a given batch ID.
     *
     * @param string $batchId
     *
     * @return self
     */
    public function setBatchId(string $batchId): self
    {
        $this->batchId = $batchId;

        return $this;
    }

    /**
     * Assign the entry a given type.
     *
     * @param \App\Enums\Entry $type
     *
     * @return self
     */
    public function setType(Entry $type): self
    {
        $this->type = $type->value;

        return $this;
    }

    /**
     * Assign the entry a family hash.
     *
     * @param string|null $familyHash
     *
     * @return self
     */
    public function withFamilyHash(?string $familyHash): self
    {
        $this->familyHash = $familyHash;

        return $this;
    }

    /**
     * Set the currently authenticated user.
     *
     * @param \Ignite\Interfaces\Users\User|null $user
     *
     * @return self
     */
    public function setUser(?User $user): self
    {
        if (! $user) {
            return $this;
        }

        $this->user = $user;

        $this->content = array_merge($this->content, [
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'auth_identifier' => 'Auth:' . $user->getAuthIdentifier(),
            ],
        ]);

        return $this;
    }

    /**
     * Get the entry's UUID.
     *
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * Get the entry's batch ID.
     *
     * @return string
     */
    public function getBatchId(): string
    {
        return $this->batchId;
    }

    /**
     * Get the entry's type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the content of the entry.
     *
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * Get the family look-up hash for the incoming entry.
     *
     * @return string|null
     */
    public function getFamilyHash(): ?string
    {
        return $this->familyHash;
    }

    /**
     * Get the DateTime that indicates when the entry was recorded.
     *
     * @return \Carbon\CarbonInterface
     */
    public function getRecordedAt(): CarbonInterface
    {
        return $this->recordedAt;
    }

    /**
     * Get the currently authenticated user, if applicable.
     *
     * @return \Ignite\Interfaces\Users\User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Get an array representation of the entry for storage.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'uuid' => $this->uuid,
            'batch_id' => $this->batchId,
            'family_hash' => $this->familyHash,
            'type' => $this->type,
            'content' => $this->content,
            'recorded_at' => $this->recordedAt->toDateTimeString(),
        ];
    }
}
