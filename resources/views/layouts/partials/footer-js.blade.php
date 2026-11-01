<!-- Required Js -->

<!-- Define feather as no-op (we use Tabler Icons only) to avoid ReferenceError in script.js -->
<script>
  if (typeof window.feather === 'undefined') {
    window.feather = { replace: function () {} };
  }
</script>
<script>
    // Dummy functions to prevent JS errors after asset cleanup
    window.layout_change = function(){ console.log('layout_change stub'); };
    window.font_change = function(){ console.log('font_change stub'); };
    window.change_box_container = function(){ console.log('change_box_container stub'); };
    window.layout_caption_change = function(){ console.log('layout_caption_change stub'); };
    window.layout_rtl_change = function(){ console.log('layout_rtl_change stub'); };
    window.preset_change = function(){ console.log('preset_change stub'); };
</script>


<script>layout_change('light');</script>
<script>
    // Dummy functions to prevent JS errors after asset cleanup
    window.layout_change = function(){ console.log('layout_change stub'); };
    window.font_change = function(){ console.log('font_change stub'); };
    window.change_box_container = function(){ console.log('change_box_container stub'); };
    window.layout_caption_change = function(){ console.log('layout_caption_change stub'); };
    window.layout_rtl_change = function(){ console.log('layout_rtl_change stub'); };
    window.preset_change = function(){ console.log('preset_change stub'); };
</script>

<!-- Restore logo src after theme.js may overwrite it with relative path -->
<script>
  document.querySelectorAll('.pc-sidebar .m-header .logo-lg, .navbar-brand .logo-lg').forEach(function (el) {
    el.setAttribute('src', '{{ asset('berry-admin-template/dist/assets/images/favicon.svg') }}');
  });
</script>
<script>
    // Dummy functions to prevent JS errors after asset cleanup
    window.layout_change = function(){ console.log('layout_change stub'); };
    window.font_change = function(){ console.log('font_change stub'); };
    window.change_box_container = function(){ console.log('change_box_container stub'); };
    window.layout_caption_change = function(){ console.log('layout_caption_change stub'); };
    window.layout_rtl_change = function(){ console.log('layout_rtl_change stub'); };
    window.preset_change = function(){ console.log('preset_change stub'); };
</script>
<script>font_change('Roboto');</script>
<script>
    // Dummy functions to prevent JS errors after asset cleanup
    window.layout_change = function(){ console.log('layout_change stub'); };
    window.font_change = function(){ console.log('font_change stub'); };
    window.change_box_container = function(){ console.log('change_box_container stub'); };
    window.layout_caption_change = function(){ console.log('layout_caption_change stub'); };
    window.layout_rtl_change = function(){ console.log('layout_rtl_change stub'); };
    window.preset_change = function(){ console.log('preset_change stub'); };
</script>
<script>change_box_container('false');</script>
<script>
    // Dummy functions to prevent JS errors after asset cleanup
    window.layout_change = function(){ console.log('layout_change stub'); };
    window.font_change = function(){ console.log('font_change stub'); };
    window.change_box_container = function(){ console.log('change_box_container stub'); };
    window.layout_caption_change = function(){ console.log('layout_caption_change stub'); };
    window.layout_rtl_change = function(){ console.log('layout_rtl_change stub'); };
    window.preset_change = function(){ console.log('preset_change stub'); };
</script>
<script>layout_caption_change('true');</script>
<script>
    // Dummy functions to prevent JS errors after asset cleanup
    window.layout_change = function(){ console.log('layout_change stub'); };
    window.font_change = function(){ console.log('font_change stub'); };
    window.change_box_container = function(){ console.log('change_box_container stub'); };
    window.layout_caption_change = function(){ console.log('layout_caption_change stub'); };
    window.layout_rtl_change = function(){ console.log('layout_rtl_change stub'); };
    window.preset_change = function(){ console.log('preset_change stub'); };
</script>
<script>layout_rtl_change('false');</script>
<script>
    // Dummy functions to prevent JS errors after asset cleanup
    window.layout_change = function(){ console.log('layout_change stub'); };
    window.font_change = function(){ console.log('font_change stub'); };
    window.change_box_container = function(){ console.log('change_box_container stub'); };
    window.layout_caption_change = function(){ console.log('layout_caption_change stub'); };
    window.layout_rtl_change = function(){ console.log('layout_rtl_change stub'); };
    window.preset_change = function(){ console.log('preset_change stub'); };
</script>
<script>preset_change('preset-1');</script>
<script>
    // Dummy functions to prevent JS errors after asset cleanup
    window.layout_change = function(){ console.log('layout_change stub'); };
    window.font_change = function(){ console.log('font_change stub'); };
    window.change_box_container = function(){ console.log('change_box_container stub'); };
    window.layout_caption_change = function(){ console.log('layout_caption_change stub'); };
    window.layout_rtl_change = function(){ console.log('layout_rtl_change stub'); };
    window.preset_change = function(){ console.log('preset_change stub'); };
</script>

<!-- Sidebar collapse toggle -->
<script>
  (function () {
    var sidebar = document.getElementById('main-sidebar');
    var btn = document.getElementById('sidebar-hide');
    var maincontent = document.querySelector('.pc-maincontent');

    if (!sidebar || !btn) return;

    btn.addEventListener('click', function (e) {
      e.preventDefault();
      var isCollapsed = sidebar.classList.contains('collapsed');

      if (isCollapsed) {
        sidebar.classList.remove('collapsed');
        if (maincontent) maincontent.classList.remove('sidebar-collapsed');
      } else {
        sidebar.classList.add('collapsed');
        if (maincontent) maincontent.classList.add('sidebar-collapsed');
      }
    });
  })();
</script>
<script>
    // Dummy functions to prevent JS errors after asset cleanup
    window.layout_change = function(){ console.log('layout_change stub'); };
    window.font_change = function(){ console.log('font_change stub'); };
    window.change_box_container = function(){ console.log('change_box_container stub'); };
    window.layout_caption_change = function(){ console.log('layout_caption_change stub'); };
    window.layout_rtl_change = function(){ console.log('layout_rtl_change stub'); };
    window.preset_change = function(){ console.log('preset_change stub'); };
</script>

@stack('scripts')
