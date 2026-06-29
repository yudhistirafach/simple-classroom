@props(['notification'])

@if ($notification)
    <div class="toast align-items-center {{ $notification['status'] ?? true ? 'text-bg-success' : 'text-bg-danger' }} border-0"
        role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4000">
        <div class="d-flex">
            <div class="toast-body text-white">
                {{ $notification['message'] ?? '' }}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
@endif

@once
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toastElements = document.querySelectorAll('.toast');

            if (!toastElements.length) {
                return;
            }

            if (window.bootstrap && window.bootstrap.Toast) {
                toastElements.forEach(function(toastNode) {
                    new window.bootstrap.Toast(toastNode).show();
                });
                return;
            }

            const existingScript = document.querySelector('script[data-bootstrap-toast]');
            if (existingScript) {
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js';
            script.setAttribute('data-bootstrap-toast', 'true');
            script.onload = function() {
                toastElements.forEach(function(toastNode) {
                    new window.bootstrap.Toast(toastNode).show();
                });
            };
            document.body.appendChild(script);
        });
    </script>
@endonce
