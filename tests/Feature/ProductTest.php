<?php

namespace Tests\Feature;

use App\Contracts\FileBuilderInterface;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_comment_added_on_product(): void
    {
        $user = User::factory()->create();
        $products = Product::factory()->count(3)->create();
        
        $data = ['comment' => 'comment added'];

        $response = $this->actingAs($user)->post(route('comment', ['product' => $products->first()->name, 'user' => $user->id]), $data);
        $response->assertOk();
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'comment' => $data['comment']
        ]);
    }

    public function test_file_is_created_when_product_is_added()
    {
        Storage::fake('public');

        $product = Product::factory()->create();
        // $file = app(FileBuilderInterface::class)->create();
        Storage::disk('public')->assertExists(config('parspack.filename'));
    }
}
