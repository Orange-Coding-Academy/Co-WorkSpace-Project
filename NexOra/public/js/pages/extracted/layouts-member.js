
        (function() {
            var burger = document.getElementById('nxBurger');
            var panel = document.getElementById('nxMobile');

            function setMenu(open) {
                burger.setAttribute('aria-expanded', String(open));
                burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
                panel.classList.toggle('is-open', open);
            }
            burger.addEventListener('click', function() {
                setMenu(burger.getAttribute('aria-expanded') !== 'true');
            });
            window.matchMedia('(min-width: 1024px)').addEventListener('change', function(m) {
                if (m.matches) setMenu(false);
            });

            // Account dropdown: close on outside click / Escape
            document.addEventListener('click', function(e) {
                document.querySelectorAll('.nx-account[open]').forEach(function(d) {
                    if (!d.contains(e.target)) d.removeAttribute('open');
                });
            });
            document.addEventListener('keydown', function(e) {
                if (e.key !== 'Escape') return;
                document.querySelectorAll('.nx-account[open]').forEach(function(d) {
                    d.removeAttribute('open');
                });
                setMenu(false);
            });
        })();
    