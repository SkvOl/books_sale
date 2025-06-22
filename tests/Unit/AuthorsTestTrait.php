<?php


namespace Tests\Unit;


trait AuthorsTestTrait{

    private function authors_post(): void
    {
        $response = $this->auth->postJson('/api/authors', [
            'first_name'=> 'Cyril',
            'last_name'=> 'Carroll',
            'rank'=> 39,
            'avatar_url'=> 'http://labadie.com/'
        ]);
    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }

    private function authors_get(): void
    {
        $response = $this->auth->getJson('/api/authors');

        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);

        $response = $this->auth->getJson('/api/authors/1');

        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }
    
    private function authors_put(): void
    {
        $response = $this->auth->putJson('/api/authors/1', [
            'first_name'=> 'Cyril'
        ]);
    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }

    private function authors_delete(): void
    {
        $response = $this->auth->deleteJson('/api/authors/1');
    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }
}