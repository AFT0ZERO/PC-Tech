<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use DatabaseTransactions;

    // ── FB-F-01: Store valid feedback ────────────────────────────────────────

    public function test_store_valid_feedback(): void
    {
        $user = User::factory()->user()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post('/feedback', [
            'message' => 'Great product!',
            'rate' => 5,
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $response->assertRedirect("/single-page/{$product->id}");
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('feedback', [
            'message' => 'Great product!',
            'rate' => 5,
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
    }

    // ── FB-F-02: Validation ─────────────────────────────────────────────────

    public function test_feedback_validation_errors(): void
    {
        $user = User::factory()->user()->create();

        $response = $this->actingAs($user)->post('/feedback', [
            'rate' => 6,
            'product_id' => 99999,
            'user_id' => 99999,
        ]);

        $response->assertSessionHasErrors(['message', 'rate', 'product_id', 'user_id']);
    }

    // ── FB-F-03: Rate boundaries ─────────────────────────────────────────────

    public function test_rate_1_and_5_accepted(): void
    {
        $user = User::factory()->user()->create();
        $product = Product::factory()->create();

        $response1 = $this->actingAs($user)->post('/feedback', [
            'message' => 'Terrible',
            'rate' => 1,
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
        $response1->assertSessionHas('success');

        $response5 = $this->actingAs($user)->post('/feedback', [
            'message' => 'Excellent',
            'rate' => 5,
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);
        $response5->assertSessionHas('success');
    }

    // ── FB-F-04: Guest redirect ─────────────────────────────────────────────

    public function test_guest_redirected_on_feedback(): void
    {
        $this->post('/feedback')->assertRedirectToRoute('login');
    }

    // ── FB-F-05: Update feedback ─────────────────────────────────────────────

    public function test_update_feedback(): void
    {
        $user = User::factory()->user()->create();
        $product = Product::factory()->create();
        $feedback = \App\Models\Feedback::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'message' => 'Old message',
            'rate' => 3,
        ]);

        $response = $this->actingAs($user)->put("/feedback/{$feedback->id}", [
            'message' => 'Updated message',
            'rate' => 4,
            'product_id' => $product->id,
            'user_id' => $user->id,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->id,
            'message' => 'Updated message',
            'rate' => 4,
        ]);
    }

    // ── FB-F-07: Delete feedback ─────────────────────────────────────────────

    public function test_delete_feedback_removes_record(): void
    {
        $user = User::factory()->user()->create();
        $product = Product::factory()->create();
        $feedback = \App\Models\Feedback::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->actingAs($user)->delete("/feedback/{$feedback->id}");

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('feedback', ['id' => $feedback->id]);
    }

    // ── FB-F-08: Cascading delete ────────────────────────────────────────────

    public function test_deleting_product_cascades_feedback(): void
    {
        $user = User::factory()->user()->create();
        $product = Product::factory()->create();
        $feedback = \App\Models\Feedback::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // Product uses SoftDeletes; forceDelete triggers FK cascade
        $product->forceDelete();

        $this->assertDatabaseMissing('feedback', ['id' => $feedback->id]);
    }
}
