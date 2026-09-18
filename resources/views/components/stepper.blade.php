{{-- Stepper Component for Borrowing Status — Modern Design --}}
{{-- Usage: @include('components.stepper', ['status' => $borrow->status]) --}}

@php
    $steps = [
        1 => 'Pengajuan',
        2 => 'Persetujuan Manager',
        3 => 'Proses HRD',
        4 => 'Perjalanan',
        5 => 'Selesai',
    ];

    $currentStep = match($status) {
        'pending_manager' => 1,
        'pending_hrd' => 2,
        'pending_bod' => 3,
        'approved' => 4,
        'active' => 4,
        'completed' => 5,
        'rejected' => 0,
        default => 1,
    };

    $isRejected = $status === 'rejected';
@endphp

@if($isRejected)
    <div class="d-flex align-items-center gap-2">
        <span class="badge-soft danger"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>
    </div>
@else
    <div class="stepper-modern">
        @foreach($steps as $num => $label)
            <div class="step-item {{ $num < $currentStep ? 'completed' : ($num === $currentStep ? 'active' : '') }}">
                <div class="step-circle">
                    @if($num < $currentStep)
                        <i class="fa-solid fa-check" style="font-size: 14px;"></i>
                    @else
                        {{ $num }}
                    @endif
                </div>
                <div class="step-label">{{ $label }}</div>
            </div>
            @if(!$loop->last)
                <div class="step-line {{ $num < $currentStep ? 'completed' : '' }}"></div>
            @endif
        @endforeach
    </div>
@endif