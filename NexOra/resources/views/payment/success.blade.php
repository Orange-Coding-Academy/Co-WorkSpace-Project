<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* ===== NexOra visual skin (tokens mirror welcome.blade.php) ===== */
        :root {
            --nx-primary: #315C4D; --nx-primary-hover: #274B3F; --nx-primary-active: #203D34;
            --nx-secondary: #6F806E; --nx-accent: #C58A62; --nx-accent-soft: #E6C9B3; --nx-deep: #152A23; --nx-sand: #EEE7DE;
            --nx-bg: #F5F7F3; --nx-surface: #F8FAF7; --nx-card: #FFFFFF; --nx-sidebar: #203D34;
            --nx-text: #1D2924; --nx-text-secondary: #53615A; --nx-text-muted: #7F8B84;
            --nx-border: #E2E8E2; --nx-divider: #E9EEE9; --nx-border-strong: #C9D4CC;
            --nx-font-heading: 'Manrope', 'Noto Sans Arabic', system-ui, sans-serif;
            --nx-font-body: 'Inter', 'Noto Sans Arabic', system-ui, sans-serif;
            --nx-ease: cubic-bezier(.2, .7, .2, 1);
            --bs-body-font-family: var(--nx-font-body);
            --bs-body-color: #1D2924; --bs-body-color-rgb: 29, 41, 36; --bs-body-bg: #F5F7F3; --bs-body-bg-rgb: 245, 247, 243;
            --bs-primary: #315C4D; --bs-primary-rgb: 49, 92, 77; --bs-secondary: #6F806E; --bs-secondary-rgb: 111, 128, 110;
            --bs-info: #315C4D; --bs-info-rgb: 49, 92, 77; --bs-light: #F8FAF7; --bs-light-rgb: 248, 250, 247; --bs-dark: #1D2924; --bs-dark-rgb: 29, 41, 36;
            --bs-secondary-color: #53615A; --bs-secondary-bg: #EEF2ED; --bs-tertiary-bg: #F8FAF7;
            --bs-link-color: #315C4D; --bs-link-color-rgb: 49, 92, 77; --bs-link-hover-color: #203D34; --bs-link-hover-color-rgb: 32, 61, 52;
            --bs-border-color: #E2E8E2; --bs-border-radius: 12px; --bs-border-radius-sm: 8px; --bs-border-radius-lg: 16px; --bs-border-radius-xl: 24px;
            --bs-focus-ring-color: rgba(49, 92, 77, .22);
        }
        :where(body) { background-color: var(--nx-bg); color: var(--nx-text); }
        body { font-family: var(--nx-font-body); -webkit-font-smoothing: antialiased; overflow-wrap: break-word; }
        :where(h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .navbar-brand, .card-title, .modal-title) { font-family: var(--nx-font-heading); letter-spacing: -0.02em; color: inherit; }
        :where(a) { color: var(--nx-primary); }
        :where(a:hover) { color: var(--nx-primary-active); }
        :focus-visible { outline: 2px solid var(--nx-accent); outline-offset: 2px; }
        .text-muted, .text-secondary { color: var(--nx-text-secondary) !important; }
        .bg-light { background-color: var(--nx-surface) !important; }
        .bg-white { background-color: var(--nx-card) !important; }
        .bg-dark { background-color: var(--nx-sidebar) !important; }
        .shadow-sm { box-shadow: 0 1px 2px rgba(29, 41, 36, .05) !important; }
        .shadow { box-shadow: 0 4px 12px rgba(29, 41, 36, .07) !important; }
        .shadow-lg { box-shadow: 0 12px 32px rgba(29, 41, 36, .12) !important; }

        /* Navbar */
        .navbar { min-height: 72px; border-bottom: 1px solid var(--nx-border); }
        .navbar .navbar-brand { font-weight: 800; font-size: 21px; letter-spacing: -.03em; color: var(--nx-text); }
        .navbar .nav-link { font-size: 14px; font-weight: 500; color: var(--nx-text-secondary); min-height: 44px; display: flex; align-items: center; }
        .navbar .nav-link:hover, .navbar .nav-link.active { color: var(--nx-text); }
        .navbar-toggler { border-color: var(--nx-border-strong); border-radius: 999px; min-height: 44px; }
        .navbar-toggler:focus { box-shadow: 0 0 0 3px rgba(49, 92, 77, .22); }
        .dropdown-menu { border: 1px solid var(--nx-border); border-radius: 12px; padding: 6px; box-shadow: 0 4px 12px rgba(29, 41, 36, .07); font-size: 14px; }
        .dropdown-item { border-radius: 8px; min-height: 40px; display: flex; align-items: center; color: var(--nx-text); }
        .dropdown-item:hover, .dropdown-item:focus { background: var(--nx-surface); color: var(--nx-text); }
        .dropdown-item.active, .dropdown-item:active { background: var(--nx-primary); color: #fff; }

        /* Buttons — landing pill language, 44px professional controls */
        .btn {
            --bs-btn-padding-x: 20px; --bs-btn-padding-y: 9px; --bs-btn-font-size: 14px; --bs-btn-font-weight: 700;
            --bs-btn-border-radius: 999px; --bs-btn-focus-box-shadow: 0 0 0 3px rgba(49, 92, 77, .22);
            min-height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            font-family: var(--nx-font-heading); letter-spacing: -.005em;
            transition: background-color .3s var(--nx-ease), color .3s var(--nx-ease), border-color .3s var(--nx-ease), transform .3s var(--nx-ease);
        }
        .btn:active { transform: scale(.98); }
        .btn-sm { --bs-btn-padding-x: 14px; --bs-btn-padding-y: 6px; --bs-btn-font-size: 13px; min-height: 36px; }
        .btn-lg { --bs-btn-padding-x: 24px; --bs-btn-font-size: 15px; min-height: 52px; }
        .btn-close { min-height: 0; border-radius: 8px; }
        .btn-link { min-height: auto; }
        .btn-primary, .btn-info, .btn-dark { --bs-btn-color: #F5F7F3; --bs-btn-bg: #315C4D; --bs-btn-border-color: #315C4D; --bs-btn-hover-color: #F5F7F3; --bs-btn-hover-bg: #274B3F; --bs-btn-hover-border-color: #274B3F; --bs-btn-active-color: #F5F7F3; --bs-btn-active-bg: #203D34; --bs-btn-active-border-color: #203D34; --bs-btn-disabled-bg: #315C4D; --bs-btn-disabled-border-color: #315C4D; --bs-btn-disabled-color: #F5F7F3; }
        .btn-secondary, .btn-light { --bs-btn-color: #203D34; --bs-btn-bg: #EDF2EC; --bs-btn-border-color: #DCE6DC; --bs-btn-hover-color: #203D34; --bs-btn-hover-bg: #E3EBE2; --bs-btn-hover-border-color: #C9D4CC; --bs-btn-active-color: #203D34; --bs-btn-active-bg: #DCE6DC; --bs-btn-active-border-color: #C9D4CC; }
        .btn-warning { --bs-btn-color: #152A23; --bs-btn-bg: #C58A62; --bs-btn-border-color: #C58A62; --bs-btn-hover-color: #152A23; --bs-btn-hover-bg: #D49A72; --bs-btn-hover-border-color: #D49A72; --bs-btn-active-bg: #B97C55; --bs-btn-active-border-color: #B97C55; }
        .btn-success { --bs-btn-bg: #16794C; --bs-btn-border-color: #16794C; --bs-btn-hover-bg: #145C3A; --bs-btn-hover-border-color: #145C3A; --bs-btn-active-bg: #145C3A; }
        .btn-danger { --bs-btn-bg: #B42318; --bs-btn-border-color: #B42318; --bs-btn-hover-bg: #912018; --bs-btn-hover-border-color: #912018; --bs-btn-active-bg: #912018; }
        .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-info, .btn-outline-light { --bs-btn-color: #1D2924; --bs-btn-bg: transparent; --bs-btn-border-color: #C9D4CC; --bs-btn-hover-color: #F5F7F3; --bs-btn-hover-bg: #315C4D; --bs-btn-hover-border-color: #315C4D; --bs-btn-active-color: #F5F7F3; --bs-btn-active-bg: #203D34; --bs-btn-active-border-color: #203D34; }
        .btn-outline-warning { --bs-btn-color: #754C05; --bs-btn-border-color: #F0D79E; --bs-btn-hover-color: #152A23; --bs-btn-hover-bg: #C58A62; --bs-btn-hover-border-color: #C58A62; }
        .btn-outline-success { --bs-btn-color: #145C3A; --bs-btn-border-color: #BCE2C9; --bs-btn-hover-bg: #16794C; --bs-btn-hover-border-color: #16794C; }
        .btn-outline-danger { --bs-btn-color: #912018; --bs-btn-border-color: #F5C2BE; --bs-btn-hover-bg: #B42318; --bs-btn-hover-border-color: #B42318; }

        /* Forms */
        .form-control, .form-select { min-height: 44px; border: 1px solid var(--nx-border-strong); border-radius: 12px; background-color: var(--nx-card); color: var(--nx-text); font-size: 14px; transition: border-color .2s var(--nx-ease), box-shadow .2s var(--nx-ease); }
        .form-control-sm, .form-select-sm { min-height: 36px; border-radius: 8px; }
        textarea.form-control { min-height: 104px; }
        .form-control::placeholder { color: var(--nx-text-muted); }
        .form-control:hover:not(:disabled):not(.is-invalid), .form-select:hover:not(:disabled):not(.is-invalid) { border-color: var(--nx-secondary); }
        .form-control:focus, .form-select:focus, .form-check-input:focus { border-color: var(--nx-primary); box-shadow: 0 0 0 3px rgba(49, 92, 77, .22); }
        .form-control:disabled, .form-select:disabled, .form-control[readonly] { background-color: #F0F2EF; color: #657169; }
        .form-control.is-invalid, .form-select.is-invalid { border-color: #B42318; }
        .invalid-feedback, .text-danger { color: #912018 !important; }
        .form-label, .col-form-label { font-size: 13px; font-weight: 600; color: var(--nx-text); }
        .form-text { color: var(--nx-text-muted); font-size: 12px; }
        .form-check-input:checked { background-color: var(--nx-primary); border-color: var(--nx-primary); }
        .input-group-text { border-color: var(--nx-border-strong); background: var(--nx-surface); color: var(--nx-text-secondary); border-radius: 12px; }

        /* Cards */
        .card { --bs-card-border-color: var(--nx-border); --bs-card-border-radius: 16px; --bs-card-inner-border-radius: 15px; --bs-card-cap-bg: var(--nx-surface); background-color: var(--nx-card); box-shadow: 0 1px 2px rgba(29, 41, 36, .05); color: var(--nx-text); }
        .card-header { font-family: var(--nx-font-heading); font-weight: 700; border-bottom-color: var(--nx-divider); padding: 16px 24px; }
        .card-body { padding: 24px; }
        .card-footer { border-top-color: var(--nx-divider); padding: 16px 24px; }
        .list-group { --bs-list-group-border-color: var(--nx-border); --bs-list-group-border-radius: 12px; --bs-list-group-active-bg: var(--nx-primary); --bs-list-group-active-border-color: var(--nx-primary); }

        /* Tables — safe horizontal containment */
        .table-responsive { border-radius: 16px; -webkit-overflow-scrolling: touch; }
        .table { --bs-table-color: var(--nx-text); --bs-table-bg: transparent; --bs-table-border-color: var(--nx-divider); --bs-table-striped-bg: rgba(49, 92, 77, .03); --bs-table-hover-bg: var(--nx-surface); font-size: 14px; vertical-align: middle; }
        .table > :not(caption) > * > * { padding: 14px 16px; }
        .table thead th, .table > thead > tr > th { background: var(--nx-surface); color: var(--nx-text-secondary); font-size: 12px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; border-bottom-color: var(--nx-border); }
        .table-light { --bs-table-bg: var(--nx-surface); --bs-table-color: var(--nx-text-secondary); --bs-table-border-color: var(--nx-border); }
        .table-dark { --bs-table-bg: var(--nx-sidebar); --bs-table-color: #F5F7F3; --bs-table-border-color: rgba(255, 255, 255, .12); }
        .table-dark th, .table thead.table-dark th { background: var(--nx-sidebar); color: #F5F7F3; }

        /* Badges & alerts — semantic distinctions preserved */
        .badge { border-radius: 999px; padding: 5px 10px; font-size: 12px; font-weight: 600; letter-spacing: 0; font-family: var(--nx-font-body); }
        .badge.bg-success { background-color: #EAF6EF !important; color: #145C3A !important; box-shadow: inset 0 0 0 1px #BCE2C9; }
        .badge.bg-warning { background-color: #FFF6E5 !important; color: #754C05 !important; box-shadow: inset 0 0 0 1px #F0D79E; }
        .badge.bg-danger { background-color: #FDEDEC !important; color: #912018 !important; box-shadow: inset 0 0 0 1px #F5C2BE; }
        .badge.bg-info, .badge.bg-primary { background-color: #E7EEE7 !important; color: #203D34 !important; box-shadow: inset 0 0 0 1px #C9D4CC; }
        .badge.bg-secondary, .badge.bg-light, .badge.bg-dark { background-color: #F0F2EF !important; color: #53615A !important; box-shadow: inset 0 0 0 1px #D9DEDA; }
        .alert { border-radius: 12px; font-size: 14px; padding: 14px 16px; }
        .alert-success { --bs-alert-color: #145C3A; --bs-alert-bg: #EAF6EF; --bs-alert-border-color: #BCE2C9; }
        .alert-warning { --bs-alert-color: #754C05; --bs-alert-bg: #FFF6E5; --bs-alert-border-color: #F0D79E; }
        .alert-danger { --bs-alert-color: #912018; --bs-alert-bg: #FDEDEC; --bs-alert-border-color: #F5C2BE; }
        .alert-info, .alert-primary { --bs-alert-color: #203D34; --bs-alert-bg: #EDF2EC; --bs-alert-border-color: #C9D4CC; }

        /* Modals, pagination, nav tabs */
        .modal-content { border: 1px solid var(--nx-border); border-radius: 16px; box-shadow: 0 12px 32px rgba(29, 41, 36, .12); }
        .modal-header { border-bottom-color: var(--nx-divider); padding: 20px 24px; }
        .modal-footer { border-top-color: var(--nx-divider); padding: 16px 24px; }
        .modal-backdrop { --bs-backdrop-bg: #101B16; }
        .pagination { --bs-pagination-color: var(--nx-text-secondary); --bs-pagination-border-color: var(--nx-border); --bs-pagination-hover-color: var(--nx-text); --bs-pagination-hover-bg: var(--nx-surface); --bs-pagination-active-bg: var(--nx-primary); --bs-pagination-active-border-color: var(--nx-primary); --bs-pagination-focus-box-shadow: 0 0 0 3px rgba(49, 92, 77, .22); flex-wrap: wrap; gap: 6px; }
        .pagination .page-link { border-radius: 8px !important; min-width: 36px; text-align: center; }
        .nav-tabs { --bs-nav-tabs-border-color: var(--nx-border); --bs-nav-tabs-link-active-color: var(--nx-primary); }
        .nav-pills { --bs-nav-pills-border-radius: 999px; --bs-nav-pills-link-active-bg: var(--nx-primary); }
        .nav-link { color: var(--nx-text-secondary); }
        .nav-link:hover { color: var(--nx-text); }
        .progress { background-color: #E8EDE8; border-radius: 999px; }
        .progress-bar { background-color: var(--nx-primary); }

        /* Responsive containment */
        img, video { max-width: 100%; }
        @media (max-width: 767px) {
            .card-body { padding: 18px; }
            .table-responsive > .table, .table-responsive table { min-width: 640px; }
            .container, .container-fluid { padding-inline: 16px; }
            .d-flex.justify-content-between { flex-wrap: wrap; gap: 12px; }
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5 text-center" style="max-width: 500px;">
    <h2 class="text-success">Payment successful</h2>
    <p>Thank you. Your booking or membership will be confirmed in a few seconds, once Stripe notifies our server.</p>
    <a href="{{ route('stripe.test') }}" class="btn btn-primary">Back</a>
</div>
</body>
</html>