<?php

namespace Tests\Feature;

use App\Security\KeyDerivation;
use App\Security\VaultEncryption;
use Tests\TestCase;

class VaultEncryptionTest extends TestCase
{
    public function test_data_can_be_encrypted_and_decrypted(): void
    {
        $fakeRootSecret = 'test-secret-do-not-use-this-as-a-real-secret';

        $key = KeyDerivation::deriveVaultKey($fakeRootSecret);

        $originalData = 'Data rahasia Personal Digital Vault';

        $encrypted = VaultEncryption::encrypt(
            $originalData,
            $key
        );

        $decrypted = VaultEncryption::decrypt(
            base64_decode($encrypted['ciphertext']),
            $key,
            base64_decode($encrypted['nonce']),
            base64_decode($encrypted['tag'])
        );

        $this->assertSame(
            $originalData,
            $decrypted
        );
    }

    public function test_tampered_data_cannot_be_decrypted(): void
    {
        $fakeRootSecret = 'test-secret-do-not-use-this-as-a-real-secret';

        $key = KeyDerivation::deriveVaultKey($fakeRootSecret);

        $encrypted = VaultEncryption::encrypt(
            'Data rahasia',
            $key
        );

        $ciphertext = base64_decode(
            $encrypted['ciphertext']
        );

        // Mengubah ciphertext.
        $ciphertext[0] = chr(
            ord($ciphertext[0]) ^ 1
        );

        $this->expectException(\RuntimeException::class);

        VaultEncryption::decrypt(
            $ciphertext,
            $key,
            base64_decode($encrypted['nonce']),
            base64_decode($encrypted['tag'])
        );
    }
}
