<?php

namespace Database\Factories;

use App\Models\SeoCity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SeoCity>
 */
class SeoCityFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->city();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(0, 100),
            'meta_title' => "Sekolah Islam & Pesantren Terbaik di {$name} | Pesantrends",
            'meta_description' => "Temukan sekolah Islam dan pesantren terbaik di {$name}. Pilihan sekolah berkualitas dengan fasilitas lengkap dan biaya terjangkau.",
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
