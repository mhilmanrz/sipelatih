<?php

namespace Database\Factories\Act;

use App\Models\Act\EvaluationCriteria;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluationCriteria>
 */
class EvaluationCriteriaFactory extends Factory
{
    protected $model = EvaluationCriteria::class;

    public function definition(): array
    {
        return [
            'code' => 'E1-'.fake()->unique()->numerify('##'),
            'name' => fake()->sentence(3),
            'is_fillable' => fake()->boolean(),
            'type' => fake()->randomElement(['string', 'number']),
            'evaluation_type' => fake()->randomElement([1, 2, 3]),
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
