<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(5),
            'content' => $this->faker->paragraph()
        ];
    }
    // public function suspended() this function can be named as we want
    // {
    //     return $this->state(function (array $attribute){
    //         return [
    //             'title' => 'Hehehe'
    //         ];
    //     });
    // }
}
