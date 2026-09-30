<?php

namespace App\Security;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class VaultStorage
{
    private const BLOB_API_VERSION = '12';

    public function read(string $localPath): ?string
    {
        return match (config('vault.driver', 'local')) {
            'local' => $this->readLocal($localPath),
            'vercel_blob' => $this->readBlob(),
            default => throw new RuntimeException(
                'Vault storage driver is not supported.'
            ),
        };
    }

    public function write(string $localPath, string $contents): void
    {
        match (config('vault.driver', 'local')) {
            'local' => $this->writeLocal($localPath, $contents),
            'vercel_blob' => $this->writeBlob($contents),
            default => throw new RuntimeException(
                'Vault storage driver is not supported.'
            ),
        };
    }

    private function readLocal(string $path): ?string
    {
        if (!is_file($path)) {
            return null;
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException('Unable to read the vault file.');
        }

        return $contents;
    }

    private function writeLocal(string $path, string $contents): void
    {
        $directory = dirname($path);

        if (!is_dir($directory) && !mkdir($directory, 0700, true) && !is_dir($directory)) {
            throw new RuntimeException('Unable to create the vault directory.');
        }

        if (file_put_contents($path, $contents, LOCK_EX) === false) {
            throw new RuntimeException('Unable to save the vault file.');
        }
    }

    private function readBlob(): ?string
    {
        [$token, $storeId, $pathname] = $this->blobConfiguration();
        $url = sprintf(
            'https://%s.private.blob.vercel-storage.com/%s',
            $storeId,
            $this->encodedPathname($pathname)
        );

        $response = Http::withToken($token)
            ->timeout(20)
            ->get($url);

        if ($response->status() === 404) {
            return null;
        }

        $response->throw();

        return $response->body();
    }

    private function writeBlob(string $contents): void
    {
        [$token, $storeId, $pathname] = $this->blobConfiguration();
        $url = 'https://vercel.com/api/blob/?pathname=' . rawurlencode($pathname);

        Http::withToken($token)
            ->withHeaders([
                'x-api-version' => self::BLOB_API_VERSION,
                'x-vercel-blob-store-id' => $storeId,
                'x-vercel-blob-access' => 'private',
                'x-allow-overwrite' => '1',
                'x-content-type' => 'application/octet-stream',
            ])
            ->timeout(20)
            ->withBody($contents, 'application/octet-stream')
            ->put($url)
            ->throw();
    }

    private function blobConfiguration(): array
    {
        $token = trim((string) config('services.vercel_blob.token'));
        $parts = explode('_', $token, 5);

        if (
            $token === '' ||
            ($parts[0] ?? null) !== 'vercel' ||
            ($parts[1] ?? null) !== 'blob' ||
            ($parts[2] ?? null) !== 'rw' ||
            empty($parts[3])
        ) {
            throw new RuntimeException(
                'Vercel Blob requires a valid BLOB_READ_WRITE_TOKEN.'
            );
        }

        $pathname = trim((string) config('vault.blob_path'));

        if ($pathname === '' || str_contains($pathname, '..')) {
            throw new RuntimeException('Vercel Blob pathname is invalid.');
        }

        return [$token, $parts[3], $pathname];
    }

    private function encodedPathname(string $pathname): string
    {
        return implode(
            '/',
            array_map('rawurlencode', explode('/', $pathname))
        );
    }
}
