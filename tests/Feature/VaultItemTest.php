<?php

namespace Tests\Feature;

use App\Security\VaultItem;
use Tests\TestCase;

class VaultItemTest extends TestCase
{
    public function test_vault_item_can_be_created(): void
    {
        $item = VaultItem::create(
            'email',
            'Email Utama',
            [
                'username' => 'test-user',
                'email' => 'test@example.com',
                'password' => 'test-password',
            ],
            'Catatan pengujian'
        );

        $this->assertNotEmpty($item['id']);

        $this->assertSame(
            'email',
            $item['type']
        );

        $this->assertSame(
            'Email Utama',
            $item['title']
        );

        $this->assertSame(
            'test-password',
            $item['fields']['password']
        );

        $this->assertSame(
            'Catatan pengujian',
            $item['notes']
        );

        $this->assertNotEmpty(
            $item['created_at']
        );

        $this->assertNotEmpty(
            $item['updated_at']
        );
    }

    public function test_item_requires_title(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );

        VaultItem::create(
            'email',
            ''
        );
    }
}
