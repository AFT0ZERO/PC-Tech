<?php

namespace Tests\Feature\Admin;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ContactAdminTest extends TestCase
{
    use DatabaseTransactions;

    private User $admin;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->user = User::factory()->user()->create();
    }

    private function createContact(array $overrides = []): Contact
    {
        return Contact::factory()->create(array_merge([
            'user_id' => $this->user->id,
        ], $overrides));
    }

    public function test_index_lists_contacts(): void
    {
        $this->createContact(['name' => 'John']);
        $this->createContact(['name' => 'Jane']);

        $response = $this->actingAs($this->admin)->get('/dashboard/contacts');

        $response->assertStatus(200);
        $response->assertSee('John');
        $response->assertSee('Jane');
    }

    public function test_store_creates_contact(): void
    {
        $response = $this->actingAs($this->admin)->post('/dashboard/contacts', [
            'user_id' => $this->user->id,
            'name' => 'John',
            'email' => 'john@example.com',
            'mobile' => '0791234567',
            'message' => 'This is a test message that is long enough.',
        ]);

        $response->assertSessionHas('success', 'Your message has been sent successfully!');
        $response->assertRedirect();

        $this->assertDatabaseHas('contacts', [
            'name' => 'John',
            'email' => 'john@example.com',
        ]);
    }

    public function test_store_validation(): void
    {
        $response = $this->actingAs($this->admin)->post('/dashboard/contacts', [
            'name' => 'AB',
            'email' => 'not-email',
            'mobile' => '123',
            'message' => 'short',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
    }

    public function test_show_displays_contact(): void
    {
        $contact = $this->createContact();

        $response = $this->actingAs($this->admin)->get("/dashboard/contacts/{$contact->id}");

        $response->assertStatus(200);
    }

    public function test_destroy_soft_deletes_contact(): void
    {
        $contact = $this->createContact();

        $response = $this->actingAs($this->admin)->delete("/dashboard/contacts/{$contact->id}");

        $response->assertSessionHas('success', 'Contact Deleted Successfully!');
        $response->assertRedirect();

        $this->assertSoftDeleted($contact);
    }

    public function test_restore_brings_contact_back(): void
    {
        $contact = $this->createContact();
        $contact->delete();

        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->get("/dashboard/restore-co/{$contact->id}");

        $response->assertSessionHas('success', 'Contact Restore Successfully!');
        $response->assertRedirect();

        $this->assertNotSoftDeleted($contact);
    }

    public function test_show_restore_lists_trashed_contacts(): void
    {
        $contact = $this->createContact();
        $contact->delete();

        $superAdmin = User::factory()->superAdmin()->create();
        $response = $this->actingAs($superAdmin)->get('/dashboard/restore-co');

        $response->assertStatus(200);
    }
}
