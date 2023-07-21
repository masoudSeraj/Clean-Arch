<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userFactory = User::factory()->state(['name' => 'Reza']);
        $user = $userFactory->create();

        Product::factory()->count(2)->sequence(
            ['name' => 'A'],
            ['name' => 'B'],
        )
        ->has(Comment::factory()->count(2)->for(
                $user
        ))
        ->create();

        $userFactory = User::factory()->state(['name' => 'masoud']);
        $user = $userFactory->create();
        
        Product::factory()->state(['name' => 'C'])->has(
            Comment::factory()->count(2)->for(
                $user
            )
        )->create();
    }
}
