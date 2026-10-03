<?php

namespace Database\Factories;

use App\Models\Mentor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mentor>
 */
class MentorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'slug' => fake()->unique()->slug(4),
            'tagline' => fake()->sentence(6),
            'description' => fake()->paragraphs(3, true),
            'expertise' => [
                fake()->randomElement(['UTBK', 'Matematika', 'Fisika', 'Kimia', 'Biologi', 'Bahasa Inggris', 'Tahfidz', 'Bahasa Arab']),
                fake()->randomElement(['SMP', 'SMA', 'SNBT', 'SNBP']),
            ],
            'price' => fake()->randomElement([150000, 250000, 350000, 500000, 750000]),
            'whatsapp_number' => '08'.fake()->numerify('##########'),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
