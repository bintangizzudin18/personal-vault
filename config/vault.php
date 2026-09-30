<?php

return [
    'driver' => env('VAULT_STORAGE_DRIVER', 'local'),
    'blob_path' => env('VAULT_BLOB_PATH', 'personal-vault.pdv'),
];
