<?php

namespace Tests\Feature;

use App\Security\KeyDerivation;
use Tests\TestCase;

class KeyDerivationTest extends TestCase
{
    public function test_root_secret_can_generate_a_256_bit_vault_key(): void
    {
        $fakeRootSecret = 'test-secret-do-not-use-this-as-a-real-secret';

        $vaultKey = KeyDerivation::deriveVaultKey($fakeRootSecret);

        $this->assertSame(32, strlen($vaultKey));
    }

    public function test_same_root_secret_generates_same_vault_key(): void
    {
        $fakeRootSecret = 'test-secret-do-not-use-this-as-a-real-secret';

        $key1 = KeyDerivation::deriveVaultKey($fakeRootSecret);
        $key2 = KeyDerivation::deriveVaultKey($fakeRootSecret);

        $this->assertSame($key1, $key2);
    }
}
