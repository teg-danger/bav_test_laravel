<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->randomElement($this->productsList());
        return [
            "name" => $name,
            "description" => $this->faker->text,
            "price" => $this->faker->randomNumber(5),
            "image" =>$this->faker->imageUrl(640,480,null,false,Str::substr($name, 0,2)),
            "user_id" => User::first()->id
        ];
    }

    private function productsList(){
        return [
            "Eau",
            "Voiture",
            "Téléphone",
            "Ordinateur",
            "Cannette de bierre",
            "Parfum",
            "Cirage",
            "Sucre blanc en poudre",
            "Marmitte chauffante",
            "Tasse de 5L"
        ];
    }
}
