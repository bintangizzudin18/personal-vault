<?php

namespace App\Console\Commands;

use App\Security\VaultIntegrity;
use Illuminate\Console\Command;
use RuntimeException;

class BackupVault extends Command
{
    protected $signature = 'vault:backup';

    protected $description = 'Create an encrypted backup of the Personal Digital Vault';

    public function handle(): int
    {
        $source = storage_path(
            'vault/personal-vault.pdv'
        );

        $backupDirectory = storage_path(
            'backups'
        );

        if (!file_exists($source)) {
            $this->error(
                'File Vault tidak ditemukan.'
            );

            return self::FAILURE;
        }

        if (!is_dir($backupDirectory)) {
            mkdir(
                $backupDirectory,
                0700,
                true
            );
        }

        $timestamp = now()->format(
            'Y-m-d_H-i-s'
        );

        $backupPath = $backupDirectory .
            DIRECTORY_SEPARATOR .
            "personal-vault_{$timestamp}.pdv";

        if (
            !copy(
                $source,
                $backupPath
            )
        ) {
            throw new RuntimeException(
                'Gagal membuat backup Vault.'
            );
        }

        $hash = VaultIntegrity::hashFile(
            $backupPath
        );

        $this->newLine();

        $this->info(
            'Backup Vault berhasil dibuat.'
        );

        $this->newLine();

        $this->line(
            'Lokasi: ' . $backupPath
        );

        $this->line(
            'SHA-256: ' . $hash
        );

        $this->newLine();

        $this->warn(
            'Backup tetap terenkripsi dan tidak berisi Root Secret.'
        );

        return self::SUCCESS;
    }
}
