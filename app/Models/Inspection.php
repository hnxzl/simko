<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_request_id',
        'fuel_level',
        'last_km',
        'physical_condition_notes',
        'is_damaged',
        'damage_photos',
        'inspected_by',
    ];

    protected $casts = [
        'is_damaged' => 'boolean',
    ];

    /**
     * Relasi ke peminjaman kendaraan
     */
    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    /**
     * Relasi ke user yang melakukan inspeksi (HRD)
     */
    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }

    /**
     * Helper: apakah kendaraan rusak
     */
    public function isDamaged()
    {
        return $this->is_damaged;
    }
}
