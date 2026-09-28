<?php

namespace App\Security;

use RuntimeException;

class VaultFile
{
    private const FORMAT = 'PDV';
    private const VERSION = 1;

    /**
     * Membuat isi file .pdv dari data Vault.
     */
    public static function create(
        array $vaultData,
        string $vaultKey
    ): string {
        $plaintext = json_encode(
            $vaultData,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_THROW_ON_ERROR
        );

        $encrypted = VaultEncryption::encrypt(
            $plaintext,
            $vaultKey
        );

        $container = [
            'format' => self::FORMAT,
            'version' => self::VERSION,
            'cipher' => $encrypted['cipher'],
            'nonce' => $encrypted['nonce'],
            'ciphertext' => $encrypted['ciphertext'],
            'tag' => $encrypted['tag'],
        ];

        return json_encode(
            $container,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_THROW_ON_ERROR
        );
    }

    /**
     * Membuka isi file .pdv.
     */
    public static function read(
        string $vaultFile,
        string $vaultKey
    ): array {
        $container = json_decode(
            $vaultFile,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (
            !isset($container['format']) ||
            $container['format'] !== self::FORMAT
        ) {
            throw new RuntimeException(
                'Format Vault tidak valid.'
            );
        }

        if (
            !isset($container['version']) ||
            $container['version'] !== self::VERSION
        ) {
            throw new RuntimeException(
                'Versi Vault tidak didukung.'
            );
        }

        $plaintext = VaultEncryption::decrypt(
            base64_decode($container['ciphertext'], true),
            $vaultKey,
            base64_decode($container['nonce'], true),
            base64_decode($container['tag'], true)
        );

        return json_decode(
            $plaintext,
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }
}
