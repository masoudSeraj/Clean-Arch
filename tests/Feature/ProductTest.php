<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use App\Models\Product;
use App\Services\FileBuilderDirector;
use Illuminate\Support\Facades\Event;
use App\Contracts\FileContentInterface;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

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

        $response = $this->actingAs($user)->postJson(route('comment', ['product' => $products->first()->name, 'user' => $user->id]), $data);

        $response->assertOk();
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'comment' => $data['comment']
        ]);
    }

    public function test_each_user_can_only_comment_two_times_on_single_product()
    {
        $userFactory = User::factory()->state(['name' => 'masoud']);
        $user = $userFactory->create();
        // $product = Product::factory()->create();
        $product = Product::factory()->state(['name' => 'a'])->has(
            Comment::factory()->count(2)->for(
                $user
            )
        )->create();

        // dd($product->id);
        $data = ['comment' => 'comment added'];

        $reponse = $this->actingAs($user)->postJson(route('comment', ['product' => $product->name, 'user' => $user->id]), $data);

        $reponse->assertStatus(422);
    }

    public function test_file_is_created_when_product_is_added()
    {
        Event::fake();

        $product = Product::factory()->create();
        $filecontent = app(FileContentInterface::class);
        
        $filecontent->setContent($product->name);
        $filecontent->setFilename('test.txt');

        app(FileBuilderDirector::class)->createFileLogger(
            $filecontent->simpleContent()
        );

        Storage::disk('public')->assertExists('test.txt');
        Storage::disk('public')->delete('test.txt');
        Storage::assertMissing('test.txt');
    }

    public function test_product_name_will_exist_in_log_file()
    {
        Event::fake();

        $product = Product::factory()->create();  
        $filecontent = app(FileContentInterface::class);
        $filecontent->setContent($product->name);
        $filecontent->setFilename('test.txt');
        
        app(FileBuilderDirector::class)->createFileLogger($filecontent->simpleContent());

        $contents = file_get_contents(Storage::disk('public')->path('test.txt'));

        $this->assertStringContainsString($product->name, $contents);
        Storage::disk('public')->delete('test.txt');
        Storage::assertMissing('test.txt');
    }

    public function test_add_new_product_string_command_will_add_product_to_database(){
        Artisan::call('new:product', [
            '--name'    =>  'test',
        ]);

        $this->assertDatabaseHas('products', ['name' => 'test']);
    }

    public function test_product_list_is_displayed_for_user(){
        $userFactory = User::factory()->state(['name' => 'masoud']);
        $user = $userFactory->create();

        $product = Product::factory()->state(['name' => 'a'])->has(
            Comment::factory()->count(2)->for(
                $user
            )
        )->create();
       
        $response = $this->actingAs($user)->postJson(route('list'));
        $response->assertOk();
        $response->assertJson(fn(AssertableJson $json) => $json
            ->has('data')
            ->has('data.0', 3)
            ->where('data.0.product_name', $product->name)
            ->has('data.0.comments', 2)
            ->where('data.0.id', $product->id)
            ->has('data.0.comments', 2, fn(AssertableJson $json) => 
                $json->where('comment', $product->comments->first()->comment)
                    ->where('user.id', $user->id)
                ->etc()
                )
            ->etc()
        );
    }
}
