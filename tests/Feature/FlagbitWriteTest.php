<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlagbitWriteTest extends TestCase
{
    /**
     * Test: only masterkey tokens can set flagbits.
     */
    public function test_set_flagbit_requires_masterkey()
    {
        // Token with ist_masterkey = 0
        $token = '99f26159eb7a50784a9006fa35a5dbe32e604fee'; // vertrag_id = 1

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/flagbit/set', [
            'trans_id' => 1,
            'flagbit'  => 12,
        ]);

        $response->assertStatus(403)
                 ->assertJson([
                     'error' => 'Access denied: masterkey required'
                 ]);
    }

    /**
     * Test: masterkey token can set flagbit successfully.
     */
    public function test_set_flagbit_with_masterkey()
    {
        // Token with ist_masterkey = 1
        $token = '8067562d7138d72501485941246cf9b229c3a46a'; // vertrag_id = 2, masterkey = true

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/flagbit/set', [
            'trans_id' => 1,
            'flagbit'  => 12,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'code'   => 0
                 ]);
    }
}
