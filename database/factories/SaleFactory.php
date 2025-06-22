<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Book;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $book_id = fake()->numberBetween(1, 100);

        $book = Book::find($book_id);

        return [
            'book_id' => $book_id,
            'client_id' => fake()->numberBetween(1, 1000),
            'price' => $book->price ?? 100,
        ];
    }
}