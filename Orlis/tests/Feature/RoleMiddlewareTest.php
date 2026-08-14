<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_users_can_access_admin_routes(): void
    {
        Route::middleware('role:admin')->get('/admin-only', fn() => 'admin');

        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/admin-only')
            ->assertOk()
            ->assertSee('admin');
    }

    public function test_non_admin_users_cannot_access_admin_routes(): void
    {
        Route::middleware('role:admin')->get('/admin-only', fn() => 'admin');

        $user = User::factory()->create(['role' => 'customer']);

        $this->actingAs($user)
            ->get('/admin-only')
            ->assertForbidden();
    }
}
