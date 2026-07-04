@if(session()->has('notification'))
    @php
        $notification = session('notification');
        $status = $notification['status'] ?? true;
        $message = $notification['message'] ?? '';
        $type = $status ? 'success' : 'error';
        $icon = $status ? 'fa-check-circle' : 'fa-times-circle';
    @endphp

    <div class="toast-custom {{ $type }} show" role="alert">
        <div class="toast-body">
            <i class="fas {{ $icon }}"></i>
            {{ $message }}
        </div>
    </div>

    <style>
        .toast-custom {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 0.75rem 1.25rem;
            margin-bottom: 0.5rem;
            border-left: 5px solid #1a73e8;
            animation: slideIn 0.3s ease;
            min-width: 250px;
        }
        .toast-custom.success { border-left-color: #0f9d58; }
        .toast-custom.error { border-left-color: #d93025; }
        .toast-custom .toast-body {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #212529;
        }
        .toast-custom .toast-body i {
            font-size: 1.3rem;
        }
        .toast-custom.success .toast-body i { color: #0f9d58; }
        .toast-custom.error .toast-body i { color: #d93025; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        /* Auto dismiss after 5s */
        .toast-custom {
            transition: opacity 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.toast-custom').forEach(function(el) {
                    el.style.opacity = '0';
                    setTimeout(function() {
                        el.remove();
                    }, 300);
                });
            }, 5000);
        });
    </script>
@endif