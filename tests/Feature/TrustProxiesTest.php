<?php

namespace Tests\Feature;

use Tests\TestCase;

class TrustProxiesTest extends TestCase
{
    public function test_unlock_form_uses_https_when_forwarded_as_https(): void
    {
        $response = $this->withServerVariables([
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_X_FORWARDED_PROTO' => 'https',
        ])->get('/vault/unlock');

        $response->assertOk();
        $response->assertSee(
            'action="https://localhost/vault/unlock"',
            false
        );
    }
}
