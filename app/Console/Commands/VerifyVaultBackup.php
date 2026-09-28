<?php

namespace App\Console\Commands;

use App\Security\KeyDerivation;
use App\Security\VaultFile;
use App\Security\VaultIntegrity;
use Illuminate\Console\Command;

class VerifyVaultBackup extends Command
{
    protected $signature = 'vault:verify-backup';

    protected $description = 'Verify and decrypt the latest Vault backup';

    public function handle(): int
    {
        $backupDirectory = storage_path('backups');

        if (!is_dir($backupDirectory)) {
            $this->error('Folder backup tidak ditemukan.');

            return self::FAILURE;
        }

        $files = glob(
            $backupDirectory . DIRECTORY_SEPARATOR . '*.pdv'
        );

        if (empty($files)) {
            $this->error('Tidak ada file backup Vault.');

            return self::FAILURE;
        }

        // Urutkan berdasarkan waktu modifikasi terbaru.
        usort(
            $files,
            fn ($a, $b) => filemtime($b) <=> filemtime($a)
        );

        $latestBackup = $files[0];

        $this->newLine();

        $this->info('Backup terbaru ditemukan:');
        $this->line(basename($latestBackup));

        $this->newLine();

        // Verifikasi fingerprint file.
        $hash = VaultIntegrity::hashFile($latestBackup);

        $this->line('SHA-256: ' . $hash);

        $this->newLine();

        $rootSecret = $this->secret(
            'Masukkan Root Secret'
        );

        if (!$rootSecret) {
            $this->error('Root Secret tidak boleh kosong.');

            return self::FAILURE;
        }

        try {
            $vaultKey = KeyDerivation::deriveVaultKey(
                $rootSecret
            );

            $vaultData = VaultFile::read(
                file_get_contents($latestBackup),
                $vaultKey
            );
        } catch (\Throwable $e) {
            $this->error('Backup gagal dibuka.');

            $this->line(
                'Root Secret salah atau backup rusak.'
            );

            return self::FAILURE;
        }

        $this->newLine();

        $this->info('✓ Backup berhasil diverifikasi dan dibuka.');

        $this->line(
            'Nama Vault: ' .
            ($vaultData['vault_name'] ?? 'Tidak diketahui')
        );

        $this->line(
            'Dibuat: ' .
            ($vaultData['created_at'] ?? 'Tidak diketahui')
        );

        $categories = $vaultData['categories'] ?? [];

        $this->line(
            'Jumlah kategori: ' . count($categories)
        );

        $this->newLine();

        $this->info('Kategori:');

        foreach ($categories as $category => $items) {
            $this->line(
                '- ' . $category . ': ' . count($items) . ' item'
            );
        }

        $this->newLine();

        return self::SUCCESS;
    }
}
