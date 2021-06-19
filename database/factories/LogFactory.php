<?php

namespace Ahmadwaleed\Blanket\Database\Factories;

use Ahmadwaleed\Blanket\Models\Log;
use Illuminate\Database\Eloquent\Factories\Factory;
use Symfony\Component\HttpFoundation\Response;

class LogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Log::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $url = $this->faker->url;
        $host = parse_url($url, PHP_URL_HOST);

        return [
            'url' => $url,
            'host' => $host,
            'status' => $this->faker->randomElement(array_keys(Response::$statusTexts)),
            'method' => $this->faker->randomElement(['GET', 'POST', 'PUT', 'PATCH', 'DELETE']),
        ];
    }
}
