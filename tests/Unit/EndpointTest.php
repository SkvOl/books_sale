<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Unit\AuthorsTestTrait;
use Tests\Unit\BuyersTestTrait;
use Tests\Unit\SalesTestTrait;
use Tests\Unit\BooksTestTrait;
use App\Models\User;
use Tests\TestCase;


class EndpointTest extends TestCase{
    use AuthorsTestTrait;
    use RefreshDatabase;
    use BuyersTestTrait;
    use SalesTestTrait;
    use BooksTestTrait;

    private $auth;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->auth = $this->actingAs($user)->withSession(['banned' => false]);
    }

    public function test_all_endpoints(): void
    {
        $this->authors_post();
        $this->books_post();
        $this->books_buy();


        $this->authors_get();
        $this->books_get();
        $this->buyers_get();
        $this->sales_get();
        

        $this->authors_put();
        $this->books_put();
        $this->buyers_put();
        $this->sales_put();


        $this->authors_delete();
        $this->books_delete();
        $this->buyers_delete();
        $this->sales_delete();
    }

    
    // public function test_authors(): void
    // {
    //     $this->authors_post();
    //     $this->authors_get();
    //     $this->authors_put();
    //     $this->authors_delete();
    // }

    
    // public function test_books(): void
    // {
    //     $this->books_post();
    //     $this->books_get();
    //     $this->books_put();
    //     $this->books_delete();

    //     $this->books_buy();
    // }

    
    
    // public function test_buyers(): void
    // {
    //     $this->buyers_get();
    //     $this->buyers_put();
    //     $this->buyers_delete();
    // }

    
    // public function test_sales(): void
    // {
    //     $this->sales_get();
    //     $this->sales_put();
    //     $this->sales_delete();
    // }
}
