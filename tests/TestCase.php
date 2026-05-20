<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function login(): User|Authenticatable
    {
        /** @var User|Authenticatable $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }
}
