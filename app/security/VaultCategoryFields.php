<?php

namespace App\Security;

use InvalidArgumentException;

class VaultCategoryFields
{
    private const DEFINITIONS = [

        'recovery_security' => [
            'Nama',
            'Recovery Code',
            'Recovery Email',
            'Catatan Keamanan',
        ],

        'm_banking' => [
            'Nama Bank',
            'Nomor Rekening',
            'Nama Pemilik',
            'Username',
            'Password',
            'PIN',
            'Nomor HP',
        ],

        'e_wallet' => [
            'Nama E-Wallet',
            'Nomor HP',
            'Email',
            'Username',
            'Password',
            'PIN',
        ],

        'email' => [
            'Alamat Email',
            'Username',
            'Password',
            'Recovery Email',
            'Nomor HP',
        ],

        'social_media' => [
            'Platform',
            'Username',
            'Email',
            'Password',
            'Nomor HP',
            'Recovery Email',
        ],

        'game' => [
            'Nama Game',
            'Username',
            'Email',
            'Password',
            'ID Game',
            'Nomor HP',
        ],

        'education' => [
            'Nama Institusi',
            'Username',
            'Email',
            'Password',
            'NIM / ID',
            'Website',
        ],

        'work_freelance' => [
            'Nama Platform',
            'Username',
            'Email',
            'Password',
            'Nomor HP',
            'Website',
        ],

        'shopping' => [
            'Nama Toko / Platform',
            'Username',
            'Email',
            'Password',
            'Nomor HP',
            'Alamat',
        ],

        'devices_services' => [
            'Nama Perangkat / Layanan',
            'Username',
            'Email',
            'Password',
            'PIN',
            'Nomor Seri',
        ],

        'investment_finance' => [
            'Nama Platform',
            'Username',
            'Email',
            'Password',
            'Nomor Rekening / ID',
            'Nomor HP',
        ],

        'identity_personal' => [
            'Jenis Identitas',
            'Nomor Identitas',
            'Nama Lengkap',
            'Tanggal Lahir',
            'Alamat',
            'Nomor HP',
        ],

        'documents' => [
            'Nama Dokumen',
            'Nomor Dokumen',
            'Tanggal Terbit',
            'Tanggal Berlaku',
            'Lokasi Penyimpanan',
        ],

        'notes' => [],
    ];

    public static function get(
        string $category
    ): array {
        if (!array_key_exists(
            $category,
            self::DEFINITIONS
        )) {
            throw new InvalidArgumentException(
                'Kategori Vault tidak valid.'
            );
        }

        return self::DEFINITIONS[$category];
    }

    public static function exists(
        string $category
    ): bool {
        return array_key_exists(
            $category,
            self::DEFINITIONS
        );
    }
}
