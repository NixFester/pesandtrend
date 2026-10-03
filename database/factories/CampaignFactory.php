<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = array_keys(Campaign::categories());
        $category = fake()->randomElement($categories);

        return [
            'school_id' => School::factory(),
            'title' => fake()->sentence(4),
            'slug' => fake()->unique()->slug(4),
            'description' => fake()->paragraphs(3, true),
            'category' => $category,
            'target_amount' => fake()->randomElement([10000000, 25000000, 50000000, 100000000, 200000000]),
            'current_amount' => 0,
            'start_date' => now(),
            'end_date' => now()->addMonths(2),
            'status' => 'active',
            'is_featured' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'current_amount' => $attributes['target_amount'],
        ]);
    }
}
