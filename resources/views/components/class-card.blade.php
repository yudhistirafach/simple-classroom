@props(['class', 'index' => 0])

@php
    $colorIndex = ($class->id + $index) % 10;
    $colors = [
        0 => '#1a73e8', 1 => '#d93025', 2 => '#f29900',
        3 => '#0f9d58', 4 => '#9c27b0', 5 => '#00acc1',
        6 => '#e67c73', 7 => '#4285f4', 8 => '#fbbc04',
        9 => '#34a853'
    ];
    $cardColor = $colors[$colorIndex] ?? '#1a73e8';
@endphp

<a href="{{ route('classes.show', $class) }}" class="class-card-link">
    <div class="class-card">
        <div class="class-card-header" style="background-color: {{ $cardColor }};">
            <h5 class="class-card-title">{{ $class->name }}</h5>
        </div>
        <div class="class-card-body">
            @if($class->description)
                <p class="class-card-desc">{{ Str::limit($class->description, 80) }}</p>
            @endif
            <div class="class-card-meta">
                <span><i class="fas fa-users"></i> {{ $class->participants_count ?? 0 }}</span>
                @if($class->schedule_day)
                    <span><i class="fas fa-clock"></i> {{ count(json_decode($class->schedule_day, true) ?? []) }} hari</span>
                @endif
            </div>
        </div>
    </div>
</a>

@push('styles')
<style>
    .class-card-link {
        text-decoration: none;
        display: block;
    }
    .class-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        transition: all 0.2s ease;
        background: white;
        height: 100%;
        min-height: 180px;
        cursor: pointer;
    }
    .class-card:active {
        transform: scale(0.98);
    }
    @media (min-width: 768px) {
        .class-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    }
    .class-card-header {
        padding: 16px 16px 48px 16px;
        color: white;
        min-height: 72px;
        display: flex;
        align-items: center;
    }
    .class-card-title {
        margin: 0;
        font-weight: 600;
        font-size: 1rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .class-card-body {
        padding: 12px 16px 16px 16px;
        background: white;
    }
    .class-card-desc {
        color: #5f6368;
        font-size: 0.85rem;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.4;
    }
    .class-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 0.8rem;
        color: #5f6368;
    }
    .class-card-meta i {
        margin-right: 4px;
        width: 16px;
        text-align: center;
    }
</style>
@endpush