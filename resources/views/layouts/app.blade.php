<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Finance - Uang Masuk</title>
    <style>
        /* CSS Dasar & Reset */
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-color: #f3f4f6;
            --text-main: #1f2937;
            --border-color: #e5e7eb;
            --card-bg: #ffffff;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* Layout & Centering */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Header & Typography */
        h2, h3 {
            margin-top: 0;
            color: #111827;
            font-weight: 600;
        }

        /* Form & Input Elements */
        .form-group {
            margin-bottom: 1rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 0.875rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Flexbox Filter Section */
        .filter-section form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        
        .filter-section .form-control {
            width: auto;
            min-width: 200px;
        }

        /* Button Styling */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.2s;
        }

        .btn-primary { background-color: var(--primary-color); color: white; }
        .btn-primary:hover { background-color: var(--primary-hover); }
        .btn-secondary { background-color: #6b7280; color: white; }
        .btn-success { background-color: #10b981; color: white; }
        .btn-danger { background-color: #ef4444; color: white; }

        /* Responsive Table */
        .table-responsive {
            overflow-x: auto;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        th, td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.875rem;
        }

        th {
            background-color: #f9fafb;
            font-weight: 600;
        }

        tr:last-child td { border-bottom: none; }
        
        /* Badges */
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .bg-success { background-color: #d1fae5; color: #065f46; }
        .bg-warning { background-color: #fef3c7; color: #92400e; }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .filter-section form { flex-direction: column; align-items: stretch; }
            .filter-section .form-control { width: 100%; }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Tempat konten dinamis akan disuntikkan -->
        @yield('content')
    </div>

</body>
</html>