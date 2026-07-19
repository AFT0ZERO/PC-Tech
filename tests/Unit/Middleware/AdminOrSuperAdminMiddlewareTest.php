<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\AdminOrSuperAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class AdminOrSuperAdminMiddlewareTest extends TestCase
{
    public function test_passes_for_admin(): void
    {
        $user = User::factory()->admin()->create();

        Auth::login($user);

        $middleware = new AdminOrSuperAdmin;
        $request = Request::create('/dashboard', 'GET');
        $response = $middleware->handle($request, fn () => response('ok'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('ok', $response->getContent());
    }

    public function test_passes_for_super_admin(): void
    {
        $user = User::factory()->superAdmin()->create();

        Auth::login($user);

        $middleware = new AdminOrSuperAdmin;
        $request = Request::create('/dashboard', 'GET');
        $response = $middleware->handle($request, fn () => response('ok'));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('ok', $response->getContent());
    }

    public function test_denies_for_regular_user(): void
    {
        $user = User::factory()->user()->create();

        Auth::login($user);

        $middleware = new AdminOrSuperAdmin;
        $request = Request::create('/dashboard', 'GET');

        $this->expectException(HttpException::class);

        $middleware->handle($request, fn () => response('ok'));
    }

    public function test_denies_for_guest(): void
    {
        $middleware = new AdminOrSuperAdmin;
        $request = Request::create('/dashboard', 'GET');

        $this->expectException(HttpException::class);

        $middleware->handle($request, fn () => response('ok'));
    }
}
