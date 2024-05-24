<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\AuthorizesRequests;
use App\Http\Requests\Traits\HasCustomValidator;
use App\Http\Requests\Traits\InputValidationRules;
use App\Support\InteractsWithContainer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Traits\Tappable;

abstract class Request extends FormRequest
{
    use AuthorizesRequests;
    use HasCustomValidator;
    use InputValidationRules;
    use InteractsWithContainer;
    use Tappable;
}
