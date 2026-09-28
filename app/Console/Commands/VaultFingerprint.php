<?php

namespace App\Console\Commands;

use App\Security\VaultIntegrity;
use Illuminate\Console\Command;

class VaultFingerprint extends Command
{
    protected $signature = 'vault:fingerprint';

    protected $description = 'Generate SHA-256 fingerprint of the Vault';

    public function handle(): int
    {
        $path = storage_path(
            'vault/personal-vault.pdv'
        );

        try {
            $hash = VaultIntegrity::hashFile($path);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->newLine();

        $this->info('Vault SHA-256 Fingerprint:');

        $this->newLine();

        $this->line($hash);

        $this->newLine();

        $this->warn(
            'Fingerprint ini bukan Root Secret dan bukan kunci dekripsi.'
        );

        return self::SUCCESS;
    }
}
