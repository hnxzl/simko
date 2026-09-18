<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UseReport extends Model
{
    protected $table = 'use_reports';

    protected $fillable = [
        'borrow_request_id',
        'fuel_before',
        'fuel_after',
        'km_before',
        'km_after',
        'hazards_ok',
        'hazards_note',
        'horn_ok',
        'horn_note',
        'siren_ok',
        'siren_note',
        'tires_ok',
        'tires_note',
        'brakes_ok',
        'brakes_note',
        'battery_ok',
        'battery_note',
        'start_engine_ok',
        'start_engine_note',
        'indicator_before_photos_path',
        'indicator_after_photos_path',
        'location_photos_path',
    ];

    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    public function conditionSummary()
    {
        $fields = ['hazards_ok', 'horn_ok', 'siren_ok', 'tires_ok', 'brakes_ok', 'battery_ok', 'start_engine_ok'];
        $rusak = 0;
        $total = 0;

        foreach ($fields as $f) {
            if ($this->$f) {
                $total++;
                if ($this->$f !== 'Baik') {
                    $rusak++;
                }
            }
        }

        if ($total === 0) return '-';
        if ($rusak === 0) return 'Baik';
        if ($rusak <= 2) return 'Rusak Ringan';
        return 'Rusak Berat';
    }
}
