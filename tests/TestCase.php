<?php

namespace Tests;

use Tests\Concerns\AuthenticatesUser;
use Tests\Concerns\InteractsWithNetwork;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Concerns\InteractsWithProtectedQualities;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use InteractsWithProtectedQualities;
    use InteractsWithNetwork;
    use AuthenticatesUser;
    use WithFaker;
}
