<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    protected $fillable = [
        'nama_driver',
        'nik',
        'no_hp',
        'jenis_sim',
        'alamat',
        'foto',
        'status',
    ];

    public function borrowings()
    {
        return $this->hasMany(BorrowRequest::class, 'driver_id');
    }
}