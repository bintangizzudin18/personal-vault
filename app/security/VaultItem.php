<?php

namespace App\Security;

use Illuminate\Support\Str;
use InvalidArgumentException;

class VaultItem
{
    /**
     * Membuat item baru di dalam Vault.
     */
    public static function create(
        string $type,
        string $title,
        array $fields = [],
        string $notes = ''
    ): array {
        if (trim($type) === '') {
            throw new InvalidArgumentException(
                'Tipe item tidak boleh kosong.'
            );
        }

        if (trim($title) === '') {
            throw new InvalidArgumentException(
                'Judul item tidak boleh kosong.'
            );
        }

        $now = now()->toIso8601String();

        return [
            'id' => (string) Str::uuid(),

            'type' => $type,

            'title' => $title,

            'fields' => $fields,

            'notes' => $notes,

            'created_at' => $now,

            'updated_at' => $now,
        ];
    }
}
