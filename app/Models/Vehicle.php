<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_bmn',
        'name',
        'distance',
        'year',
        'merk',
        'plat_nomor',
        'tipe',
        'factory',
        'load_capacity',
        'weight',
        'bahan_bakar',
        'lokasi',
        'warna',
        'status',
        'last_km_for_oil',
        'oil_change_interval',
        'fuel_percent',
        'notes',
        'photo_path',
    ];

    /**
     * Relasi ke peminjaman kendaraan
     */
    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    /**
     * Relasi ke pengecekan rutin (legacy - will be removed)
     */
    public function checkItems()
    {
        return $this->hasMany(CheckItem::class);
    }

    /**
     * Relasi ke penggantian oli
     */
    public function oilChanges()
    {
        return $this->hasMany(OilChange::class);
    }

    /**
     * Helper: apakah kendaraan sedang dipinjam
     */
    public function isBorrowed()
    {
        return $this->status === 'in_use';
    }

    /**
     * Helper: apakah kendaraan dalam perawatan
     */
    public function isMaintenance()
    {
        return $this->status === 'maintenance';
    }

    /**
     * Helper: apakah kendaraan tersedia
     */
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    /**
     * Label status kendaraan untuk UI
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'available'   => 'Tersedia',
            'in_use'      => 'Sedang Digunakan',
            'maintenance' => 'Dalam Perbaikan',
            default       => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Badge class untuk status kendaraan
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            'available'   => 'bg-green-100 text-green-800',
            'in_use'      => 'bg-blue-100 text-blue-800',
            'maintenance' => 'bg-red-100 text-red-800',
            default       => 'bg-gray-100 text-gray-800',
        };
    }
}
