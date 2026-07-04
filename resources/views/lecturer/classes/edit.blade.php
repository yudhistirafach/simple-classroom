@extends('layouts.main')

@section('title', 'Edit Kelas')
@section('page-title', 'Edit Kelas')

@section('content')
<div class="container-fluid d-flex justify-content-center mt-4">
    <div class="card border-0 shadow-sm" style="max-width: 600px; width: 100%; border-radius: 8px;">
        <div class="card-body p-4 p-md-5">
            <h4 class="mb-4 text-dark" style="font-weight: 500;">Edit Kelas</h4>
            
            <form action="{{ route('classes.update', $class) }}" method="POST" id="editClassForm">
                @csrf
                @method('PUT')

                <div class="form-floating mb-4">
                    <input type="text" 
                           class="form-control gc-input @error('name') is-invalid @enderror" 
                           id="name" name="name" 
                           placeholder="Nama Kelas (wajib)" 
                           value="{{ old('name', $class->name) }}" required>
                    <label for="name" class="text-secondary">Nama Kelas (wajib)</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-4">
                    <textarea class="form-control gc-input @error('description') is-invalid @enderror" 
                              id="description" name="description" 
                              placeholder="Deskripsi" 
                              style="height: 100px">{{ old('description', $class->description) }}</textarea>
                    <label for="description" class="text-secondary">Deskripsi</label>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary fw-medium mb-3" style="font-size: 0.9rem;">Jadwal Kelas</label>
                    
                    <div id="schedule-container" class="d-flex flex-column gap-2 mb-2"></div>
                    
                    <button type="button" class="btn btn-link text-decoration-none px-0" id="addScheduleBtn" style="font-weight: 500;">
                        <i class="fas fa-plus me-1"></i> Tambah Waktu
                    </button>
                    
                    <input type="hidden" id="schedule_day" name="schedule_day" 
                           value="{{ old('schedule_day', json_encode($class->schedule_day ?? [])) }}">
                    
                    @error('schedule_day')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('classes.show', $class) }}" class="btn text-secondary fw-medium text-decoration-none py-2 px-3">Batal</a>
                    <button type="submit" class="btn btn-primary fw-medium px-4 py-2" style="border-radius: 4px;">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .gc-input {
        background-color: #f1f3f4;
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 4px 4px 0 0;
        transition: all 0.2s ease-in-out;
    }
    .gc-input:focus {
        background-color: #e8eaed;
        border-bottom: 2px solid #0d6efd;
        box-shadow: none;
    }
    .gc-input:hover:not(:focus) {
        background-color: #e8eaed;
        border-bottom: 2px solid #5f6368;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('schedule-container');
    const hiddenInput = document.getElementById('schedule_day');
    const addBtn = document.getElementById('addScheduleBtn');
    const form = document.getElementById('editClassForm');

    const days = [
        { value: 'monday', label: 'Senin' },
        { value: 'tuesday', label: 'Selasa' },
        { value: 'wednesday', label: 'Rabu' },
        { value: 'thursday', label: 'Kamis' },
        { value: 'friday', label: 'Jumat' },
        { value: 'saturday', label: 'Sabtu' },
        { value: 'sunday', label: 'Minggu' }
    ];

    function createScheduleRow(dayValue = '', startTime = '', endTime = '') {
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center schedule-row';
        
        let dayOptions = '<option value="" disabled>Pilih Hari...</option>';
        days.forEach(d => {
            const selected = d.value === dayValue ? 'selected' : '';
            dayOptions += `<option value="${d.value}" ${selected}>${d.label}</option>`;
        });

        row.innerHTML = `
            <div class="col-md-4">
                <select class="form-select schedule-day gc-input" required>
                    ${dayOptions}
                </select>
            </div>
            <div class="col-md-3">
                <input type="time" class="form-control schedule-start gc-input" value="${startTime}" required>
            </div>
            <div class="col-md-auto text-secondary fw-bold">-</div>
            <div class="col-md-3">
                <input type="time" class="form-control schedule-end gc-input" value="${endTime}" required>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-link text-danger p-0 remove-schedule" title="Hapus">
                    <i class="fas fa-times fs-5"></i>
                </button>
            </div>
        `;

        row.querySelector('.remove-schedule').addEventListener('click', function() {
            row.remove();
            updateHiddenJSON();
        });

        row.querySelectorAll('select, input').forEach(el => {
            el.addEventListener('change', updateHiddenJSON);
        });

        container.appendChild(row);
    }

    function updateHiddenJSON() {
        const rows = container.querySelectorAll('.schedule-row');
        const scheduleData = {};

        rows.forEach(row => {
            const day = row.querySelector('.schedule-day').value;
            const start = row.querySelector('.schedule-start').value;
            const end = row.querySelector('.schedule-end').value;

            if (day && start && end) {
                scheduleData[day] = `${start}-${end}`;
            }
        });

        hiddenInput.value = JSON.stringify(scheduleData);
    }

    try {
        const rawValue = hiddenInput.value.trim();
        console.log('Raw schedule_day:', rawValue);
        
        if (rawValue && rawValue !== '[]' && rawValue !== '{}' && rawValue !== '""') {
            const oldData = JSON.parse(rawValue);
            if (oldData && typeof oldData === 'object' && Object.keys(oldData).length > 0) {
                let hasData = false;
                for (const [day, timeRange] of Object.entries(oldData)) {
                    if (typeof timeRange === 'string' && timeRange.includes('-')) {
                        const parts = timeRange.split('-');
                        const start = parts[0] ? parts[0].trim() : '';
                        const end = parts[1] ? parts[1].trim() : '';
                        if (start && end) {
                            createScheduleRow(day, start, end);
                            hasData = true;
                        }
                    }
                }
                if (!hasData) {
                    createScheduleRow();
                }
            } else {
                createScheduleRow();
            }
        } else {
            createScheduleRow();
        }
    } catch (e) {
        console.error('Error parsing schedule data:', e);
        createScheduleRow();
    }

    addBtn.addEventListener('click', function() {
        createScheduleRow();
    });

    form.addEventListener('submit', updateHiddenJSON);
});
</script>
@endsection