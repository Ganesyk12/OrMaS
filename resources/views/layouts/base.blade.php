<!doctype html>
<html lang="id">
<!-- [Head] start -->

<head>
  @include('layouts.partials.head-meta')
  @include('layouts.partials.head-css')
</head>
<!-- [Head] end -->

<!-- [Body] Start -->

<body class="pc-default">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <div class="main-body d-flex flex-column min-vh-100">
    <!-- [ Header Topbar ] start -->
    @include('layouts.partials.header')
    <!-- [ Header ] end -->

    <div class="pc-wrapper d-flex flex-grow-1">
      @include('layouts.partials.sidebar')
      <!-- [ Sidebar Menu ] start -->
      <!-- [ Sidebar Menu ] end -->

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