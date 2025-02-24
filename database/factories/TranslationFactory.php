<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $locales = ['en', 'fr', 'es'];
        return [
            'locale' => $this->faker->randomElement($locales),
            'translation_key' => $this->faker->word . '_' . $this->faker->unique()->numberBetween(1, 10000000),
            'content' => $this->faker->sentence,
        ];
    }
}
