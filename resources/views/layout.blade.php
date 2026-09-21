<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Task Project' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        :root { --ink: #18212f; --muted: #6d7888; --line: #e4e9ef; --paper: #fff; --wash: #f4f7fb; --blue: #2764e7; --blue-dark: #1749b3; --mint: #dff7ed; }
        * { box-sizing: border-box; }
        body { margin: 0; color: var(--ink); background: var(--wash); font-family: 'DM Sans', sans-serif; }
        h1, h2, h3, .navbar-brand { font-family: 'Space Grotesk', sans-serif; }
        .navbar-laravel { background: var(--paper); border-bottom: 1px solid var(--line); }
        .navbar-brand { color: var(--ink) !important; font-weight: 700; letter-spacing: -.03em; }
        .brand-mark { display: inline-flex; width: 30px; height: 30px; margin-right: 9px; align-items: center; justify-content: center; border-radius: 9px; color: #fff; background: var(--blue); font-size: 14px; }
        .nav-link { color: var(--muted) !important; font-size: 14px; font-weight: 600; }
        .page-shell { min-height: calc(100vh - 57px); padding: 48px 0 64px; background: radial-gradient(circle at 8% 0%, #e8f0ff 0, transparent 33%), var(--wash); }
        .card { border: 1px solid var(--line); border-radius: 14px; box-shadow: 0 12px 35px rgba(31, 55, 86, .06); }
        .form-control { height: 44px; border: 1px solid #d9e0e8; border-radius: 8px; color: var(--ink); }
        .form-control:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(39, 100, 231, .12); }
        .btn-primary { border: 0; border-radius: 8px; background: var(--blue); font-weight: 600; }
        .btn-primary:hover { background: var(--blue-dark); }
        .btn-light { border: 1px solid var(--line); border-radius: 8px; color: var(--ink); font-weight: 600; }
        .invalid-feedback { display: block; font-size: 12px; }
        .alert { border: 0; border-radius: 9px; }
        .alert-success { color: #17633f; background: var(--mint); }
        .table thead th { border-top: 0; border-bottom: 1px solid var(--line); color: var(--muted); font-size: 11px; letter-spacing: .08em; text-transform: uppercase; }
        .table td { border-color: var(--line); vertical-align: middle; }
        .table a { color: var(--blue); }
        .table .url-cell { max-width: 245px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .pagination { margin-bottom: 0; }
        .page-link { border-color: var(--line); color: var(--blue); }
        .page-item.active .page-link { border-color: var(--blue); background: var(--blue); }
        @media (max-width: 767.98px) { .page-shell { padding-top: 28px; } .table-wrap { overflow-x: auto; } .table { min-width: 720px; } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}"><span class="brand-mark">L</span>Task Project</a>
            <div class="ml-auto">
                @guest
                    <a class="nav-link d-inline-block" href="{{ route('login') }}">{{ __('Log in') }}</a>
                    <a class="nav-link d-inline-block" href="{{ route('register') }}">{{ __('Register') }}</a>
                @else
                    <a class="nav-link d-inline-block" href="{{ route('dashboard') }}">{{ __('Create lead') }}</a>
                    <a class="nav-link d-inline-block" href="{{ route('leads.index') }}">{{ __('View leads') }}</a>
                    <a class="nav-link d-inline-block" href="{{ route('logout') }}">{{ __('Log out') }}</a>
                @endguest
            </div>
        </div>
    </nav>
    <main class="page-shell">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Delete this link?',
                        text: 'This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it',
                        cancelButtonText: 'Cancel'
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
