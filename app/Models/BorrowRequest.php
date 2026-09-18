<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BorrowRequest extends Model
{
    use HasFactory;

    protected $table = 'borrow_requests';

    protected $fillable = [
        'kode_pinjam',
        'user_id',
        'vehicle_id',
        'driver_id',
        'purpose_text',
        'destination_address',
        'start_at',
        'end_at',
        'start_time',
        'end_time',
        'surat_tugas_path',
        'status',
        'manager_approval',
        'bod_approval',
        'need_bod_approval',
        'manager_notes',
        'hrd_notes',
        'bod_notes',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'lampiran_path',
    ];

    protected $casts = [
        'start_at'         => 'datetime:Y-m-d',
        'end_at'           => 'datetime:Y-m-d',
        'start_time'       => 'string',
        'end_time'         => 'string',
        'manager_approval' => 'boolean',
        'bod_approval'     => 'boolean',
        'need_bod_approval' => 'boolean',
        'approved_at'      => 'datetime',
    ];

    // RELATIONS
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function inspection()
    {
        return $this->hasOne(Inspection::class);
    }

    public function useReport()
    {
        return $this->hasOne(UseReport::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // STATUS HELPERS
    public function isPendingManager()
    {
        return $this->status === 'pending_manager';
    }

    public function isPendingHRD()
    {
        return $this->status === 'pending_hrd';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPendingBoD()
    {
        return $this->status === 'pending_bod';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get status label for UI display
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending_manager' => 'Menunggu Persetujuan Manager',
            'pending_hrd'     => 'Menunggu Proses HRD',
            'pending_bod'     => 'Menunggu Persetujuan BoD',
            'approved'        => 'Disetujui',
            'active'          => 'Sedang Berlangsung',
            'completed'       => 'Selesai',
            'rejected'        => 'Ditolak',
            default           => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /**
     * Get stepper step number (1-5)
     */
    public function getStepperStepAttribute()
    {
        return match ($this->status) {
            'pending_manager' => 1,
            'pending_hrd'     => 2,
            'pending_bod'     => 3,
            'approved', 'active' => 4,
            'completed'       => 5,
            'rejected'        => 0, // Special case
            default           => 1,
        };
    }

    /**
     * Sync vehicle status based on borrowing status
     */
    public function syncVehicleStatus()
    {
        if (!$this->vehicle) return;

        // Don't override maintenance status (set by inspection if damaged)
        if ($this->vehicle->status === 'maintenance') return;

        if ($this->status === 'active') {
            $this->vehicle->update([
                'status' => 'in_use',
                'lokasi' => $this->destination_address ?? $this->vehicle->lokasi
            ]);
        } elseif (in_array($this->status, ['completed', 'rejected'])) {
            $this->vehicle->update([
                'status' => 'available',
                'lokasi' => 'Markas Utama'
            ]);
        }
    }

    /**
     * Sync driver status based on borrowing status
     */
    public function syncDriverStatus()
    {
        if (!$this->driver) return;

        if ($this->status === 'active') {
            $this->driver->update(['status' => 'bertugas']);
        } elseif (in_array($this->status, ['completed', 'rejected'])) {
            $this->driver->update(['status' => 'aktif']);
        }
    }
}