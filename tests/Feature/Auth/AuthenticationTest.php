<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    // ── AUTH-F-01: Register with valid data ──────────────────────────────────

    public function test_register_creates_user_with_role_user_and_hashed_password(): void
    {
        $response = $this->post('/register', [
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john@example.com',
            'mobile' => '0791234567',
            'gender' => 'male',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertRedirect('/home');

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'fname' => 'John',
            'lname' => 'Doe',
            'mobile' => '0791234567',
            'gender' => 'male',
            'role' => 'user',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('secret', $user->password));
        $this->assertAuthenticated();
    }

    // ── AUTH-F-02: Register validation failures ──────────────────────────────

    public function test_register_requires_all_fields(): void
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['fname', 'lname', 'email', 'mobile', 'gender', 'password']);
    }

    public function test_register_validates_field_lengths_and_formats(): void
    {
        $response = $this->post('/register', [
            'fname' => 'Jo',
            'lname' => 'Do',
            'email' => 'not-an-email',
            'mobile' => '07',    // length 2 < min:9, but min on numeric checks value >= 9 → 7 < 9 → fails
            'gender' => '',
            'password' => 'ab',
            'password_confirmation' => 'cd',
        ]);

        $response->assertSessionHasErrors(['fname', 'lname', 'email', 'gender', 'password']);
    }

    public function test_register_requires_password_confirmation(): void
    {
        $response = $this->post('/register', [
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john@example.com',
            'mobile' => '0791234567',
            'gender' => 'male',
            'password' => 'secret',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_register_requires_numeric_mobile(): void
    {
        $response = $this->post('/register', [
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john@example.com',
            'mobile' => 'not-a-number',
            'gender' => 'male',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertSessionHasErrors(['mobile']);
    }

    // ── AUTH-F-03: Duplicate email registration (DB unique constraint) ──────

    public function test_duplicate_email_causes_database_error(): void
    {
        User::factory()->user()->create(['email' => 'dupe@example.com']);

        // Email has DB UNIQUE constraint but no Laravel unique validation rule.
        // Duplicate insert causes a 500 error.
        $response = $this->post('/register', [
            'fname' => 'Jane',
            'lname' => 'Doe',
            'email' => 'dupe@example.com',
            'mobile' => '0797654321',
            'gender' => 'female',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $response->assertStatus(500);
        $this->assertEquals(1, User::where('email', 'dupe@example.com')->count());
    }

    // ── AUTH-F-04: Login with valid credentials ──────────────────────────────

    public function test_login_with_valid_credentials_redirects_to_landing(): void
    {
        $user = User::factory()->user()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_login_also_redirects_to_landing(): void
    {
        $admin = User::factory()->admin()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($admin);
    }

    // ── AUTH-F-05: Login wrong credentials ──────────────────────────────────

    public function test_login_wrong_password_returns_error(): void
    {
        $user = User::factory()->user()->create([
            'password' => Hash::make('correct'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_login_unknown_email_returns_error(): void
    {
        $response = $this->post('/login', [
            'email' => 'nobody@example.com',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    // ── AUTH-F-06: Logout ───────────────────────────────────────────────────

    public function test_logout_invalidates_session(): void
    {
        $user = User::factory()->user()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    // ── AUTH-F-07: Guest hitting auth routes → redirect to login ────────────

    public function test_guest_redirected_to_login_on_favorite_routes(): void
    {
        $this->post('/favorite/1')->assertRedirectToRoute('login');
        $this->delete('/favorites/remove/1')->assertRedirectToRoute('login');
        $this->get('/favorites/list')->assertRedirectToRoute('login');
    }

    public function test_guest_redirected_to_login_on_account_routes(): void
    {
        $this->get('/User-Account')->assertRedirectToRoute('login');
        $this->put('/User-Account/password')->assertRedirectToRoute('login');
    }

    public function test_guest_redirected_to_login_on_feedback_routes(): void
    {
        $this->post('/feedback')->assertRedirectToRoute('login');
    }

    public function test_guest_redirected_to_login_on_builder_save(): void
    {
        $this->post('/builder/save')->assertRedirectToRoute('login');
        $this->get('/builder/my-builds')->assertRedirectToRoute('login');
    }

    // ── AUTH-F-08: route('login') resolves to /login ─────────────────────────

    public function test_route_login_resolves_to_login_path(): void
    {
        $this->assertStringEndsWith('/login', route('login'));
    }

    // ── AUTH-F-09: Password reset ───────────────────────────────────────────

    public function test_password_reset_form_loads(): void
    {
        $response = $this->get('/password/reset');

        $response->assertStatus(200);
    }

    public function test_password_reset_email_sent_for_valid_user(): void
    {
        $user = User::factory()->user()->create();

        $response = $this->post('/password/email', [
            'email' => $user->email,
        ]);

        $response->assertSessionHas('status');
    }

    public function test_password_reset_email_fails_for_unknown_email(): void
    {
        $response = $this->post('/password/email', [
            'email' => 'nobody@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    // ── AUTH-F-10: Authenticated user visiting login/register → redirect ────

    public function test_authenticated_user_redirected_away_from_login(): void
    {
        $user = User::factory()->user()->create();
        $this->actingAs($user);

        $this->get('/login')->assertRedirect('/');
        $this->get('/register')->assertRedirect('/');
    }
}
