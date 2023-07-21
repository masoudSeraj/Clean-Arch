<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;
    public function test_throttle_middleware_works_fine()
    {
        $user = User::factory()->create();
        foreach (range(1, 12) as $i) {
            $response = $this->actingAs($user)->postJson(route('me'), []);
        }
        $response =  $this->actingAs($user)->postJson(route('me'), []);
        $response->assertStatus(429);
        // RateLimiter::clear('throttler');
        Artisan::call('cache:clear');
    }
}
