<?php

namespace Database\Seeders;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Buyer;
use App\Models\Book;
use App\Models\User;
use App\Models\Sale;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(100)
        ->has(Author::factory()->count(4))
        ->create();
        // Author::factory(1000)->create();
        Buyer::factory(1000)
        ->has(User::factory()->count(1), 'user')
        ->create();
        Sale::factory(1000)->create();
    }
}
