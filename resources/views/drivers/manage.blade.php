@extends('layouts.app')

@section('title', 'Kelola Tim')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Pengaturan Tim</h1>
        <p class="page-subtitle">Atur ketua dan anggota tim untuk setiap driver</p>
    </div>
    <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
    </a>
</div>

<form action="{{ route('drivers.manage.save') }}" method="POST" id="saveForm">
    @csrf

    <div class="row g-3">
        @foreach($drivers as $driver)
        <div class="col-md-4">
            <div class="card h-100 driver-box" data-driver="{{ $driver->id }}">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-weight:600;font-size:14px">
                            {{ strtoupper(substr($driver->name, 0, 1)) }}
                        </div>
                        <h6 class="fw-semibold mb-0">{{ $driver->name }}</h6>
                    </div>

                    {{-- KETUA --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:12px;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px">
                            <i class="fa-solid fa-crown me-1" style="color:#f59e0b"></i> Ketua
                        </label>
                        <div class="driver-leader drop-zone"
                             data-driver="{{ $driver->id }}"
                             id="leader-{{ $driver->id }}">
                            @if($driver->leader)
                                <div class="drag-item" data-user="{{ $driver->leader->id }}">
                                    <i class="fa-solid fa-user me-1"></i> {{ $driver->leader->name }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ANGGOTA --}}
                    <div>
                        <label class="form-label fw-semibold" style="font-size:12px;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px">
                            <i class="fa-solid fa-users me-1" style="color:var(--primary)"></i> Anggota
                        </label>
                        <div class="driver-members drop-zone"
                             data-driver="{{ $driver->id }}"
                             id="members-{{ $driver->id }}">
                            @foreach($driver->members->where('id','!=',$driver->leader_id) as $member)
                                <div class="drag-item" data-user="{{ $member->id }}">
                                    <i class="fa-solid fa-user me-1"></i> {{ $member->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <input type="hidden" name="payload" id="payload">

    <div class="d-flex justify-content-end mt-4">
        <button class="btn btn-primary px-4">
            <i class="fa-solid fa-save me-2"></i>Simpan Perubahan
        </button>
    </div>
</form>

{{-- SortableJS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.driver-leader, .driver-members').forEach(list => {
        new Sortable(list, {
            group: 'shared-driver',
            animation: 150,
            ghostClass: 'sortable-ghost',
        });
    });

    document.getElementById('saveForm').addEventListener('submit', function(){
        const result = {};
        document.querySelectorAll('.driver-box').forEach(box => {
            const driverId = box.dataset.driver;
            const leader = box.querySelector('.driver-leader .drag-item');
            const members = box.querySelectorAll('.driver-members .drag-item');
            result[driverId] = {
                leader: leader ? leader.dataset.user : null,
                members: Array.from(members).map(m => m.dataset.user)
            };
        });
        document.getElementById('payload').value = JSON.stringify(result);
    });
});
</script>

<style>
.drop-zone {
    min-height: 48px;
    padding: 8px;
    background: var(--bg);
    border: 2px dashed #d1d5db;
    border-radius: var(--radius);
    transition: border-color .2s;
}
.drop-zone:hover { border-color: var(--primary); }
.drag-item {
    padding: 8px 12px;
    background: var(--primary-light);
    color: var(--primary);
    border-radius: 8px;
    margin-bottom: 6px;
    cursor: grab;
    font-weight: 500;
    font-size: 13px;
    transition: all .15s;
}
.drag-item:hover {
    background: #e0e7ff;
    transform: translateY(-1px);
}
.drag-item:last-child { margin-bottom: 0; }
.sortable-ghost { opacity: .4; background: #fef3c7; }
</style>
@endsection
