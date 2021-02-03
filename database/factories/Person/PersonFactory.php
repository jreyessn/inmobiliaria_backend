<?php

namespace Database\Factories\Person;

use App\Models\Person\Person;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{

    protected $model = Person::class;

    // ...
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->e164PhoneNumber,
            'occupation' => $this->faker->catchPhrase,
            'street' => $this->faker->streetName,
            'city' => $this->faker->city,
            'country' => $this->faker->country,
            'postcode' => $this->faker->postcode,
            'image' => $this->faker->imageUrl(640, 480, 'cats'),
            'created_at' => $this->faker->dateTimeBetween('-100 days', now())
        ];
    }
}