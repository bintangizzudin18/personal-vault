<?php

namespace App\Console\Commands;

use App\Security\RootSecret;
use Illuminate\Console\Command;

class GenerateRootSecret extends Command
{
    protected $signature = 'vault:generate-root';

    protected $description = 'Generate a new Vault Root Secret';

    public function handle(): int
    {
        $this->newLine();

        $this->warn('==============================================');
        $this->warn('       PERSONAL DIGITAL VAULT');
        $this->warn('           ROOT SECRET');
        $this->warn('==============================================');

        $this->newLine();

        $this->warn('PERINGATAN:');
        $this->warn('Root Secret adalah rahasia utama Vault.');
        $this->warn('Jangan kirim Root Secret kepada siapa pun.');
        $this->warn('Jangan simpan di GitHub atau database.');

        $this->newLine();

        $rootSecret = RootSecret::generate();

        $this->info('ROOT SECRET BARU:');
        $this->newLine();

        $this->line($rootSecret);

        $this->newLine();

        $this->error('Simpan Root Secret ini secara offline dan aman.');
        $this->error('Jangan masukkan Root Secret ke dalam Vault.');

        $this->newLine();

        return self::SUCCESS;
    }
}
