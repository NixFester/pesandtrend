<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<School>
 */
class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition(): array
    {
        $name = 'Pesantren '.$this->faker->city();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.uniqid(),
            'type' => 'Pesantren Modern',
            'jenjang' => ['SMAIT', 'SMPIT'],
            'city' => $this->faker->city(),
            'province' => 'Jawa Barat',
            'address' => $this->faker->address(),
            'short_desc' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'rating' => 4.5,
            'reviews_count' => 10,
            'students_count' => 300,
            'teacher_ratio' => '1:10',
            'founded_year' => 2005,
            'is_boarding' => true,
            'registration_open' => true,
            'is_verified' => true,
            'is_featured' => false,
            'is_published' => true,
            'accreditation' => 'A',
            'image' => 'images/schools/default.jpg',
            'tags' => ['Pesantren', 'Beasiswa'],
            'uang_pangkal' => 5000000,
            'spp_monthly' => 500000,
            'asrama_monthly' => 300000,
            'seragam_fee' => 750000,
            'ekskul_fee' => 200000,
            'study_tour_fee' => 500000,
            'latitude' => -6.5971,
            'longitude' => 106.7990,
            'whatsapp_e164' => '+6281234567890',
        ];
    }
}
