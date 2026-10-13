<?php

namespace App\Support;

final class Roles
{
    public const ADMIN = 'admin';
    public const KEPALA = 'kepala_sekolah';
    public const WALI_KELAS = 'wali_kelas';
    public const GURU = 'guru';

    public const LABELS = [
        self::ADMIN      => 'Admin',
        self::KEPALA     => 'Kepala Sekolah',
        self::WALI_KELAS => 'Wali Kelas',
        self::GURU       => 'Guru',
    ];

    /** Halaman login khusus per peran (kunci = parameter di route). */
    public const PORTALS = [
        'kepala_sekolah' => [
            'role'  => self::KEPALA,
            'label' => 'Kepala Sekolah',
            'icon'  => 'bi-person-badge',
        ],
        'wali_kelas' => [
            'role'  => self::WALI_KELAS,
            'label' => 'Wali Kelas',
            'icon'  => 'bi-people',
        ],
    ];

    public static function label(?string $role): string
    {
        return self::LABELS[$role] ?? 'Tanpa Peran';
    }

    public static function badgeClass(?string $role): string
    {
        return match ($role) {
            self::ADMIN      => 'bg-danger-subtle text-danger',
            self::KEPALA     => 'bg-primary-subtle text-primary',
            self::WALI_KELAS => 'bg-warning-subtle text-warning-emphasis',
            self::GURU       => 'bg-success-subtle text-success',
            default          => 'bg-light text-dark border',
        };
    }

    /** Admin atau Kepala Sekolah. */
    public static function isLeader(?string $role): bool
    {
        return in_array($role, [self::ADMIN, self::KEPALA], true);
    }
}