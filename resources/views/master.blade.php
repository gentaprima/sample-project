<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Metinca</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- CSS Select2 -->
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script> -->
    <!-- jQuery & Select2 JS -->
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }

        aside {
            background-color: #141c2b;
            color: white;
            padding: 1rem;
            transition: width 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        aside.expanded {
            width: 500px;
        }

        aside.collapsed {
            width: 250px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .hamburger {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
        }

        .menu-card {
            background-color: #1c263b;
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s;
        }

        .menu-card:hover {
            background-color: #ffc107;
            color: #000;
        }

        .menu-card.active {
            background-color: #ffc107;
            color: #000;
        }

        .menu-card a {
            color: #fff;
            text-decoration: none;
        }



        .menu-card i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .menu-card span {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .vertical-menu {
            display: none;
            flex-direction: column;
            gap: 0.75rem;
        }

        .vertical-menu a {
            color: white;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .vertical-menu a:hover {
            background-color: #ffc107;
            color: #000;
        }
        .vertical-menu a.active {
            background-color: #ffc107;
            color: #000;
        }

        aside.collapsed .menu-grid {
            display: none;
        }

        aside.collapsed .vertical-menu {
            display: flex;
        }

        main {
            flex: 1;
            background-color: #f5f5f5;
            padding: 2rem;
        }

        .card-placeholder {
            background-color: #1c263b;
            padding: 1.5rem;
            border-radius: 12px;
            color: white;
            text-align: center;
        }

        .card-custom {
            background-color: #1c263b;
            padding: 1.5rem;
            border-radius: 12px;
            color: white;
        }

        .card-custom h3 {
            font-size: 1rem;
            color: #ffc107;
            margin-bottom: 0.5rem;
        }

        .card-custom p {
            font-size: 2rem;
            font-weight: bold;
        }

        .chart-placeholder {
            background: #fff;
            border: 2px dashed #ccc;
            height: 300px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 1.2rem;
        }

        .small-text {
            font-weight: 100;
            font-style: italic;
        }

        .table-section {
            margin-top: 2rem;
        }

        .table-section h4 {
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .table-search {
            margin-bottom: 1rem;
        }

        .table-search input {
            padding: 0.5rem 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 100%;
            max-width: 300px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background-color: #1c263b;
            color: white;
        }

        th,
        td {
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            /* background-color: #f1f1f1; */
        }

        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
        }

        .search-box {
            position: relative;
            width: 250px;
        }

        .search-box input {
            width: 100%;
            padding: 0.5rem 0.5rem 0.5rem 2rem;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .search-box svg {
            position: absolute;
            top: 50%;
            left: 8px;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            fill: #888;
        }

        .table-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .add-button {
            padding: 0.5rem 1rem;
            background-color: #ffc107;
            border: none;
            border-radius: 5px;
            color: #000;
            font-weight: 600;
            cursor: pointer;
        }

        .primary-button {
            padding: 0.5rem 1rem;
            background-color: #141c2b;
            border: none;
            border-radius: 5px;
            color: #000;
            font-weight: 600;
            cursor: pointer;
            color: #fff;

        }

        .primary-button.btn-sm {
            padding: 0.25rem 0.5rem !important;
        }

        .add-button.btn-sm {
            padding: 6px 10px !important;
        }

        .remove-process-btn,
        .remove-subprocess-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 10;
            padding: 6px 7px;
            font-size: 12px;
            line-height: 1;
        }

        .group-process {
            position: relative;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fa;
        }

        .form-floating .remove-subprocess-btn {
            top: 50%;
            transform: translateY(-50%);
            right: 10px;
        }

        .form-floating {
            position: relative;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 58px;
            /* Ubah sesuai kebutuhan */
            padding: 6px 8px;
            border-radius: 0.375rem;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin-top: 21px;
            ;
        }

        .colored-toast.swal2-icon-success {
            background-color: #a5dc86 !important;
        }

        .colored-toast.swal2-icon-error {
            background-color: #f27474 !important;
        }

        .colored-toast.swal2-icon-warning {
            background-color: #f8bb86 !important;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <aside class="expanded" id="sidebar">
        <div class="top-bar d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center text-white">
                <i class="bi bi-person fs-4 me-2"></i>
                <span class="fw-semibold">Genta Prima Syahnur</span><br>
            </div>
            <button class="hamburger btn btn-sm text-white" onclick="toggleSidebar()">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>

        <h4 class="fw-bold" id="name-menu">Hi, Genta Prima Syahnur</h4>
        <span class="fw-lighter" id="menu-desc">Welcome back to your work, good luck for today.</span>


        <!-- Default Grid Menu -->
        <div class="menu-grid mt-4">
            <div class="menu-card {{ Request::is('dashboard') ? 'active' : '' }}"> <a href="/dashboard"> <i class="bi bi-speedometer2"></i><span>Dashboard</span> </a></div>
            <div class="menu-card {{ Request::is('wax-room') || Request::is('add-wax-room') ? 'active' : '' }}"><a href="/wax-room"><i class="bi bi-speedometer2"></i><span>Wax Room</span></a></div>
            <div class="menu-card"><i class="bi bi-gear-fill"></i><span>Mould Room</span></div>
            <div class="menu-card"><i class="bi bi-list-check"></i><span>Melting</span></div>
            <div class="menu-card"><i class="bi bi-box-seam"></i><span>Cut Off</span></div>
            <div class="menu-card"><i class="bi bi-archive-fill"></i><span>Finishing</span></div>
            <div class="menu-card"><i class="bi bi-question-circle-fill"></i><span>Straightening</span></div>
            <div class="menu-card"><i class="bi bi-box-arrow-right"></i><span>Machining</span></div>
            <div class="menu-card"><i class="bi bi-person-badge-fill"></i><span>Quality</span></div>
            <div class="menu-card"><i class="bi bi-sliders"></i><span>Warehouse</span></div>
            <div class="menu-card {{ Request::is('material') ? 'active' : '' }}"><a href="/material" class=""><i class="bi bi-info-circle"></i><span>Master Data</span></a></div>
            <div class="menu-card"><i class="bi bi-box-arrow-left"></i><span>Sign Out</span></div>
        </div>

        <!-- Collapsed Vertical Menu -->
        <div class="vertical-menu">
            <a class="{{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard">Dashboard</a>
            <a class="{{ Request::is('wax-room') || Request::is('add-wax-room') ? 'active' : '' }}" href="/wax-room">Wax Room</a>
            <a href="#">Mould Room</a>
            <a href="#">Melting</a>
            <a href="#">Cut Off</a>
            <a href="#">Finishing</a>
            <a href="#">Straightening</a>
            <a href="#">Machining</a>
            <a href="#">Quality</a>
            <a href="#">Warehouse</a>
            <a class="{{ Request::is('material') ? 'active' : '' }}" href="/material">Master Data</a>
            <a href="#">Sign Out</a>
        </div>
    </aside>

    <main>
        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("expanded");
            sidebar.classList.toggle("collapsed");
            if (sidebar.className == 'collapsed') {
                document.getElementById("name-menu").hidden = true
                document.getElementById("menu-desc").hidden = true
            } else {
                document.getElementById("name-menu").hidden = false
                document.getElementById("menu-desc").hidden = false
            }

        }
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            iconColor: 'black',
            customClass: {
                popup: 'colored-toast'
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        })
    </script>
</body>

</html>