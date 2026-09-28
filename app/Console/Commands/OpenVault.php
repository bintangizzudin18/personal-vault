<?php

namespace App\Console\Commands;

use App\Security\KeyDerivation;
use App\Security\VaultFile;
use Illuminate\Console\Command;

class OpenVault extends Command
{
    protected $signature = 'vault:open';

    protected $description = 'Open and decrypt the Personal Digital Vault';

    public function handle(): int
    {
        $this->newLine();

        $this->info('Opening Personal Digital Vault...');

        $this->newLine();

        $rootSecret = $this->secret(
            'Masukkan Root Secret'
        );

        if (!$rootSecret) {
            $this->error('Root Secret tidak boleh kosong.');

            return self::FAILURE;
        }

        $path = storage_path(
            'vault/personal-vault.pdv'
        );

        if (!file_exists($path)) {
            $this->error('File Vault tidak ditemukan.');

            return self::FAILURE;
        }

        try {
            $vaultKey = KeyDerivation::deriveVaultKey(
                $rootSecret
            );

            $vaultData = VaultFile::read(
                file_get_contents($path),
                $vaultKey
            );
        } catch (\Throwable $e) {
            $this->error(
                'Gagal membuka Vault.'
            );

            $this->line(
                'Root Secret salah atau file Vault rusak.'
            );

            return self::FAILURE;
        }

        $this->newLine();

        $this->info('Vault berhasil dibuka.');

        $this->newLine();

        $this->line(
            'Nama Vault: ' . ($vaultData['vault_name'] ?? 'Tidak diketahui')
        );

        $this->line(
            'Dibuat: ' . ($vaultData['created_at'] ?? 'Tidak diketahui')
        );

        $categories = $vaultData['categories'] ?? [];

        $this->line(
            'Jumlah kategori: ' . count($categories)
        );

        $this->newLine();

        $this->info('Kategori Vault:');

        foreach ($categories as $category => $items) {
            $this->line(
                '- ' . $category . ': ' . count($items) . ' item'
            );
        }

        $this->newLine();

        return self::SUCCESS;
    }
}
