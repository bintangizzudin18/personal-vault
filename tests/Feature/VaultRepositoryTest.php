<?php

namespace Tests\Feature;

use App\Security\KeyDerivation;
use App\Security\VaultFile;
use App\Security\VaultItem;
use App\Security\VaultRepository;
use Tests\TestCase;

class VaultRepositoryTest extends TestCase
{
    private string $vaultPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vaultPath = storage_path(
            'vault/test-vault.pdv'
        );

        $rootSecret = 'test-secret-do-not-use-this-as-a-real-secret';

        $vaultKey = KeyDerivation::deriveVaultKey(
            $rootSecret
        );

        $vaultData = [
            'vault_name' => 'Test Vault',

            'created_at' => now()->toIso8601String(),

            'categories' => [
                'email' => [],
                'm_banking' => [],
            ],
        ];

        file_put_contents(
            $this->vaultPath,
            VaultFile::create(
                $vaultData,
                $vaultKey
            ),
            LOCK_EX
        );
    }

    protected function tearDown(): void
    {
        if (file_exists($this->vaultPath)) {
            unlink($this->vaultPath);
        }

        parent::tearDown();
    }

    public function test_item_can_be_added_and_read(): void
    {
        $rootSecret =
            'test-secret-do-not-use-this-as-a-real-secret';

        $vaultKey = KeyDerivation::deriveVaultKey(
            $rootSecret
        );

        $repository = new VaultRepository(
            $this->vaultPath,
            $vaultKey
        );

        $item = VaultItem::create(
            'email',
            'Email Test',
            [
                'username' => 'test-user',
                'email' => 'test@example.com',
                'password' => 'test-password',
            ]
        );

        $repository->addItem(
            'email',
            $item
        );

        $items = $repository->getItems(
            'email'
        );

        $this->assertCount(
            1,
            $items
        );

        $this->assertSame(
            'Email Test',
            $items[0]['title']
        );
    }

    public function test_item_can_be_found_by_id(): void
    {
        $rootSecret =
            'test-secret-do-not-use-this-as-a-real-secret';

        $vaultKey = KeyDerivation::deriveVaultKey(
            $rootSecret
        );

        $repository = new VaultRepository(
            $this->vaultPath,
            $vaultKey
        );

        $item = VaultItem::create(
            'email',
            'Email Test',
            [
                'email' => 'test@example.com',
                'password' => 'test-password',
            ]
        );

        $repository->addItem(
            'email',
            $item
        );

        $found = $repository->findItem(
            'email',
            $item['id']
        );

        $this->assertNotNull($found);

        $this->assertSame(
            $item['id'],
            $found['id']
        );
    }

    public function test_item_can_be_deleted(): void
    {
        $rootSecret =
            'test-secret-do-not-use-this-as-a-real-secret';

        $vaultKey = KeyDerivation::deriveVaultKey(
            $rootSecret
        );

        $repository = new VaultRepository(
            $this->vaultPath,
            $vaultKey
        );

        $item = VaultItem::create(
            'email',
            'Email Test',
            [
                'email' => 'test@example.com',
            ]
        );

        $repository->addItem(
            'email',
            $item
        );

        $repository->deleteItem(
            'email',
            $item['id']
        );

        $items = $repository->getItems(
            'email'
        );

        $this->assertCount(
            0,
            $items
        );
    }
    public function test_item_can_be_updated(): void
{
    $rootSecret =
        'test-secret-do-not-use-this-as-a-real-secret';

    $vaultKey = KeyDerivation::deriveVaultKey(
        $rootSecret
    );

    $repository = new VaultRepository(
        $this->vaultPath,
        $vaultKey
    );

    $item = VaultItem::create(
        'email',
        'Email Lama',
        [
            'email' => 'old@example.com',
            'password' => 'old-password',
        ]
    );

    $repository->addItem(
        'email',
        $item
    );

    $updatedItem = [
        'id' => $item['id'],
        'type' => 'email',
        'title' => 'Email Baru',
        'fields' => [
            'email' => 'new@example.com',
            'password' => 'new-password',
        ],
        'notes' => 'Password sudah diperbarui.',
    ];

    $repository->updateItem(
        'email',
        $item['id'],
        $updatedItem
    );

    $result = $repository->findItem(
        'email',
        $item['id']
    );

    $this->assertNotNull($result);

    $this->assertSame(
        'Email Baru',
        $result['title']
    );

    $this->assertSame(
        'new@example.com',
        $result['fields']['email']
    );

    $this->assertSame(
        'new-password',
        $result['fields']['password']
    );

    $this->assertNotEmpty(
        $result['updated_at']
    );
}
}
