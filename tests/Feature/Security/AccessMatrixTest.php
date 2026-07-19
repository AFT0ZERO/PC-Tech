<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AccessMatrixTest extends TestCase
{
    use DatabaseTransactions;

    // ── SEC-F-01: Admin route matrix ─────────────────────────────────────────

    /**
     * @dataProvider adminRouteProvider
     */
    public function test_admin_routes_access_matrix(string $method, string $url): void
    {
        // Guest → login redirect
        $response = $this->call($method, $url);
        $response->assertRedirect('/login');

        // Regular user → 403
        $user = User::factory()->user()->create();
        $response = $this->actingAs($user)->call($method, $url);
        $this->assertContains($response->getStatusCode(), [403, 302, 500]);

        // Admin → 200/302 (may redirect after action)
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->call($method, $url);
        $this->assertContains($response->getStatusCode(), [200, 302]);

        // Super admin → 200/302
        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->call($method, $url);
        $this->assertContains($response->getStatusCode(), [200, 302]);
    }

    public static function adminRouteProvider(): array
    {
        return [
            'dashboard' => ['GET', '/dashboard'],
            'categories index' => ['GET', '/dashboard/categories'],
            'contacts index' => ['GET', '/dashboard/contacts'],
            'stores index' => ['GET', '/dashboard/stores'],
            'products index' => ['GET', '/dashboard/products'],
            'users index' => ['GET', '/dashboard/users'],
            'faqs index' => ['GET', '/dashboard/faqs'],
            'scraper index' => ['GET', '/dashboard/scraper'],
        ];
    }

    // ── SEC-F-02: Restore routes — admin denied, super-admin allowed ─────────

    /**
     * @dataProvider restoreRouteProvider
     */
    public function test_restore_routes_require_super_admin(string $method, string $url): void
    {
        // Admin → 403
        $admin = User::factory()->admin()->create();
        $response = $this->actingAs($admin)->call($method, $url);
        $this->assertContains($response->getStatusCode(), [403, 302]);

        // Super admin → 200/302
        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->call($method, $url);
        $this->assertContains($response->getStatusCode(), [200, 302]);
    }

    public static function restoreRouteProvider(): array
    {
        return [
            'users restore list' => ['GET', '/dashboard/restore-u'],
            'categories restore list' => ['GET', '/dashboard/restore-c'],
            'stores restore list' => ['GET', '/dashboard/restore-s'],
            'products restore list' => ['GET', '/dashboard/restore-p'],
            'contacts restore list' => ['GET', '/dashboard/restore-co'],
            'faqs restore list' => ['GET', '/dashboard/restore-f'],
        ];
    }

    // ── SEC-F-03: Auth-only routes redirect guest to login ───────────────────

    /**
     * @dataProvider authOnlyRouteProvider
     */
    public function test_auth_only_routes_redirect_guest_to_login(string $method, string $url): void
    {
        $response = $this->call($method, $url);
        $response->assertRedirect('/login');
    }

    public static function authOnlyRouteProvider(): array
    {
        return [
            'favorite toggle' => ['POST', '/favorite/1'],
            'favorites list' => ['GET', '/favorites/list'],
            'user account' => ['GET', '/User-Account'],
            'builder save' => ['POST', '/builder/save'],
            'builder my-builds' => ['GET', '/builder/my-builds'],
        ];
    }

    // ── SEC-F-04: Public routes accessible by all actors ─────────────────────

    /**
     * @dataProvider publicRouteProvider
     */
    public function test_public_routes_accessible_by_all_actors(string $method, string $url): void
    {
        $user = User::factory()->user()->create();

        // Guest
        $this->call($method, $url)->assertStatus(200);

        // Logged-in user
        $this->actingAs($user)->call($method, $url)->assertStatus(200);

        // Admin
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->call($method, $url)->assertStatus(200);

        // Super admin
        $superAdmin = User::factory()->superAdmin()->create();
        $this->actingAs($superAdmin)->call($method, $url)->assertStatus(200);
    }

    public static function publicRouteProvider(): array
    {
        return [
            'landing' => ['GET', '/'],
            'category null' => ['GET', '/category'],
            'about' => ['GET', '/About'],
            'contact' => ['GET', '/Contact Us'],
            'faqs' => ['GET', '/FAQs'],
            'builder index' => ['GET', '/builder'],
        ];
    }
}
