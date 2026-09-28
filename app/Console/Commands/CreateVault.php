<?php

namespace App\Console\Commands;

use App\Security\KeyDerivation;
use App\Security\VaultFile;
use Illuminate\Console\Command;
use RuntimeException;

class CreateVault extends Command
{
    protected $signature = 'vault:create';

    protected $description = 'Create a new encrypted Personal Digital Vault';

    public function handle(): int
    {
        $this->newLine();

        $this->info('Creating Personal Digital Vault...');

        $this->newLine();

        $rootSecret = $this->secret(
            'Masukkan Root Secret'
        );

        if (!$rootSecret) {
            $this->error('Root Secret tidak boleh kosong.');

            return self::FAILURE;
        }

        $vaultKey = KeyDerivation::deriveVaultKey(
            $rootSecret
        );

        $vaultData = [
            'vault_name' => 'Personal Digital Vault',
            'created_at' => now()->toIso8601String(),
            'categories' => [
                'recovery_security' => [],
                'm_banking' => [],
                'e_wallet' => [],
                'email' => [],
                'social_media' => [],
                'game' => [],
                'education' => [],
                'work_freelance' => [],
                'shopping' => [],
                'devices_services' => [],
                'investment_finance' => [],
                'identity_personal' => [],
                'documents' => [],
                'notes' => [],
            ],
        ];

        $vaultFile = VaultFile::create(
            $vaultData,
            $vaultKey
        );

        $path = storage_path(
            'vault/personal-vault.pdv'
        );

        if (
            file_put_contents(
                $path,
                $vaultFile,
                LOCK_EX
            ) === false
        ) {
            throw new RuntimeException(
                'Gagal menyimpan file Vault.'
            );
        }

        $this->newLine();

        $this->info(
            'Vault berhasil dibuat.'
        );

        $this->line(
            'Lokasi: ' . $path
        );

        $this->newLine();

        $this->warn(
            'Vault masih kosong dan belum berisi data pribadi.'
        );

        return self::SUCCESS;
    }
}
