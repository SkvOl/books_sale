<?php


namespace Tests\Unit;


trait BuyersTestTrait{

    
    private function buyers_get(): void
    {
        $response = $this->auth->getJson('/api/buyers');

        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);

        $response = $this->auth->getJson('/api/buyers/1');

        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }
    
    private function buyers_put(): void
    {
        $response = $this->auth->putJson('/api/buyers/1', [
            'name'=> '---'
        ]);
    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }

    private function buyers_delete(): void
    {
        $response = $this->auth->deleteJson('/api/buyers/1');
    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }
}