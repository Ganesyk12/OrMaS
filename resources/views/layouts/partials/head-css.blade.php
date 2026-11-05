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
        /* Beri ruang untuk header yang fixed agar breadcrumb tidak tertutup navbar */
        padding: 100px 20px 20px;
        /* Default: sidebar expanded (260px), konten mulai setelah sidebar */
        margin-left: 260px;
        transition: margin-left 0.25s ease, width 0.25s ease;
    }
    .pc-maincontent.sidebar-collapsed {
        margin-left: 88px;
    }

    /* Header ikut bergeser saat sidebar di-collapse */
    .pc-header {
        transition: left 0.25s ease;
    }
    body.sidebar-is-collapsed .pc-header {
        left: 88px;
    }

    /* Card: beri border tipis agar batas kartu tetap terlihat */
    .card {
        --bs-card-border-width: 1px;
        --bs-card-border-color: #e2e8f0;
        --bs-card-inner-border-radius: calc(var(--bs-card-border-radius) - 1px);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
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
    /* Submenu Sidebar Dark Mode */
    .sidebar-dark .pc-navbar .pc-submenu:after {
        background-color: #4b5563 !important;
    }
    .sidebar-dark .pc-navbar .pc-submenu .pc-item > .pc-link {
        color: #cbd5e1 !important;
    }
    .sidebar-dark .pc-navbar .pc-submenu .pc-item > .pc-link:after {
        background-color: #6b7280 !important;
    }
    .sidebar-dark .pc-navbar .pc-submenu .pc-item > .pc-link:hover,
    .sidebar-dark .pc-navbar .pc-submenu .pc-item.pc-active > .pc-link {
        color: #ffffff !important;
    }
    .sidebar-dark .pc-navbar .pc-submenu .pc-item.pc-active > .pc-link {
        font-weight: 600;
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

    /* Brand sidebar: logo + nama aplikasi saat expand, hanya logo saat collapse */
    .pc-sidebar .m-header .b-brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        max-width: 100%;
        text-decoration: none;
    }
    .pc-sidebar .m-header .b-brand .brand-logo {
        flex-shrink: 0;
        height: 40px;
        width: auto;
    }
    .pc-sidebar .m-header .b-brand .brand-name {
        overflow: hidden;
        color: #ffffff;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .pc-sidebar.collapsed .m-header {
        width: 88px;
        justify-content: center;
        padding-left: 0;
        padding-right: 0;
    }
    .pc-sidebar.collapsed .m-header .b-brand {
        max-width: 40px;
    }
    .pc-sidebar.collapsed .m-header .b-brand .brand-name {
        display: none !important;
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

    /* Saat sidebar collapse, submenu disembunyikan (klik menu induk akan membuka sidebar) */
    .pc-sidebar.collapsed .pc-submenu {
        display: none !important;
    }

    /* Overlay sidebar mobile: default disembunyikan agar desktop tidak ikut blur */
    .pc-menu-overlay {
        display: none;
    }
    .pc-menu-overlay.show {
        display: block;
    }

    /* ===== Responsive Mobile ===== */
    @media (max-width: 1024px) {
        .pc-maincontent,
        .pc-maincontent.sidebar-collapsed {
            width: 100%;
            margin-left: 0 !important;
            padding: 92px 14px 18px;
        }

        .pc-header,
        body.sidebar-is-collapsed .pc-header {
            left: 0 !important;
        }

        .pc-sidebar {
            width: 260px !important;
            min-width: 260px !important;
            max-width: 86vw;
            z-index: 1030;
            overflow-y: auto;
        }

        .pc-sidebar .navbar-wrapper {
            width: 260px;
        }

        .pc-menu-overlay {
            z-index: 1026;
        }

        body.mobile-sidebar-open {
            overflow: hidden;
        }
    }

    @media (max-width: 767.98px) {
        .pc-maincontent {
            padding: 88px 10px 16px;
        }

        .page-header {
            padding: 12px 16px;
            margin-bottom: 14px;
        }

        .page-header .page-title {
            font-size: 1.15rem;
        }

        .card-header {
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-header > div:first-child {
            min-width: 0;
        }

        .card-header form.d-flex {
            flex-wrap: wrap;
            width: 100%;
            margin-top: 4px;
        }

        .card-header form.d-flex .form-control,
        .card-header form.d-flex .form-select {
            width: 100% !important;
            min-width: 0;
        }

        .card-header form.d-flex .ms-2 {
            margin-left: 0 !important;
        }

        .container-fluid {
            padding-left: 4px;
            padding-right: 4px;
        }
    }

    /* Tombol profil di navbar: pastikan ikon tetap tampil di layar kecil.
       Template bawaan menyembunyikan <span> ikon pada max-width 575.98px,
       sehingga tombol hanya tampil sebagai lingkaran kosong. */
    @media (max-width: 575.98px) {
        .pc-header .header-user-profile .pc-head-link {
            width: 48px;
            height: 48px;
        }

        .pc-header .header-user-profile .pc-head-link > span {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
        }

        .pc-header .header-user-profile .pc-head-link > span > i {
            display: inline-block !important;
            margin-right: 0;
            font-size: 22px;
            color: #2196f3;
        }
    }
</style>
