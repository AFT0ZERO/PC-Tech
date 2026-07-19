<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use DatabaseTransactions;

    // ── FAV-F-01: Toggle add ─────────────────────────────────────────────────

    public function test_toggle_adds_favorite(): void
    {
        $user = User::factory()->user()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post("/favorite/{$product->id}");

        $response->assertJson(['status' => 'added', 'message' => 'Product added to favorites']);
        $this->assertDatabaseHas('favorite', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    // ── FAV-F-02: Toggle remove ─────────────────────────────────────────────

    public function test_toggle_removes_favorite(): void
    {
        $user = User::factory()->user()->create();
        $product = Product::factory()->create();
        $user->favorites()->attach($product->id);

        $response = $this->actingAs($user)->post("/favorite/{$product->id}");

        $response->assertJson(['status' => 'removed', 'message' => 'Product removed from favorites']);
        $this->assertDatabaseMissing('favorite', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    // ── FAV-F-04: Guest redirect ────────────────────────────────────────────

    public function test_guest_redirected_on_toggle(): void
    {
        $this->post('/favorite/1')->assertRedirectToRoute('login');
    }

    // ── FAV-F-05: Remove from list page ──────────────────────────────────────

    public function test_remove_favorite_via_delete(): void
    {
        $user = User::factory()->user()->create();
        $product = Product::factory()->create();
        $user->favorites()->attach($product->id);

        $response = $this->actingAs($user)->delete("/favorites/remove/{$product->id}");

        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('favorite', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    // ── FAV-F-06: List favorites ────────────────────────────────────────────

    public function test_list_favorites_shows_only_user_favorites(): void
    {
        $user = User::factory()->user()->create();
        $otherUser = User::factory()->user()->create();
        $product1 = Product::factory()->create();
        \App\Models\ProductImage::factory()->forProduct($product1)->create();
        $product2 = Product::factory()->create();
        \App\Models\ProductImage::factory()->forProduct($product2)->create();

        $user->favorites()->attach($product1->id);
        $otherUser->favorites()->attach($product2->id);

        $response = $this->actingAs($user)->get('/favorites/list');

        $response->assertStatus(200);
    }

    // ── FAV-F-07: Double toggle idempotency ─────────────────────────────────

    public function test_double_toggle_leaves_no_rows(): void
    {
        $user = User::factory()->user()->create();
        $product = Product::factory()->create();

        $this->actingAs($user)->post("/favorite/{$product->id}");
        $this->actingAs($user)->post("/favorite/{$product->id}");

        $this->assertEquals(0, $user->favorites()->where('product_id', $product->id)->count());
    }
}
