<?php

namespace Ignite\Statuses\Enums;

use Ignite\Shared\Enums\Traits\FetchAll;
use InvalidArgumentException;

enum Status: string
{
    use FetchAll;

    case INITIATED = 'initiated';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';
    case READY = 'ready';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case PROCESSING = 'processing';
    case PROCESSING_FAILED = 'processing_failed';
    case PROCESSED = 'processed';
    case IN_PROGRESS = 'in_progress';
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case DELETED = 'deleted';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
    case BANNED = 'banned';
    case LOCKED = 'locked';
    case UNLOCKED = 'unlocked';
    case VERIFIED = 'verified';
    case UNVERIFIED = 'unverified';
    case FLAGGED = 'flagged';
    case UNFLAGGED = 'unflagged';

    /**
     * Check if the given status is valid and return the status as an enum.
     *
     * @param string $status
     *
     * @return \Ignite\Statuses\Enums\Status
     *
     * @throws InvalidArgumentException
     */
    public static function isValid(string $status): Status
    {
        $enum = self::tryFrom($status);

        if (is_null($enum)) {
            throw new InvalidArgumentException("The status `{$status}` is an invalid status.");
        }

        return $enum;
    }
}
