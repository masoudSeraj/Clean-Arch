<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $commentable = $this->commentable();

        return [
            'commentable_id' => $commentable->factory(),
            'commentable_type' => $commentable,
        ];
    }

    public function commentable()
    {
        return $this->faker->randomElement([
            Product::class,
        ]);
    }
}
