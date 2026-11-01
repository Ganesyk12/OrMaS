<!-- [Google Font] Family -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" id="main-font-link" />
<!-- [Tabler Icons] -->
<link rel="stylesheet" href="{{ asset('berry-admin-template/dist/assets/fonts/tabler-icons.min.css') }}" />
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="{{ asset('berry-admin-template/dist/assets/css/style.css') }}" id="main-style-link" />
<link rel="stylesheet" href="{{ asset('berry-admin-template/dist/assets/css/style-preset.css') }}" />

@stack('styles')
@stack('custom-styles')

<style>
    /* Layout dasar: header di atas, wrapper (sidebar + konten) di bawah */
    .main-body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    .pc-wrapper {
        display: flex;
        flex: 1;
        min-height: 0;
        overflow: hidden;
    }
    .pc-sidebar {
        flex-shrink: 0;
        width: 260px;
        min-width: 260px;
        transition: width 0.25s ease;
        overflow: hidden;
    }
    .pc-sidebar.collapsed {
        width: 88px !important;
        min-width: 88px !important;
    }
    .pc-maincontent {
        flex: 1;
        min-width: 0;
        overflow-y: auto;
        overflow-x: hidden;
        /* Default: sidebar expanded (260px), konten mulai setelah sidebar */
        margin-left: 260px;
        transition: margin-left 0.25s ease, width 0.25s ease;
    }
    .pc-maincontent.sidebar-collapsed {
        margin-left: 88px;
    }

    /* Footer tetap di bottom viewport ketika konten pendek */
    .pc-footer {
        margin-top: auto;
    }

    /* Sidebar Dark Mode */
    .sidebar-dark {
        background-color: #1f2937 !important;
    }
    .sidebar-dark .pc-navbar > li > a.pc-link,
    .sidebar-dark .pc-navbar > li.pc-caption label,
    .sidebar-dark .pc-navbar > li > a.pc-link .pc-micon i,
    .sidebar-dark .pc-navbar > li > a.pc-link .pc-mtext {
        color: #e5e7eb !important;
    }
    .sidebar-dark .pc-navbar > li.pc-active > a.pc-link,
    .sidebar-dark .pc-navbar > li > a.pc-link:hover {
        background-color: #374151 !important;
        color: #ffffff !important;
    }
    .sidebar-dark .pc-navbar > li.pc-active > a.pc-link .pc-micon i,
    .sidebar-dark .pc-navbar > li > a.pc-link:hover .pc-micon i {
        color: #ffffff !important;
    }
    .sidebar-dark .pc-navbar-card {
        background-color: #374151 !important;
    }
    .sidebar-dark .pc-navbar-card h4.text-white,
    .sidebar-dark .pc-navbar-card p.text-white {
        color: #ffffff !important;
    }
    .sidebar-dark .b-brand {
        color: #ffffff !important;
    }

    /* Saat sidebar collapse, sembunyikan teks menu */
    .pc-sidebar.collapsed .pc-mtext,
    .pc-sidebar.collapsed .pc-arrow,
    .pc-sidebar.collapsed .pc-caption label,
    .pc-sidebar.collapsed .pc-caption i {
        display: none !important;
    }
    .pc-sidebar.collapsed .pc-link .pc-micon {
        justify-content: center;
    }
</style>
