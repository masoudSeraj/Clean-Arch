<?php

namespace Tests\Feature;

use App\Contracts\FileContentInterface;
use App\Facades\Storage;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use App\Services\FileBuilderDirector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

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
            'comment' => $data['comment'],
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

        $this->assertTrue(Storage::exists('test.txt'));
        Storage::delete('test.txt');
        $this->assertTrue(Storage::missing('test.txt'));
    }

    public function test_product_name_will_exist_in_log_file_when_a_product_is_created()
    {
        Event::fake();

        $product = Product::factory()->create();
        $filecontent = app(FileContentInterface::class);
        $filecontent->setContent($product->name);
        $filecontent->setFilename('test.txt');

        app(FileBuilderDirector::class)->createFileLogger($filecontent->simpleContent());

        $contents = file_get_contents(Storage::getFile('test.txt'));
        // dd($contents);
        $this->assertStringContainsString($product->name, $contents);
        // Storage::disk('public')->delete('test.txt');
        // Storage::assertMissing('test.txt');
    }

    public function test_products_will_append_when_new_product_is_added()
    {
        Event::fake();
        $filecontent = app(FileContentInterface::class);

        $product = Product::factory()->state(['name' => 'product1'])->create();
        $filecontent->setContent($product->name);
        $filecontent->setFilename('test.txt');

        app(FileBuilderDirector::class)->createFileLogger($filecontent->simpleContent());

        $product2 = Product::factory()->state(['name' => 'product2'])->create();
        $filecontent->setContent($product2->name);
        $filecontent->setFilename('test.txt');

        app(FileBuilderDirector::class)->createFileLogger($filecontent->simpleContent());

        $product3 = Product::factory()->state(['name' => 'product3'])->create();
        $filecontent->setContent($product3->name);
        $filecontent->setFilename('test.txt');
        app(FileBuilderDirector::class)->createFileLogger($filecontent->simpleContent());

        $this->assertStringContainsString($product->name, file_get_contents(Storage::getFile('test.txt')));
        $this->assertStringContainsString($product2->name, file_get_contents(Storage::getFile('test.txt')));
        $this->assertStringContainsString($product3->name, file_get_contents(Storage::getFile('test.txt')));

        // app(StorageInterface::class)->delete('test.txt');
        // $this->assertTrue(app(StorageInterface::class)->missing('test.txt'));
    }

    public function test_comment_count_will_increase_when_comment_is_added()
    {
        Event::fake();
        $filecontent = app(FileContentInterface::class);

        $product2 = Product::factory()->state(['name' => 'product2'])->create();
        $filecontent->setContent($product2->name);
        $filecontent->setFilename('test.txt');
        app(FileBuilderDirector::class)->createFileLogger($filecontent->simpleContent());

        // dd(file_get_contents(Storage::getFile('test.txt')));
        $filecontent = app(FileContentInterface::class);
        $user = User::factory()->state(['name' => 'masoud'])->create();
        $product1 = Product::factory()->state(['name' => 'product1']);
        $comment = Comment::factory()->count(2)
            // ->for($user)
            ->for($product1, 'commentable')->create();

        // dd($comment->first()->commentable);
        $filecontent->setContent($comment->first()->commentable->name);
        $filecontent->setContent($comment[1]->commentable->name);
        $filecontent->setFilename('test.txt');
        $filecontent->setCount($comment->first()->commentable->count());
        $filecontent->setCount($comment[1]->commentable->count());
        $filecontent->simpleContent();

        app(FileBuilderDirector::class)->updateFileLogger();
    }

    public function test_add_new_product_command_will_add_product_to_database()
    {
        Artisan::call('new:product', [
            '--name' => 'test',
        ]);

        $this->assertDatabaseHas('products', ['name' => 'test']);
    }

    public function test_product_list_is_displayed_for_user()
    {
        $userFactory = User::factory()->state(['name' => 'masoud']);
        $user = $userFactory->create();

        $product = Product::factory()->state(['name' => 'a'])->has(
            Comment::factory()->count(2)->for(
                $user
            )
        )->create();

        $response = $this->actingAs($user)->postJson(route('list'));
        $response->assertOk();
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('data')
            ->has('data.0', 3)
            ->where('data.0.product_name', $product->name)
            ->has('data.0.comments', 2)
            ->where('data.0.id', $product->id)
            ->has('data.0.comments', 2, fn (AssertableJson $json) => $json->where('comment', $product->comments->first()->comment)
                ->where('user.id', $user->id)
                ->etc()
            )
            ->etc()
        );
    }
}
