<?php

namespace App\Security;

use RuntimeException;

class VaultIntegrity
{
    /**
     * Membuat fingerprint SHA-256 dari file Vault.
     */
    public static function hashFile(string $path): string
    {
        if (!file_exists($path)) {
            throw new RuntimeException(
                'File Vault tidak ditemukan.'
            );
        }

        $hash = hash_file('sha256', $path);

        if ($hash === false) {
            throw new RuntimeException(
                'Gagal membuat fingerprint Vault.'
            );
        }

        return $hash;
    }

    /**
     * Memeriksa apakah file memiliki fingerprint yang sama.
     */
    public static function verifyFile(
        string $path,
        string $expectedHash
    ): bool {
        return hash_equals(
            $expectedHash,
            self::hashFile($path)
        );
    }
}
