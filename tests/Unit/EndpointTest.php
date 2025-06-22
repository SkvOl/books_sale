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
        $this->createAll();
        $this->getAll();
        $this->changeAll();
        $this->deleteAll();
    }

    private function createAll(): void
    {
        $this->authors_post();
        $this->books_post();
        $this->books_buy();
    }

    private function getAll(): void
    {
        $this->authors_get();
        $this->books_get();
        $this->buyers_get();
        $this->sales_get();
    }

    private function changeAll(): void
    {
        $this->authors_put();
        $this->books_put();
        $this->buyers_put();
        $this->sales_put();
    }

    private function deleteAll(): void
    {
        $this->authors_delete();
        $this->books_delete();
        $this->buyers_delete();
        $this->sales_delete();
    }
}
