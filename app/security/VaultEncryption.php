<?php

namespace App\Security;

use RuntimeException;

class VaultEncryption
{
    private const CIPHER = 'aes-256-gcm';
    private const KEY_LENGTH = 32;
    private const NONCE_LENGTH = 12;
    private const TAG_LENGTH = 16;

    public static function encrypt(
        string $plaintext,
        string $key
    ): array {
        if (strlen($key) !== self::KEY_LENGTH) {
            throw new RuntimeException(
                'Vault Key harus berukuran 32 byte.'
            );
        }

        $nonce = random_bytes(self::NONCE_LENGTH);

        $tag = '';

        $ciphertext = openssl_encrypt(
            $plaintext,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag,
            '',
            self::TAG_LENGTH
        );

        if ($ciphertext === false) {
            throw new RuntimeException(
                'Enkripsi Vault gagal.'
            );
        }

        return [
            'cipher' => self::CIPHER,
            'nonce' => base64_encode($nonce),
            'ciphertext' => base64_encode($ciphertext),
            'tag' => base64_encode($tag),
        ];
    }

    public static function decrypt(
        string $ciphertext,
        string $key,
        string $nonce,
        string $tag
    ): string {
        if (strlen($key) !== self::KEY_LENGTH) {
            throw new RuntimeException(
                'Vault Key harus berukuran 32 byte.'
            );
        }

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::CIPHER,
            $key,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag,
            ''
        );

        if ($plaintext === false) {
            throw new RuntimeException(
                'Dekripsi gagal. Data mungkin rusak atau telah diubah.'
            );
        }

        return $plaintext;
    }
}
