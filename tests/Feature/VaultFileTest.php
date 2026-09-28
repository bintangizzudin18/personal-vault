<?php

namespace Tests\Feature;

use App\Security\KeyDerivation;
use App\Security\VaultFile;
use Tests\TestCase;

class VaultFileTest extends TestCase
{
    public function test_vault_file_can_be_created_and_read(): void
    {
        $fakeRootSecret = 'test-secret-do-not-use-this-as-a-real-secret';

        $vaultKey = KeyDerivation::deriveVaultKey(
            $fakeRootSecret
        );

        $originalData = [
            'categories' => [
                'email' => [
                    [
                        'name' => 'Test Email',
                        'username' => 'test-user',
                        'password' => 'test-password',
                    ],
                ],
            ],
        ];

        $vaultFile = VaultFile::create(
            $originalData,
            $vaultKey
        );

        $restoredData = VaultFile::read(
            $vaultFile,
            $vaultKey
        );

        $this->assertSame(
            $originalData,
            $restoredData
        );
    }

    public function test_vault_file_has_correct_format(): void
    {
        $fakeRootSecret = 'test-secret-do-not-use-this-as-a-real-secret';

        $vaultKey = KeyDerivation::deriveVaultKey(
            $fakeRootSecret
        );

        $vaultFile = VaultFile::create(
            [
                'categories' => [],
            ],
            $vaultKey
        );

        $container = json_decode(
            $vaultFile,
            true
        );

        $this->assertSame(
            'PDV',
            $container['format']
        );

        $this->assertSame(
            1,
            $container['version']
        );

        $this->assertSame(
            'aes-256-gcm',
            $container['cipher']
        );
    }
}
