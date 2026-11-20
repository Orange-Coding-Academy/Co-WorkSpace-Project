(function () {
    var root = document.getElementById("nxDashboard");
    var toggle = document.getElementById("sidebarToggle");
    var overlay = document.getElementById("sidebarOverlay");
    if (!root || !toggle) return;

    var KEY = "nx-sidebar-collapsed";
    var mq = window.matchMedia("(min-width: 1024px)");

    function safeGet() {
        try {
            return localStorage.getItem(KEY) === "1";
        } catch (e) {
            return false;
        }
    }
    function safeSet(v) {
        try {
            localStorage.setItem(KEY, v ? "1" : "0");
        } catch (e) {}
    }

    function setOpen(open) {
        root.classList.toggle("is-sidebar-open", open);
        toggle.setAttribute("aria-expanded", String(open));
    }

    function applyMode() {
        if (mq.matches) {
            setOpen(false);
            root.classList.toggle("is-collapsed", safeGet());
            toggle.setAttribute(
                "aria-expanded",
                String(!root.classList.contains("is-collapsed")),
            );
        } else {
            root.classList.remove("is-collapsed");
        }
    }

    toggle.addEventListener("click", function () {
        if (mq.matches) {
            var collapsed = !root.classList.contains("is-collapsed");
            root.classList.toggle("is-collapsed", collapsed);
            safeSet(collapsed);
            toggle.setAttribute("aria-expanded", String(!collapsed));
        } else {
            setOpen(!root.classList.contains("is-sidebar-open"));
        }
    });

    if (overlay)
        overlay.addEventListener("click", function () {
            setOpen(false);
        });
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") setOpen(false);
    });
    mq.addEventListener
        ? mq.addEventListener("change", applyMode)
        : mq.addListener(applyMode);

    applyMode();
    if (window.feather) window.feather.replace();
})();
