<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IT Help Desk')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar h1 {
            font-size: 1.5rem;
        }

        .navbar-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .navbar-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .navbar-links a:hover {
            color: #3498db;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .card {
            background: white;
            border-radius: 5px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #ecf0f1;
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        input[type="file"] {
            padding: 0.5rem;
        }

        .error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        .badge {
            display: inline-block;
            padding: 0.5rem 0.75rem;
            border-radius: 3px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .badge-open {
            background-color: #3498db;
            color: white;
        }

        .badge-in_progress {
            background-color: #f39c12;
            color: white;
        }

        .badge-resolved {
            background-color: #27ae60;
            color: white;
        }

        .badge-closed {
            background-color: #95a5a6;
            color: white;
        }

        .badge-low {
            background-color: #27ae60;
            color: white;
        }

        .badge-medium {
            background-color: #f39c12;
            color: white;
        }

        .badge-high {
            background-color: #e67e22;
            color: white;
        }

        .badge-urgent {
            background-color: #e74c3c;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #ecf0f1;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination a, .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 3px;
            color: #3498db;
            text-decoration: none;
        }

        .pagination a:hover {
            background-color: #3498db;
            color: white;
        }

        .pagination .active {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }

        .file-upload-box {
            border: 2px dashed #3498db;
            border-radius: 5px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .file-upload-box:hover {
            background-color: #ecf0f1;
        }

        .attachments-list {
            list-style: none;
        }

        .attachments-list li {
            padding: 0.75rem;
            background-color: #ecf0f1;
            margin-bottom: 0.5rem;
            border-radius: 3px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comments {
            margin-top: 2rem;
        }

        .comment {
            background-color: #f9f9f9;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #3498db;
            border-radius: 3px;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .comment-author {
            font-weight: bold;
            color: #2c3e50;
        }

        .breadcrumb {
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .breadcrumb a {
            color: #3498db;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-box {
            background: white;
            padding: 1.5rem;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-box-number {
            font-size: 2rem;
            font-weight: bold;
            color: #3498db;
        }

        .stat-box-label {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }

            .navbar-links {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 0.75rem 0.5rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div>
            <h1>🎫 IT Help Desk</h1>
        </div>
        <div class="navbar-links">
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('tickets.admin-index') }}">Visas biļetes</a>
                    <a href="{{ route('tickets.calendar') }}">Kalendārs</a>
                @else
                    <a href="{{ route('tickets.index') }}">Manas biļetes</a>
                    <a href="{{ route('tickets.create') }}">Jauna biļete</a>
                @endif
                <span style="color: white;">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: white; cursor: pointer; text-decoration: underline;">Izlogoties</button>
                </form>
            @else
                <a href="{{ route('login') }}">Pieslēgties</a>
                <a href="{{ route('register') }}">Reģistrēties</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Kļūda!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
