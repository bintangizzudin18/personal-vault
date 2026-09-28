<?php

namespace App\Security;

use InvalidArgumentException;

class KeyDerivation
{
    /**
     * Menghasilkan Vault Key 256-bit
     * dari Root Secret.
     */
    public static function deriveVaultKey(string $rootSecret): string
    {
        if ($rootSecret === '') {
            throw new InvalidArgumentException(
                'Root Secret tidak boleh kosong.'
            );
        }

        return hash_hkdf(
            'sha256',
            $rootSecret,
            32,
            'personal-digital-vault',
            ''
        );
    }
}
