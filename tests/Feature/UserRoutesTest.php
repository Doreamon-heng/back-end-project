<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserRoutesTest extends TestCase
{
    public function test_delete_users_route_is_registered(): void
    {
        $route = app('router')->getRoutes()->getByName('users.destroy.collection');

        $this->assertNotNull($route);
        $this->assertContains('DELETE', $route->methods());
    }
}
