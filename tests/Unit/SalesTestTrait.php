<?php


namespace Tests\Unit;

trait SalesTestTrait{
    private function sales_get(): void
    {
        $response = $this->auth->getJson('/api/sales');

        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);

        $response = $this->auth->getJson('/api/sales/1');

        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }
    
    private function sales_put(): void
    {
        $response = $this->auth->putJson('/api/sales/1', [
            'price'=> '0'
        ]);
    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }

    private function sales_delete(): void
    {
        $response = $this->auth->deleteJson('/api/sales/1');
    
        $response->assertStatus(200)->assertJson([
            'status' => 'Successfully',
        ]);
    }
}