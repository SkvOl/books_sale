<?php


namespace Tests\Unit;

trait BooksTestTrait{
    private function books_post(): void
    {
        $response = $this->auth->postJson('/api/books', [
            'title'=> 'Illum quia.',
            'description'=> 'Natus quod amet assumenda quis consequatur temporibus.',
            'cover_url'=> 'http://ortiz.com/distinctio-animi-quaerat-et-similique-itaque.html',
            'price'=> 296.07,
            'quantity'=> 5,
            'authors'=>[1]
        ]);

    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }

    private function books_get(): void
    {
        $response = $this->auth->getJson('/api/books');

        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);

        $response = $this->auth->getJson('/api/books/1');

        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }
    
    private function books_put(): void
    {
        $response = $this->auth->putJson('/api/books/1', [
            'title'=> '--',
            'isTest'=> true
        ]);
    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }

    private function books_delete(): void
    {
        $response = $this->auth->deleteJson('/api/books/1');
    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }

    private function books_buy(): void
    {
        $response = $this->auth->postJson('/api/books/1/buy',['isTest'=> true]);
    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }
}