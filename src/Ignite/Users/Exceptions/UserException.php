<?php

namespace Ignite\Users\Exceptions;

use App\Exceptions\Traits\HasRenderables;
use App\Interfaces\Exceptions\RenderableException;
use DomainException;

class UserException extends DomainException implements RenderableException
{
    use HasRenderables;
}
