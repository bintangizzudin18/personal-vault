<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
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

    public function test_unlock_page_issues_cookie_backed_session(): void
    {
        Config::set('session.driver', 'cookie');

        $response = $this->get('/vault/unlock');

        $response->assertOk();
        $response->assertCookie(config('session.cookie'));
    }
}
