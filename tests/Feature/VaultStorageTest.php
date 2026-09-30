<?php

namespace Tests\Feature;

use App\Security\KeyDerivation;
use App\Security\VaultFile;
use App\Security\VaultStorage;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VaultStorageTest extends TestCase
{
    private const TOKEN = 'vercel_blob_rw_test-store_secret';

    public function test_it_reads_the_vault_from_private_blob_storage(): void
    {
        $url = 'https://test-store.private.blob.vercel-storage.com/personal-vault.pdv';
        $this->configureBlobStorage();

        Http::fake([
            $url => Http::response('encrypted-vault', 200),
        ]);

        $contents = app(VaultStorage::class)->read(
            storage_path('vault/personal-vault.pdv')
        );

        $this->assertSame('encrypted-vault', $contents);
        Http::assertSent(fn (ClientRequest $request) =>
            $request->method() === 'GET' &&
            $request->hasHeader('Authorization', 'Bearer '.self::TOKEN)
        );
    }

    public function test_it_writes_encrypted_vault_data_to_private_blob_storage(): void
    {
        $this->configureBlobStorage();

        Http::fake([
            'https://vercel.com/api/blob/*' => Http::response([
                'pathname' => 'personal-vault.pdv',
            ], 200),
        ]);

        app(VaultStorage::class)->write(
            storage_path('vault/personal-vault.pdv'),
            'encrypted-vault'
        );

        Http::assertSent(fn (ClientRequest $request) =>
            $request->method() === 'PUT' &&
            $request->hasHeader('Authorization', 'Bearer '.self::TOKEN) &&
            $request->hasHeader('x-vercel-blob-access', 'private') &&
            $request->hasHeader('x-allow-overwrite', '1') &&
            $request->body() === 'encrypted-vault'
        );
    }

    public function test_unlock_uses_the_vault_file_from_private_blob_storage(): void
    {
        $this->configureBlobStorage();

        $rootSecret = 'test-secret-do-not-use-this-as-a-real-secret';
        $vaultKey = KeyDerivation::deriveVaultKey($rootSecret);
        $vaultContents = VaultFile::create([
            'vault_name' => 'Test Vault',
            'created_at' => now()->toIso8601String(),
            'categories' => [],
        ], $vaultKey);

        Http::fake([
            'https://test-store.private.blob.vercel-storage.com/personal-vault.pdv' =>
                Http::response($vaultContents, 200),
        ]);

        $response = $this->post('/vault/unlock', [
            'root_secret' => $rootSecret,
        ]);

        $response->assertRedirect(route('vault.dashboard'));
    }

    private function configureBlobStorage(): void
    {
        Config::set('vault.driver', 'vercel_blob');
        Config::set('vault.blob_path', 'personal-vault.pdv');
        Config::set('services.vercel_blob.token', self::TOKEN);
    }
}
