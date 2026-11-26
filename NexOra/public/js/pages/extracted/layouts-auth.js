
        document.addEventListener('click', function (e) {
            var b = e.target.closest('[data-toggle-password]');
            if (!b) return;
            var input = b.parentElement.querySelector('input');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            b.textContent = show ? 'Hide' : 'Show';
            b.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    