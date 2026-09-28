<?php

namespace Tests\Feature;

use App\Security\KeyDerivation;
use App\Security\VaultFile;
use App\Security\VaultItem;
use App\Security\VaultService;
use Tests\TestCase;

class VaultServiceTest extends TestCase
{
    private string $vaultPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vaultPath = storage_path(
            'vault/test-service-vault.pdv'
        );

        $rootSecret =
            'test-secret-do-not-use-this-as-a-real-secret';

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

    public function test_service_can_read_vault(): void
    {
        $rootSecret =
            'test-secret-do-not-use-this-as-a-real-secret';

        $service = new VaultService(
            $rootSecret,
            $this->vaultPath
        );

        $vault = $service->getVault();

        $this->assertSame(
            'Test Vault',
            $vault['vault_name']
        );
    }

    public function test_service_can_add_and_read_item(): void
    {
        $rootSecret =
            'test-secret-do-not-use-this-as-a-real-secret';

        $service = new VaultService(
            $rootSecret,
            $this->vaultPath
        );

        $item = VaultItem::create(
            'email',
            'Email Test',
            [
                'email' => 'test@example.com',
                'password' => 'test-password',
            ]
        );

        $service->addItem(
            'email',
            $item
        );

        $items = $service->getItems(
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

    public function test_service_can_update_item(): void
    {
        $rootSecret =
            'test-secret-do-not-use-this-as-a-real-secret';

        $service = new VaultService(
            $rootSecret,
            $this->vaultPath
        );

        $item = VaultItem::create(
            'email',
            'Email Lama',
            [
                'email' => 'old@example.com',
                'password' => 'old-password',
            ]
        );

        $service->addItem(
            'email',
            $item
        );

        $updatedItem = [
            'type' => 'email',
            'title' => 'Email Baru',
            'fields' => [
                'email' => 'new@example.com',
                'password' => 'new-password',
            ],
            'notes' => 'Diperbarui melalui service.',
        ];

        $service->updateItem(
            'email',
            $item['id'],
            $updatedItem
        );

        $result = $service->findItem(
            'email',
            $item['id']
        );

        $this->assertNotNull($result);

        $this->assertSame(
            'Email Baru',
            $result['title']
        );

        $this->assertSame(
            'new-password',
            $result['fields']['password']
        );
    }

    public function test_service_can_delete_item(): void
    {
        $rootSecret =
            'test-secret-do-not-use-this-as-a-real-secret';

        $service = new VaultService(
            $rootSecret,
            $this->vaultPath
        );

        $item = VaultItem::create(
            'email',
            'Email Test',
            [
                'email' => 'test@example.com',
            ]
        );

        $service->addItem(
            'email',
            $item
        );

        $service->deleteItem(
            'email',
            $item['id']
        );

        $result = $service->findItem(
            'email',
            $item['id']
        );

        $this->assertNull($result);
    }
}
