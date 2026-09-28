<?php

namespace App\Security;

class VaultService
{
    private VaultRepository $repository;

    public function __construct(
        string $rootSecret,
        ?string $vaultPath = null
    ) {
        $vaultKey = KeyDerivation::deriveVaultKey(
            $rootSecret
        );

        $vaultPath ??= storage_path(
            'vault/personal-vault.pdv'
        );

        $this->repository = new VaultRepository(
            $vaultPath,
            $vaultKey
        );
    }

    /**
     * Mengambil seluruh isi Vault.
     */
    public function getVault(): array
    {
        return $this->repository->getVault();
    }

    /**
     * Mengambil semua item dalam kategori.
     */
    public function getItems(
        string $category
    ): array {
        return $this->repository->getItems(
            $category
        );
    }

    /**
     * Mencari item berdasarkan ID.
     */
    public function findItem(
        string $category,
        string $id
    ): ?array {
        return $this->repository->findItem(
            $category,
            $id
        );
    }

    /**
     * Menambahkan item baru.
     */
    public function addItem(
        string $category,
        array $item
    ): void {
        $this->repository->addItem(
            $category,
            $item
        );
    }

    /**
     * Memperbarui item.
     */
    public function updateItem(
        string $category,
        string $id,
        array $item
    ): void {
        $this->repository->updateItem(
            $category,
            $id,
            $item
        );
    }

    /**
     * Menghapus item.
     */
    public function deleteItem(
        string $category,
        string $id
    ): void {
        $this->repository->deleteItem(
            $category,
            $id
        );
    }
}
