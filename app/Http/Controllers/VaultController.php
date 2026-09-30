<?php

namespace App\Http\Controllers;

use App\Security\KeyDerivation;
use App\Security\VaultCategoryFields;
use App\Security\VaultFile;
use App\Security\VaultItem;
use App\Security\VaultRepository;
use App\Security\VaultStorage;
use Illuminate\Http\Request;

class VaultController extends Controller
{
    private function getVaultKey(Request $request): ?string
    {
        $encodedKey = $request->session()->get('vault_key');

        if (!$encodedKey) {
            return null;
        }

        return base64_decode(
            $encodedKey,
            true
        );
    }

    private function getRepository(
        Request $request
    ): ?VaultRepository {
        $vaultKey = $this->getVaultKey($request);

        if (!$vaultKey) {
            return null;
        }

        return new VaultRepository(
            storage_path('vault/personal-vault.pdv'),
            $vaultKey
        );
    }

    public function showUnlock()
    {
        return view('vault.unlock');
    }

    public function unlock(Request $request)
    {
        $request->validate([
            'root_secret' => [
                'required',
                'string',
            ],
        ]);

        $rootSecret = $request->input(
            'root_secret'
        );

        $vaultPath = storage_path(
            'vault/personal-vault.pdv'
        );

        $vaultContents = app(VaultStorage::class)->read($vaultPath);

        if ($vaultContents === null) {
            return back()->withErrors([
                'root_secret' =>
                    'File Vault tidak ditemukan.',
            ]);
        }

        try {
            $vaultKey = KeyDerivation::deriveVaultKey(
                $rootSecret
            );

            VaultFile::read(
                $vaultContents,
                $vaultKey
            );
        } catch (\Throwable $e) {
            return back()->withErrors([
                'root_secret' =>
                    'Root Secret salah atau Vault rusak.',
            ]);
        }

        $request->session()->put(
            'vault_key',
            base64_encode($vaultKey)
        );

        $request->session()->regenerate();

        return redirect()->route(
            'vault.dashboard'
        );
    }

    public function dashboard(Request $request)
    {
        $vaultKey = $this->getVaultKey($request);

        if (!$vaultKey) {
            return redirect()->route(
                'vault.unlock'
            );
        }

        $vaultPath = storage_path(
            'vault/personal-vault.pdv'
        );

        try {
            $vaultContents = app(VaultStorage::class)->read($vaultPath);

            if ($vaultContents === null) {
                throw new \RuntimeException('File Vault tidak ditemukan.');
            }

            $vaultData = VaultFile::read(
                $vaultContents,
                $vaultKey
            );
        } catch (\Throwable $e) {
            $request->session()->forget(
                'vault_key'
            );

            return redirect()->route(
                'vault.unlock'
            );
        }

        return view(
            'vault.dashboard',
            compact('vaultData')
        );
    }

    public function category(
        Request $request,
        string $category
    ) {
        $repository = $this->getRepository($request);

        if (!$repository) {
            return redirect()->route(
                'vault.unlock'
            );
        }

        try {
            $items = $repository->getItems(
                $category
            );
        } catch (\Throwable $e) {
            abort(404);
        }

        return view(
            'vault.category',
            compact(
                'category',
                'items'
            )
        );
    }

    public function createItem(
        Request $request,
        string $category
    ) {
        $repository = $this->getRepository($request);

        if (!$repository) {
            return redirect()->route(
                'vault.unlock'
            );
        }

        try {
            $repository->getItems($category);

            $fieldDefinitions =
                VaultCategoryFields::get($category);

        } catch (\Throwable $e) {
            abort(404);
        }

        return view(
            'vault.item-form',
            [
                'category' => $category,
                'item' => null,
                'fieldDefinitions' => $fieldDefinitions,
            ]
        );
    }

    public function storeItem(
        Request $request,
        string $category
    ) {
        $repository = $this->getRepository($request);

        if (!$repository) {
            return redirect()->route(
                'vault.unlock'
            );
        }

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:200',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'fields' => [
                'nullable',
                'array',
            ],

