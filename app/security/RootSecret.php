<?php

namespace App\Security;

use RuntimeException;

class RootSecret
{
    /**
     * Membuat Root Secret 256-bit.
     */
    public static function generate(): string
    {
        try {
            $randomBytes = random_bytes(32);
        } catch (\Throwable $e) {
            throw new RuntimeException(
                'Gagal membuat Root Secret yang aman.',
                0,
                $e
            );
        }

        return bin2hex($randomBytes);
    }
}
