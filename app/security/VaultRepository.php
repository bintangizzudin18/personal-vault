<?php

namespace App\Security;

use RuntimeException;

class VaultRepository
{
    private string $path;

    private string $vaultKey;

    public function __construct(
        string $path,
        string $vaultKey
    ) {
        $this->path = $path;
        $this->vaultKey = $vaultKey;
    }

    /**
     * Membaca seluruh isi Vault.
     */
    public function getVault(): array
    {
        $contents = app(VaultStorage::class)->read($this->path);

        if ($contents === null) {
            throw new RuntimeException(
                'File Vault tidak ditemukan.'
            );
        }

        return VaultFile::read(
            $contents,
            $this->vaultKey
        );
    }

    /**
     * Menyimpan seluruh isi Vault.
     */
    private function saveVault(array $vaultData): void
    {
        $vaultFile = VaultFile::create(
            $vaultData,
            $this->vaultKey
        );

        app(VaultStorage::class)->write($this->path, $vaultFile);
    }

    /**
     * Menambahkan item ke kategori.
     */
    public function addItem(
        string $category,
        array $item
    ): void {
        $vaultData = $this->getVault();

        if (!isset($vaultData['categories'][$category])) {
            throw new RuntimeException(
                'Kategori Vault tidak ditemukan.'
            );
        }

        $vaultData['categories'][$category][] = $item;

        $this->saveVault($vaultData);
    }

    /**
     * Mengambil seluruh item dari kategori.
     */
    public function getItems(
        string $category
    ): array {
        $vaultData = $this->getVault();

        if (!isset($vaultData['categories'][$category])) {
            throw new RuntimeException(
                'Kategori Vault tidak ditemukan.'
            );
        }

        return $vaultData['categories'][$category];
    }

    /**
     * Mencari item berdasarkan ID.
     */
    public function findItem(
        string $category,
        string $id
    ): ?array {
        $items = $this->getItems($category);

        foreach ($items as $item) {
            if (($item['id'] ?? null) === $id) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Menghapus item berdasarkan ID.
     */
    public function deleteItem(
        string $category,
        string $id
    ): void {
        $vaultData = $this->getVault();

        if (!isset($vaultData['categories'][$category])) {
            throw new RuntimeException(
                'Kategori Vault tidak ditemukan.'
            );
        }

        $items = $vaultData['categories'][$category];

        $found = false;

        $items = array_values(
            array_filter(
                $items,
                function ($item) use ($id, &$found) {
                    if (($item['id'] ?? null) === $id) {
                        $found = true;

                        return false;
                    }

                    return true;
                }
            )
        );

        if (!$found) {
            throw new RuntimeException(
                'Item tidak ditemukan.'
            );
        }

        $vaultData['categories'][$category] = $items;

        $this->saveVault($vaultData);
    }

    /**
 * Memperbarui item berdasarkan ID.
 */
public function updateItem(
    string $category,
    string $id,
    array $updatedItem
): void {
    $vaultData = $this->getVault();

    if (!isset($vaultData['categories'][$category])) {
        throw new RuntimeException(
            'Kategori Vault tidak ditemukan.'
        );
    }

    $items = $vaultData['categories'][$category];

    $found = false;

    foreach ($items as $index => $item) {
        if (($item['id'] ?? null) === $id) {
            $updatedItem['id'] = $id;

            $updatedItem['created_at'] =
                $item['created_at'] ?? now()->toIso8601String();

            $updatedItem['updated_at'] =
                now()->toIso8601String();

            $items[$index] = $updatedItem;

            $found = true;

            break;
        }
    }

    if (!$found) {
        throw new RuntimeException(
            'Item tidak ditemukan.'
        );
    }

    $vaultData['categories'][$category] = $items;

    $this->saveVault($vaultData);
}
}
