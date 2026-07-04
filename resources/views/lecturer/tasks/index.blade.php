@php
    $user = auth()->user();
    $isOwner = $class->isOwner($user);
@endphp

<div class="tasks-section">
    @if($isOwner)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-medium">Daftar Tugas</h5>
            <a href="{{ route('tasks.create', $class) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> Buat Tugas
            </a>
        </div>
    @else
        <h5 class="mb-3 fw-medium">Daftar Tugas</h5>
    @endif

    @if($tasks->count() > 0)
        <div class="tasks-list">
            @foreach($tasks as $task)
                <div class="card border-0 shadow-sm mb-3 task-card">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                            <div class="task-info flex-grow-1" style="min-width: 0;">
                                <h6 class="mb-1 fw-semibold task-title">{{ $task->title }}</h6>
                                @if($task->description)
                                    <p class="text-muted small mb-2 task-description">{{ Str::limit($task->description, 120) }}</p>
                                @endif
                                <div class="d-flex flex-wrap align-items-center gap-2 gap-md-3 text-muted small">
                                    <span>
                                        <i class="far fa-calendar-alt me-1"></i>
                                        Deadline: {{ $task->deadline_at->format('d M Y H:i') }}
                                    </span>
                                    <span class="badge {{ $task->deadline_at->isPast() ? 'bg-danger' : 'bg-success' }} rounded-pill">
                                        {{ $task->deadline_at->isPast() ? 'Expired' : 'Active' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($isOwner)
                                <div class="task-actions d-flex gap-1 flex-shrink-0">
                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary btn-sm rounded-circle" style="width: 36px; height: 36px;" title="Edit Tugas">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tugas ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle" style="width: 36px; height: 36px;" title="Hapus Tugas">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div class="text-muted opacity-25 mb-3">
                <i class="fas fa-clipboard-list" style="font-size: 3rem;"></i>
            </div>
            <p class="text-muted mb-2">Belum ada tugas di kelas ini.</p>
            @if($isOwner)
                <a href="{{ route('tasks.create', $class) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-plus me-1"></i> Buat Tugas Pertama
                </a>
            @endif
        </div>
    @endif
</div>

@push('styles')
<style>
    .task-card {
        border-radius: 8px;
        transition: box-shadow 0.2s ease-in-out;
    }
    .task-card:hover {
        box-shadow: 0 4px 12px rgba(60,64,67,0.12) !important;
    }
    .task-title {
        color: #202124;
        font-size: 1rem;
    }
    .task-description {
        font-size: 0.875rem;
        line-height: 1.4;
        color: #5f6368;
    }
    @media (max-width: 576px) {
        .task-actions {
            width: 100%;
            justify-content: flex-end;
            margin-top: 8px;
        }
        .task-info .badge {
            font-size: 0.7rem;
        }
    }
</style>
@endpush