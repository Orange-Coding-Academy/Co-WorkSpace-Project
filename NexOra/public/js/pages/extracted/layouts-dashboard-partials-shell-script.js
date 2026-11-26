
    (function () {
        var shell = document.getElementById('nxDashboard');
        var toggle = document.getElementById('sidebarToggle');
        if (!shell || !toggle) return;
        var mobile = window.matchMedia('(max-width: 767px)');

        function sync() {
            var collapsed = shell.classList.contains(mobile.matches ? 'is-nav-closed-mobile' : 'is-nav-collapsed');
            toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        }

        shell.classList.add('is-nav-closed-mobile');
        toggle.addEventListener('click', function () {
            shell.classList.toggle(mobile.matches ? 'is-nav-closed-mobile' : 'is-nav-collapsed');
            sync();
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && mobile.matches && !shell.classList.contains('is-nav-closed-mobile')) {
                shell.classList.add('is-nav-closed-mobile');
                sync();
                toggle.focus();
            }
        });
        if (mobile.addEventListener) mobile.addEventListener('change', sync);
        sync();
    })();
