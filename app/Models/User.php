<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method bool hasVerifiedEmail()
 * @method void markEmailAsVerified()
 * @method void sendEmailVerificationNotification()
 */

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'NIP',
        'password',
        'driver_id',
        'role',
        'institution',
        'verification_token',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /* =======================
       RELASI ANTAR MODEL
       ======================= */

    /**
     * Relasi ke supir (jika user juga terdaftar sebagai supir)
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Relasi ke peminjaman kendaraan (sebagai peminjam)
     */
    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    /**
     * Relasi ke notifikasi
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->latest();
    }

    /**
     * Relasi ke inspeksi yang dilakukan (sebagai HRD)
     */
    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'inspected_by');
    }

    /* =======================
       HELPER DAN ROLE
       ======================= */

    public function isAdmin() { return strtolower($this->role) === 'admin'; }
    public function isHRD() { return strtolower($this->role) === 'hrd'; }
    public function isKaryawan() { return strtolower($this->role) === 'karyawan'; }
    public function isManager() { return strtolower($this->role) === 'manager'; }
    public function isBoD() { return strtolower($this->role) === 'bod'; }

    /**
     * Dapatkan label peran dalam bahasa Indonesia untuk UI
     */
    public function getRoleLabelAttribute()
    {
        return match (strtolower($this->role)) {
            'admin' => 'Administrator',
            'hrd' => 'HRD / GA',
            'karyawan' => 'Karyawan',
            'manager' => 'Manager',
            'bod' => 'Board of Directors',
            default => ucfirst($this->role),
        };
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($roles)
    {
        $roles = is_array($roles) ? $roles : explode(',', $roles);
        return in_array(strtolower($this->role), array_map('strtolower', $roles));
    }

    /**
     * Cek apakah user bisa approve peminjaman (Manager atau Admin)
     */
    public function canApprove()
    {
        return $this->isAdmin() || $this->isManager();
    }

    /**
     * Cek apakah user bisa assign vehicle/driver (HRD atau Admin)
     */
    public function canAssign()
    {
        return $this->isAdmin() || $this->isHRD();
    }
}