            'fields.*' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        try {
            $repository->getItems($category);

            $allowedFields =
                VaultCategoryFields::get($category);

        } catch (\Throwable $e) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil field dari form
        |--------------------------------------------------------------------------
        |
        | Form menggunakan:
        |
        | fields[Username]
        | fields[Password]
        | fields[Email]
        |
        */

        $submittedFields = $request->input(
            'fields',
            []
        );

        $fields = [];

        foreach ($submittedFields as $name => $value) {

            /*
             * Hanya field yang memang didefinisikan
             * untuk kategori tersebut yang boleh disimpan.
             */
            if (!in_array(
                $name,
                $allowedFields,
                true
            )) {
                continue;
            }

            $fields[$name] = $value ?? '';
        }

        try {
            $item = VaultItem::create(
                $category,
                $validated['title'],
                $fields,
                $validated['notes'] ?? ''
            );

            $repository->addItem(
                $category,
                $item
            );

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'vault' =>
                        'Gagal menyimpan item Vault.',
                ]);
        }

        return redirect()->route(
            'vault.category',
            $category
        )->with(
            'success',
            'Item berhasil ditambahkan.'
        );
    }

    public function editItem(
        Request $request,
        string $category,
        string $id
    ) {
        $repository = $this->getRepository($request);

        if (!$repository) {
            return redirect()->route(
                'vault.unlock'
            );
        }

        try {
            $fieldDefinitions =
                VaultCategoryFields::get($category);

        } catch (\Throwable $e) {
            abort(404);
        }

        $item = $repository->findItem(
            $category,
            $id
        );

        if (!$item) {
            abort(404);
        }

        return view(
            'vault.item-form',
            compact(
                'category',
                'item',
                'fieldDefinitions'
            )
        );
    }

    public function updateItem(
        Request $request,
        string $category,
        string $id
    ) {
        $repository = $this->getRepository($request);

        if (!$repository) {
            return redirect()->route(
                'vault.unlock'
            );
        }

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:200',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'fields' => [
                'nullable',
                'array',
            ],

            'fields.*' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        try {
            $allowedFields =
                VaultCategoryFields::get($category);

        } catch (\Throwable $e) {
            abort(404);
        }

        $existingItem = $repository->findItem(
            $category,
            $id
        );

        if (!$existingItem) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil field dari form
        |--------------------------------------------------------------------------
        */

        $submittedFields = $request->input(
            'fields',
            []
        );

        $fields = [];

        foreach ($submittedFields as $name => $value) {

            /*
             * Jangan menerima nama field sembarangan
             * dari request.
             */
            if (!in_array(
                $name,
                $allowedFields,
                true
            )) {
                continue;
            }

            $fields[$name] = $value ?? '';
        }

        try {
            $updatedItem = [
                'type' => $category,

                'title' =>
                    $validated['title'],

                'fields' =>
                    $fields,

                'notes' =>
                    $validated['notes'] ?? '',
            ];

            $repository->updateItem(
                $category,
                $id,
                $updatedItem
            );

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'vault' =>
                        'Gagal memperbarui item Vault.',
                ]);
        }

        return redirect()->route(
            'vault.category',
            $category
        )->with(
            'success',
            'Item berhasil diperbarui.'
        );
    }

    public function destroyItem(
        Request $request,
        string $category,
        string $id
    ) {
        $repository = $this->getRepository($request);

        if (!$repository) {
            return redirect()->route(
                'vault.unlock'
            );
        }

        try {
            $repository->deleteItem(
                $category,
                $id
            );

        } catch (\Throwable $e) {
            return back()->withErrors([
                'vault' =>
                    'Gagal menghapus item Vault.',
            ]);
        }

        return redirect()->route(
            'vault.category',
            $category
        )->with(
            'success',
            'Item berhasil dihapus.'
        );
    }

    public function lock(Request $request)
    {
        $request->session()->forget(
            'vault_key'
        );

        return redirect()->route(
            'vault.unlock'
        );
    }
}
