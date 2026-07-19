<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAdminTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_index_lists_users(): void
    {
        User::factory()->create(['fname' => 'Alice']);
        User::factory()->create(['fname' => 'Bob']);

        $response = $this->actingAs($this->admin)->get('/dashboard/users');

        $response->assertStatus(200);
        $response->assertSee('Alice');
        $response->assertSee('Bob');
    }

    public function test_store_creates_user_with_role_admin(): void
    {
        $response = $this->actingAs($this->admin)->post('/dashboard/users', [
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john@example.com',
            'mobile' => '0791234567',
            'role' => 'admin',
            'gender' => 'male',
            'password' => 'secret',
        ]);

        $response->assertSessionHas('success', 'User Created Successfully!');

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'fname' => 'John',
            'role' => 'admin',
        ]);
    }

    public function test_store_with_image_uploads_image(): void
    {
        $file = UploadedFile::fake()->image('user.jpg');

        $response = $this->actingAs($this->admin)->post('/dashboard/users', [
            'fname' => 'John',
            'lname' => 'Doe',
            'email' => 'john@example.com',
            'mobile' => '0791234567',
            'role' => 'admin',
            'gender' => 'male',
            'password' => 'secret',
            'image' => $file,
        ]);

        $response->assertSessionHas('success', 'User Created Successfully!');

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user->image);
        $this->assertStringStartsWith('uploads/user/', $user->image);
    }

    public function test_store_validation(): void
    {
        $response = $this->actingAs($this->admin)->post('/dashboard/users', [
            'fname' => 'AB',
            'lname' => 'CD',
            'email' => 'not-email',
            'mobile' => '123',
            'gender' => 'male',
            'password' => '1234',
        ]);

        $response->assertSessionHasErrors(['fname', 'lname', 'email', 'password', 'role']);
    }

    public function test_update_user_fields(): void
    {
        $user = User::factory()->create(['fname' => 'Old']);

        $response = $this->actingAs($this->admin)->put("/dashboard/users/{$user->id}", [
            'fname' => 'New',
            'lname' => $user->lname,
            'email' => $user->email,
            'mobile' => '0791234567',
            'role' => 'user',
            'gender' => 'male',
        ]);

        $response->assertSessionHas('success', 'User updated successfully!');
        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'fname' => 'New',
        ]);
    }

    public function test_update_with_image_replaces_image(): void
    {
        $user = User::factory()->create(['image' => 'uploads/user/old.jpg']);
        $file = UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($this->admin)->put("/dashboard/users/{$user->id}", [
            'fname' => $user->fname,
            'lname' => $user->lname,
            'email' => $user->email,
            'mobile' => '0791234567',
            'role' => 'user',
            'gender' => 'male',
            'image' => $file,
        ]);

        $response->assertSessionHas('success', 'User updated successfully!');

        $user->refresh();
        $this->assertStringStartsWith('uploads/user/', $user->image);
    }

    public function test_destroy_soft_deletes_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/dashboard/users/{$user->id}");

        $response->assertSessionHas('success', 'User Deleted Successfully!');

        $this->assertSoftDeleted($user);
    }

    public function test_restore_brings_user_back(): void
    {
        $user = User::factory()->create();
        $user->delete();

        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->get("/dashboard/restore-u/{$user->id}");

        $response->assertSessionHas('success', 'User Restore Successfully!');
        $response->assertRedirect();

        $this->assertNotSoftDeleted($user);
    }

    public function test_show_restore_lists_trashed_users(): void
    {
        $user = User::factory()->create();
        $user->delete();

        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->get('/dashboard/restore-u');

        $response->assertStatus(200);
    }

    public function test_admin_profile_page_loads(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard/admin');

        $response->assertStatus(200);
    }

    public function test_update_admin_password_success(): void
    {
        $admin = User::factory()->admin()->create([
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->actingAs($admin)->put('/dashboard/admin/password', [
            'current_password' => 'old-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHas('password_success', 'Password changed successfully!');
        $response->assertRedirect();

        $admin->refresh();
        $this->assertTrue(Hash::check('new-password', $admin->password));
    }

    public function test_update_admin_password_wrong_current(): void
    {
        $admin = User::factory()->admin()->create([
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->actingAs($admin)->put('/dashboard/admin/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHasErrors(['current_password']);
    }
}
