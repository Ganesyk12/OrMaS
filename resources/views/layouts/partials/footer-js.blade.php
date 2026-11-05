<!-- Required Js -->

<!-- SweetAlert2 (konfirmasi global, mis. tombol hapus) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Define feather as no-op (we use Tabler Icons only) to avoid ReferenceError in script.js -->
<script>
  if (typeof window.feather === 'undefined') {
    window.feather = {
      replace: function() {}
    };
  }
  document.querySelectorAll('.pc-sidebar .m-header .logo-lg, .navbar-brand .logo-lg').forEach(function(el) {
    el.setAttribute('src', '{{ asset('berry-admin-template/dist/assets/images/favicon.svg') }}');
  });

  (function() {
    var dropdownToggles = document.querySelectorAll('[data-bs-toggle="dropdown"]');

    function closeAllDropdowns() {
      document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
        menu.classList.remove('show');
      });
      document.querySelectorAll('[data-bs-toggle="dropdown"][aria-expanded="true"]').forEach(function(toggle) {
        toggle.setAttribute('aria-expanded', 'false');
      });
    }

    dropdownToggles.forEach(function(toggle) {
      toggle.addEventListener('click', function(event) {
        event.preventDefault();
        event.stopPropagation();

        var parent = toggle.closest('.dropdown');
        var menu = parent ? parent.querySelector('.dropdown-menu') : null;
        if (!menu) return;

        var willOpen = !menu.classList.contains('show');
        closeAllDropdowns();

        if (willOpen) {
          menu.classList.add('show');
          toggle.setAttribute('aria-expanded', 'true');
        }
      });
    });

    document.addEventListener('click', function(event) {
      if (event.target.closest('.dropdown-menu')) return;
      closeAllDropdowns();
    });

    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') closeAllDropdowns();
    });
  })();

  (function() {
    var sidebar = document.getElementById('main-sidebar');
    var btn = document.getElementById('sidebar-hide');
    var maincontent = document.querySelector('.pc-maincontent');

    if (!sidebar || !btn) return;

    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var isCollapsed = sidebar.classList.contains('collapsed');

      if (isCollapsed) {
        sidebar.classList.remove('collapsed');
        if (maincontent) maincontent.classList.remove('sidebar-collapsed');
        document.body.classList.remove('sidebar-is-collapsed');
      } else {
        sidebar.classList.add('collapsed');
        if (maincontent) maincontent.classList.add('sidebar-collapsed');
        document.body.classList.add('sidebar-is-collapsed');
      }
    });
  })();

  // Sidebar mobile (off-canvas)
  (function() {
    var sidebar = document.getElementById('main-sidebar');
    var btn = document.getElementById('mobile-collapse');
    var overlay = document.getElementById('mobile-sidebar-overlay');
    var maincontent = document.querySelector('.pc-maincontent');

    if (!sidebar || !btn) return;

    function isMobile() {
      return window.matchMedia('(max-width: 1024px)').matches;
    }

    function resetCollapsedState() {
      sidebar.classList.remove('collapsed');
      if (maincontent) maincontent.classList.remove('sidebar-collapsed');
      document.body.classList.remove('sidebar-is-collapsed');
    }

    function closeSidebar() {
      sidebar.classList.remove('mob-sidebar-active');
      if (overlay) overlay.classList.remove('show');
      document.body.classList.remove('mobile-sidebar-open');
    }

    function openSidebar() {
      resetCollapsedState();
      sidebar.classList.add('mob-sidebar-active');
      if (overlay) overlay.classList.add('show');
      document.body.classList.add('mobile-sidebar-open');
    }

    btn.addEventListener('click', function(e) {
      e.preventDefault();
      if (sidebar.classList.contains('mob-sidebar-active')) {
        closeSidebar();
      } else {
        openSidebar();
      }
    });

    if (overlay) {
      overlay.addEventListener('click', closeSidebar);
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeSidebar();
    });

    window.addEventListener('resize', function() {
      if (!isMobile()) {
        closeSidebar();
      } else {
        resetCollapsedState();
      }
    });

    if (isMobile()) {
      resetCollapsedState();
    }
  })();

  (function() {
    var sidebar = document.getElementById('main-sidebar');
    if (!sidebar) return;

    var parentItems = sidebar.querySelectorAll('.pc-navbar .pc-hasmenu');

    function setExpanded(item, expanded) {
      item.classList.toggle('pc-trigger', expanded);
      var link = item.querySelector(':scope > .pc-link');
      if (link) link.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    }

    parentItems.forEach(function(item) {
      var link = item.querySelector(':scope > .pc-link');
      if (!link) return;

      link.addEventListener('click', function(event) {
        event.preventDefault();
        event.stopPropagation();

        // Saat sidebar collapse, buka sidebar terlebih dahulu agar submenu terlihat
        var wasCollapsed = sidebar.classList.contains('collapsed');
        if (wasCollapsed) {
          sidebar.classList.remove('collapsed');
          var maincontent = document.querySelector('.pc-maincontent');
          if (maincontent) maincontent.classList.remove('sidebar-collapsed');
          document.body.classList.remove('sidebar-is-collapsed');
        }

        // Jika baru dibuka dari kondisi collapse, pastikan submenu ikut terbuka
        var willOpen = wasCollapsed ? true : !item.classList.contains('pc-trigger');

        // Accordion: tutup submenu lain pada level yang sama
        var list = item.parentElement;
        if (list) {
          Array.prototype.forEach.call(list.children, function(sibling) {
            if (sibling !== item && sibling.classList && sibling.classList.contains('pc-hasmenu')) {
              setExpanded(sibling, false);
            }
          });
        }

        setExpanded(item, willOpen);
      });
    });
  })();

  // Konfirmasi hapus global dengan SweetAlert2.
  // Cukup tambahkan atribut data-confirm pada <form> untuk mengaktifkannya.
  (function() {
    var forms = document.querySelectorAll('form[data-confirm]');
    if (!forms.length) return;

    forms.forEach(function(form) {
      form.addEventListener('submit', function(event) {
        // Lanjutkan submit jika konfirmasi sudah disetujui.
        if (form.dataset.confirmed === '1') return;

        event.preventDefault();

        var message = form.dataset.confirm || 'Yakin ingin menghapus data ini?';

        if (typeof Swal === 'undefined') {
          if (window.confirm(message)) {
            form.dataset.confirmed = '1';
            form.submit();
          }
          return;
        }

        Swal.fire({
          title: 'Konfirmasi Hapus',
          text: message,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus',
          cancelButtonText: 'Batal',
          reverseButtons: true,
          focusCancel: true
        }).then(function(result) {
          if (result.isConfirmed) {
            form.dataset.confirmed = '1';
            form.submit();
          }
        });
      });
    });
  })();

  // Toggle show/hide password untuk input dengan tombol data-toggle-password.
  (function() {
    document.querySelectorAll('[data-toggle-password]').forEach(function(toggle) {
      var target = document.getElementById(toggle.getAttribute('data-toggle-password'));
      if (!target) return;

      toggle.addEventListener('click', function() {
        var isHidden = target.type === 'password';
        target.type = isHidden ? 'text' : 'password';

        var icon = toggle.querySelector('i');
        if (icon) {
          icon.classList.toggle('ti-eye', !isHidden);
          icon.classList.toggle('ti-eye-off', isHidden);
        }

        var label = isHidden ? 'Sembunyikan password' : 'Tampilkan password';
        toggle.setAttribute('aria-label', label);
        toggle.setAttribute('title', label);
      });
    });
  })();
</script>
@stack('scripts')