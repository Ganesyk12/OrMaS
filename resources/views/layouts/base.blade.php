<!doctype html>
<html lang="id">

<head>
  <title>{{ $title ?? 'Pages' }} | {{ config('app.name') }}</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Sistem Administrasi Karang Taruna - Modular Monolith Laravel" />
  <meta name="keywords" content="Karang Taruna, Administrasi, Laravel, Modular Monolith" />
  <meta name="author" content="Cameleon Team" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="icon" href="{{ asset('images/logo.webp') }}" type="image/webp" />

  @include('layouts.partials.head-css')
</head>

<body class="pc-default">
  <div class="main-body d-flex flex-column min-vh-100">
    <!-- [ Header Topbar ] start -->
    @include('layouts.partials.header')
    <div class="pc-menu-overlay" id="mobile-sidebar-overlay"></div>
    <div class="pc-wrapper d-flex flex-grow-1">
      @include('layouts.partials.sidebar')

      <!-- [ Main Content ] start -->
      <div class="pc-maincontent flex-grow-1">
        <div class="page-header">
          <div class="row align-items-center">
            <div class="col-sm-12">
              <h3 class="page-title">{{ $pageTitle ?? 'Dashboard' }}</h3>
              <ol class="breadcrumb">
                @php $__breadcrumbLast = count($breadcrumbs ?? []); $__breadcrumbCount = 0; @endphp
                @foreach($breadcrumbs ?? [] as $name => $url)
                @php $__breadcrumbCount++; @endphp
                <li class="breadcrumb-item{{ $__breadcrumbCount === $__breadcrumbLast ? ' active' : '' }}"><a href="{{ $url }}">{{ $name }}</a></li>
                @endforeach
              </ol>
            </div>
          </div>
        </div>

        <!-- Flash messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
          <div class="alert-body"><b>Berhasil:</b> {{ session('success') }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible show fade">
          <div class="alert-body"><b>Error:</b> {{ session('error') }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
      </div>
      <!-- [ Main Content ] end -->
    </div>

    @include('layouts.partials.footer')
  </div>

  @include('layouts.partials.footer-js')
</body>

</html>