@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@if (request()->query('view') === 'list')
    @include('Manager.partials.my-workspaces', [
        'workspaces' => auth()->user()->managedWorkspaces()
            ->with(['images' => fn ($query) => $query->orderByDesc('is_primary')->orderBy('sort_order')])
            ->latest()
            ->get(),
    ])
@else
@section('title') Create workspace | NexOra @endsection
@section('page-title') Create workspace @endsection

@push('styles')
    <style>
        .nx-page-content{
            --forest: #152A23;
            --cream: #F5F7F3;
            --paper: #F5F7F3;
            --orange: #C58A62;
            --orange-hover: #E6C9B3;
            --ink: #152A23;
            --muted: #53615A;
            --line: #C9D4CC;
            --error: #a32c24;
        }

        .nx-page-content *{ box-sizing: border-box; }

        .nx-page-content{
            margin: 0;
            background: #F5F7F3;
            color: var(--ink);
            font: 1rem/1.5 system-ui, sans-serif;
        }

        .nx-page-content .topbar{ display: none; }

        .nx-page-content .topbar-inner, .nx-page-content [data-nx-page-main]{
            width: min(1060px, calc(100% - 32px));
            margin-inline: auto;
        }

        .nx-page-content .topbar-inner{
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            min-height: 64px;
            padding-block: 12px;
        }

        .nx-page-content .brand{
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -.04em;
        }

        .nx-page-content .back{
            color: inherit;
            font-size: .9rem;
            font-weight: 650;
            text-decoration: none;
        }

        .nx-page-content .back:hover{ text-decoration: underline; }

        .nx-page-content [data-nx-page-main]{ padding-block: clamp(28px, 5vw, 64px); }

        .nx-page-content .page-heading{ margin-bottom: 24px; }

        .nx-page-content h1, .nx-page-content h2, .nx-page-content p{ margin: 0; }

        .nx-page-content h1{
            font-size: clamp(2.1rem, 5vw, 3.4rem);
            line-height: 1.05;
            letter-spacing: -.055em;
        }

        .nx-page-content .page-heading p{ margin-top: 10px; color: var(--muted); }

        .nx-page-content .workspace-form{
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 22px;
            background: var(--paper);
            box-shadow: 0 12px 30px #103b340b;
        }

        .nx-page-content .form-grid{
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        }

        .nx-page-content .panel{ padding: clamp(20px, 4vw, 32px); }
        .nx-page-content .cover-panel{ background: #E9EEE9; border-left: 1px solid var(--line); }

        .nx-page-content h2{
            margin-bottom: 18px;
            font-size: 1.15rem;
            letter-spacing: -.02em;
        }

        .nx-page-content .field + .field{ margin-top: 20px; }

        .nx-page-content label{
            display: block;
            margin-bottom: 6px;
            font-size: .92rem;
            font-weight: 700;
        }

        .nx-page-content input[type="text"], .nx-page-content textarea{
            width: 100%;
            min-height: 48px;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            color: var(--ink);
            font: inherit;
        }

        .nx-page-content input[aria-invalid="true"], .nx-page-content textarea[aria-invalid="true"]{ border-color: var(--error); }

        .nx-page-content a:focus-visible, .nx-page-content button:focus-visible, .nx-page-content input:focus-visible, .nx-page-content textarea:focus-visible{
            outline: 2px solid #276a59;
            outline-offset: 3px;
        }

        .nx-page-content .preview{
            display: grid;
            place-items: center;
            aspect-ratio: 16 / 9;
            margin-bottom: 14px;
            overflow: hidden;
            border: 1px dashed #7F8B84;
            border-radius: 14px;
            background: #E2E8E2;
        }

        .nx-page-content .preview img{
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .nx-page-content .preview-placeholder{
            display: grid;
            justify-items: center;
            gap: 6px;
            padding: 16px;
            color: var(--muted);
            font-size: .9rem;
            text-align: center;
        }

        .nx-page-content [hidden]{ display: none !important; }

        .nx-page-content input[type="file"]{
            width: 100%;
            color: var(--ink);
            font: inherit;
            font-size: .88rem;
        }

        .nx-page-content input::file-selector-button{
            margin-right: 10px;
            padding: 8px 12px;
            border: 1px solid #7F8B84;
            border-radius: 8px;
            background: var(--paper);
            color: var(--forest);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .nx-page-content input::file-selector-button:hover{ background: #E2E8E2; }

        .nx-page-content .hint, .nx-page-content .error{
            margin-top: 6px;
            font-size: .85rem;
        }

        .nx-page-content .hint{ color: var(--muted); }
        .nx-page-content .error{ color: var(--error); }

        .nx-page-content .form-footer{
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
            padding: 16px clamp(20px, 4vw, 32px);
            border-top: 1px solid var(--line);
        }

        .nx-page-content .cancel{ color: var(--forest); }

        .nx-page-content .submit{
            min-height: 46px;
            padding: 9px 18px;
            border: 1px solid #C58A62;
            border-radius: 10px;
            background: var(--orange);
            color: #1D2924;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }

        .nx-page-content .submit:hover{ background: var(--orange-hover); }

        @media (max-width: 680px) {
            .nx-page-content .form-grid{ grid-template-columns: 1fr; }

            .nx-page-content .cover-panel{
                border-top: 1px solid var(--line);
                border-left: 0;
            }

            .nx-page-content .submit{ flex: 1; }
        }
        .nx-page-content textarea{ display: block; resize: vertical; }

        .nx-page-content .optional{ color: var(--muted); font-weight: 400; }

        .nx-page-content .facilities{ min-width: 0; margin-inline: 0; padding: 0; border: 0; }

        .nx-page-content .facilities legend{
            margin-bottom: 8px;
            padding: 0;
            font-size: .92rem;
            font-weight: 700;
        }

        .nx-page-content .facility-options{
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .nx-page-content .facility-options label{
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin: 0;
            padding: 7px 10px;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 500;
            cursor: pointer;
        }

        .nx-page-content .facility-options label:hover,
        .nx-page-content .facility-options label:has(input:checked){ background: #E2E8E2; }

        .nx-page-content .facility-options input{ margin: 0; accent-color: var(--forest); }
        .nx-page-content .workspace-directory-link{
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            padding: 8px 12px;
            border: 1px solid #C9D4CC;
            border-radius: 9px;
            background: #E9EEE9;
            color: var(--forest);
            font-weight: 700;
            text-decoration: none;
        }

        .nx-page-content .workspace-directory-link:hover{ background: #E2E8E2; }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* ===== NexOra visual skin (tokens mirror welcome.blade.php) ===== */
        .nx-page-content{
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
        :where(.nx-page-content){ background-color: var(--nx-bg); color: var(--nx-text); }
        .nx-page-content{ font-family: var(--nx-font-body); -webkit-font-smoothing: antialiased; overflow-wrap: break-word; }
        .nx-page-content :where(h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .navbar-brand, .card-title, .modal-title){ font-family: var(--nx-font-heading); letter-spacing: -0.02em; color: inherit; }
        .nx-page-content :where(a){ color: var(--nx-primary); }
        .nx-page-content :where(a:hover){ color: var(--nx-primary-active); }
        .nx-page-content :focus-visible{ outline: 2px solid var(--nx-accent); outline-offset: 2px; }
        .nx-page-content .text-muted, .nx-page-content .text-secondary{ color: var(--nx-text-secondary) !important; }
        .nx-page-content .bg-light{ background-color: var(--nx-surface) !important; }
        .nx-page-content .bg-white{ background-color: var(--nx-card) !important; }
        .nx-page-content .bg-dark{ background-color: var(--nx-sidebar) !important; }
        .nx-page-content .shadow-sm{ box-shadow: 0 1px 2px rgba(29, 41, 36, .05) !important; }
        .nx-page-content .shadow{ box-shadow: 0 4px 12px rgba(29, 41, 36, .07) !important; }
        .nx-page-content .shadow-lg{ box-shadow: 0 12px 32px rgba(29, 41, 36, .12) !important; }

        /* Navbar */
        .nx-page-content .navbar{ min-height: 72px; border-bottom: 1px solid var(--nx-border); }
        .nx-page-content .navbar .navbar-brand{ font-weight: 800; font-size: 21px; letter-spacing: -.03em; color: var(--nx-text); }
        .nx-page-content .navbar .nav-link{ font-size: 14px; font-weight: 500; color: var(--nx-text-secondary); min-height: 44px; display: flex; align-items: center; }
        .nx-page-content .navbar .nav-link:hover, .nx-page-content .navbar .nav-link.active{ color: var(--nx-text); }
        .nx-page-content .navbar-toggler{ border-color: var(--nx-border-strong); border-radius: 999px; min-height: 44px; }
        .nx-page-content .navbar-toggler:focus{ box-shadow: 0 0 0 3px rgba(49, 92, 77, .22); }
        .nx-page-content .dropdown-menu{ border: 1px solid var(--nx-border); border-radius: 12px; padding: 6px; box-shadow: 0 4px 12px rgba(29, 41, 36, .07); font-size: 14px; }
        .nx-page-content .dropdown-item{ border-radius: 8px; min-height: 40px; display: flex; align-items: center; color: var(--nx-text); }
        .nx-page-content .dropdown-item:hover, .nx-page-content .dropdown-item:focus{ background: var(--nx-surface); color: var(--nx-text); }
        .nx-page-content .dropdown-item.active, .nx-page-content .dropdown-item:active{ background: var(--nx-primary); color: #fff; }

        /* Buttons — landing pill language, 44px professional controls */
        .nx-page-content .btn{
            --bs-btn-padding-x: 20px; --bs-btn-padding-y: 9px; --bs-btn-font-size: 14px; --bs-btn-font-weight: 700;
            --bs-btn-border-radius: 999px; --bs-btn-focus-box-shadow: 0 0 0 3px rgba(49, 92, 77, .22);
            min-height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            font-family: var(--nx-font-heading); letter-spacing: -.005em;
            transition: background-color .3s var(--nx-ease), color .3s var(--nx-ease), border-color .3s var(--nx-ease), transform .3s var(--nx-ease);
        }
        .nx-page-content .btn:active{ transform: scale(.98); }
        .nx-page-content .btn-sm{ --bs-btn-padding-x: 14px; --bs-btn-padding-y: 6px; --bs-btn-font-size: 13px; min-height: 36px; }
        .nx-page-content .btn-lg{ --bs-btn-padding-x: 24px; --bs-btn-font-size: 15px; min-height: 52px; }
        .nx-page-content .btn-close{ min-height: 0; border-radius: 8px; }
        .nx-page-content .btn-link{ min-height: auto; }
        .nx-page-content .btn-primary, .nx-page-content .btn-info, .nx-page-content .btn-dark{ --bs-btn-color: #F5F7F3; --bs-btn-bg: #315C4D; --bs-btn-border-color: #315C4D; --bs-btn-hover-color: #F5F7F3; --bs-btn-hover-bg: #274B3F; --bs-btn-hover-border-color: #274B3F; --bs-btn-active-color: #F5F7F3; --bs-btn-active-bg: #203D34; --bs-btn-active-border-color: #203D34; --bs-btn-disabled-bg: #315C4D; --bs-btn-disabled-border-color: #315C4D; --bs-btn-disabled-color: #F5F7F3; }
        .nx-page-content .btn-secondary, .nx-page-content .btn-light{ --bs-btn-color: #203D34; --bs-btn-bg: #EDF2EC; --bs-btn-border-color: #DCE6DC; --bs-btn-hover-color: #203D34; --bs-btn-hover-bg: #E3EBE2; --bs-btn-hover-border-color: #C9D4CC; --bs-btn-active-color: #203D34; --bs-btn-active-bg: #DCE6DC; --bs-btn-active-border-color: #C9D4CC; }
        .nx-page-content .btn-warning{ --bs-btn-color: #152A23; --bs-btn-bg: #C58A62; --bs-btn-border-color: #C58A62; --bs-btn-hover-color: #152A23; --bs-btn-hover-bg: #D49A72; --bs-btn-hover-border-color: #D49A72; --bs-btn-active-bg: #B97C55; --bs-btn-active-border-color: #B97C55; }
        .nx-page-content .btn-success{ --bs-btn-bg: #16794C; --bs-btn-border-color: #16794C; --bs-btn-hover-bg: #145C3A; --bs-btn-hover-border-color: #145C3A; --bs-btn-active-bg: #145C3A; }
        .nx-page-content .btn-danger{ --bs-btn-bg: #B42318; --bs-btn-border-color: #B42318; --bs-btn-hover-bg: #912018; --bs-btn-hover-border-color: #912018; --bs-btn-active-bg: #912018; }
        .nx-page-content .btn-outline-primary, .nx-page-content .btn-outline-secondary, .nx-page-content .btn-outline-dark, .nx-page-content .btn-outline-info, .nx-page-content .btn-outline-light{ --bs-btn-color: #1D2924; --bs-btn-bg: transparent; --bs-btn-border-color: #C9D4CC; --bs-btn-hover-color: #F5F7F3; --bs-btn-hover-bg: #315C4D; --bs-btn-hover-border-color: #315C4D; --bs-btn-active-color: #F5F7F3; --bs-btn-active-bg: #203D34; --bs-btn-active-border-color: #203D34; }
        .nx-page-content .btn-outline-warning{ --bs-btn-color: #754C05; --bs-btn-border-color: #F0D79E; --bs-btn-hover-color: #152A23; --bs-btn-hover-bg: #C58A62; --bs-btn-hover-border-color: #C58A62; }
        .nx-page-content .btn-outline-success{ --bs-btn-color: #145C3A; --bs-btn-border-color: #BCE2C9; --bs-btn-hover-bg: #16794C; --bs-btn-hover-border-color: #16794C; }
        .nx-page-content .btn-outline-danger{ --bs-btn-color: #912018; --bs-btn-border-color: #F5C2BE; --bs-btn-hover-bg: #B42318; --bs-btn-hover-border-color: #B42318; }

        /* Forms */
        .nx-page-content .form-control, .nx-page-content .form-select{ min-height: 44px; border: 1px solid var(--nx-border-strong); border-radius: 12px; background-color: var(--nx-card); color: var(--nx-text); font-size: 14px; transition: border-color .2s var(--nx-ease), box-shadow .2s var(--nx-ease); }
        .nx-page-content .form-control-sm, .nx-page-content .form-select-sm{ min-height: 36px; border-radius: 8px; }
        .nx-page-content textarea.form-control{ min-height: 104px; }
        .nx-page-content .form-control::placeholder{ color: var(--nx-text-muted); }
        .nx-page-content .form-control:hover:not(:disabled):not(.is-invalid), .nx-page-content .form-select:hover:not(:disabled):not(.is-invalid){ border-color: var(--nx-secondary); }
        .nx-page-content .form-control:focus, .nx-page-content .form-select:focus, .nx-page-content .form-check-input:focus{ border-color: var(--nx-primary); box-shadow: 0 0 0 3px rgba(49, 92, 77, .22); }
        .nx-page-content .form-control:disabled, .nx-page-content .form-select:disabled, .nx-page-content .form-control[readonly]{ background-color: #F0F2EF; color: #657169; }
        .nx-page-content .form-control.is-invalid, .nx-page-content .form-select.is-invalid{ border-color: #B42318; }
        .nx-page-content .invalid-feedback, .nx-page-content .text-danger{ color: #912018 !important; }
        .nx-page-content .form-label, .nx-page-content .col-form-label{ font-size: 13px; font-weight: 600; color: var(--nx-text); }
        .nx-page-content .form-text{ color: var(--nx-text-muted); font-size: 12px; }
        .nx-page-content .form-check-input:checked{ background-color: var(--nx-primary); border-color: var(--nx-primary); }
        .nx-page-content .input-group-text{ border-color: var(--nx-border-strong); background: var(--nx-surface); color: var(--nx-text-secondary); border-radius: 12px; }

        /* Cards */
        .nx-page-content .card{ --bs-card-border-color: var(--nx-border); --bs-card-border-radius: 16px; --bs-card-inner-border-radius: 15px; --bs-card-cap-bg: var(--nx-surface); background-color: var(--nx-card); box-shadow: 0 1px 2px rgba(29, 41, 36, .05); color: var(--nx-text); }
        .nx-page-content .card-header{ font-family: var(--nx-font-heading); font-weight: 700; border-bottom-color: var(--nx-divider); padding: 16px 24px; }
        .nx-page-content .card-body{ padding: 24px; }
        .nx-page-content .card-footer{ border-top-color: var(--nx-divider); padding: 16px 24px; }
        .nx-page-content .list-group{ --bs-list-group-border-color: var(--nx-border); --bs-list-group-border-radius: 12px; --bs-list-group-active-bg: var(--nx-primary); --bs-list-group-active-border-color: var(--nx-primary); }

        /* Tables — safe horizontal containment */
        .nx-page-content .table-responsive{ border-radius: 16px; -webkit-overflow-scrolling: touch; }
        .nx-page-content .table{ --bs-table-color: var(--nx-text); --bs-table-bg: transparent; --bs-table-border-color: var(--nx-divider); --bs-table-striped-bg: rgba(49, 92, 77, .03); --bs-table-hover-bg: var(--nx-surface); font-size: 14px; vertical-align: middle; }
        .nx-page-content .table > :not(caption) > * > *{ padding: 14px 16px; }
        .nx-page-content .table thead th, .nx-page-content .table > thead > tr > th{ background: var(--nx-surface); color: var(--nx-text-secondary); font-size: 12px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; border-bottom-color: var(--nx-border); }
        .nx-page-content .table-light{ --bs-table-bg: var(--nx-surface); --bs-table-color: var(--nx-text-secondary); --bs-table-border-color: var(--nx-border); }
        .nx-page-content .table-dark{ --bs-table-bg: var(--nx-sidebar); --bs-table-color: #F5F7F3; --bs-table-border-color: rgba(255, 255, 255, .12); }
        .nx-page-content .table-dark th, .nx-page-content .table thead.table-dark th{ background: var(--nx-sidebar); color: #F5F7F3; }

        /* Badges & alerts — semantic distinctions preserved */
        .nx-page-content .badge{ border-radius: 999px; padding: 5px 10px; font-size: 12px; font-weight: 600; letter-spacing: 0; font-family: var(--nx-font-body); }
        .nx-page-content .badge.bg-success{ background-color: #EAF6EF !important; color: #145C3A !important; box-shadow: inset 0 0 0 1px #BCE2C9; }
        .nx-page-content .badge.bg-warning{ background-color: #FFF6E5 !important; color: #754C05 !important; box-shadow: inset 0 0 0 1px #F0D79E; }
        .nx-page-content .badge.bg-danger{ background-color: #FDEDEC !important; color: #912018 !important; box-shadow: inset 0 0 0 1px #F5C2BE; }
        .nx-page-content .badge.bg-info, .nx-page-content .badge.bg-primary{ background-color: #E7EEE7 !important; color: #203D34 !important; box-shadow: inset 0 0 0 1px #C9D4CC; }
        .nx-page-content .badge.bg-secondary, .nx-page-content .badge.bg-light, .nx-page-content .badge.bg-dark{ background-color: #F0F2EF !important; color: #53615A !important; box-shadow: inset 0 0 0 1px #D9DEDA; }
        .nx-page-content .alert{ border-radius: 12px; font-size: 14px; padding: 14px 16px; }
        .nx-page-content .alert-success{ --bs-alert-color: #145C3A; --bs-alert-bg: #EAF6EF; --bs-alert-border-color: #BCE2C9; }
        .nx-page-content .alert-warning{ --bs-alert-color: #754C05; --bs-alert-bg: #FFF6E5; --bs-alert-border-color: #F0D79E; }
        .nx-page-content .alert-danger{ --bs-alert-color: #912018; --bs-alert-bg: #FDEDEC; --bs-alert-border-color: #F5C2BE; }
        .nx-page-content .alert-info, .nx-page-content .alert-primary{ --bs-alert-color: #203D34; --bs-alert-bg: #EDF2EC; --bs-alert-border-color: #C9D4CC; }

        /* Modals, pagination, nav tabs */
        .nx-page-content .modal-content{ border: 1px solid var(--nx-border); border-radius: 16px; box-shadow: 0 12px 32px rgba(29, 41, 36, .12); }
        .nx-page-content .modal-header{ border-bottom-color: var(--nx-divider); padding: 20px 24px; }
        .nx-page-content .modal-footer{ border-top-color: var(--nx-divider); padding: 16px 24px; }
        .modal-backdrop { --bs-backdrop-bg: #101B16; }
        .nx-page-content .pagination{ --bs-pagination-color: var(--nx-text-secondary); --bs-pagination-border-color: var(--nx-border); --bs-pagination-hover-color: var(--nx-text); --bs-pagination-hover-bg: var(--nx-surface); --bs-pagination-active-bg: var(--nx-primary); --bs-pagination-active-border-color: var(--nx-primary); --bs-pagination-focus-box-shadow: 0 0 0 3px rgba(49, 92, 77, .22); flex-wrap: wrap; gap: 6px; }
        .nx-page-content .pagination .page-link{ border-radius: 8px !important; min-width: 36px; text-align: center; }
        .nx-page-content .nav-tabs{ --bs-nav-tabs-border-color: var(--nx-border); --bs-nav-tabs-link-active-color: var(--nx-primary); }
        .nx-page-content .nav-pills{ --bs-nav-pills-border-radius: 999px; --bs-nav-pills-link-active-bg: var(--nx-primary); }
        .nx-page-content .nav-link{ color: var(--nx-text-secondary); }
        .nx-page-content .nav-link:hover{ color: var(--nx-text); }
        .nx-page-content .progress{ background-color: #E8EDE8; border-radius: 999px; }
        .nx-page-content .progress-bar{ background-color: var(--nx-primary); }

        /* Responsive containment */
        .nx-page-content img, .nx-page-content video{ max-width: 100%; }
        @media (max-width: 767px) {
            .nx-page-content .card-body{ padding: 18px; }
            .nx-page-content .table-responsive > .table, .nx-page-content .table-responsive table{ min-width: 640px; }
            .nx-page-content .container, .nx-page-content .container-fluid{ padding-inline: 16px; }
            .nx-page-content .d-flex.justify-content-between{ flex-wrap: wrap; gap: 12px; }
        }
            /* Main area polish */
        .nx-page-content [data-nx-page-main]{width:min(1120px,calc(100% - 48px));padding-block:12px 48px}
        .nx-page-content .page-heading{max-width:920px;margin-bottom:14px}
        .nx-page-content .page-heading h1{color:var(--nx-deep,#152A23);font-size:clamp(2.15rem,4vw,3.15rem);letter-spacing:-.06em}
        .nx-page-content .page-heading p{max-width:900px;margin-top:8px;color:var(--nx-text-secondary,#53615A);font-size:1.02rem;line-height:1.55}
        .nx-page-content .workspace-directory-link{min-height:40px;margin-top:10px;padding-inline:14px;border-color:#315c4d;border-radius:999px;background:#315c4d;color:#fff;transition:background .18s ease,border-color .18s ease,transform .18s ease}
        .nx-page-content .workspace-directory-link:hover{border-color:#274b3f;background:#274b3f;color:#fff;transform:translateY(-1px)}
        .nx-page-content .workspace-form{border-color:#dce4dc;border-radius:24px;background:#fff;box-shadow:0 18px 48px rgba(21,42,35,.08)}
        .nx-page-content .form-grid{grid-template-columns:minmax(0,1.12fr) minmax(320px,.88fr)}
        .nx-page-content .panel{padding:clamp(22px,3vw,28px)}
        .nx-page-content .cover-panel{border-left-color:#e1e8e1;background:#F8FAF7}
        .nx-page-content h2{margin-bottom:16px;font-size:1.2rem;font-weight:800}
        .nx-page-content .field+.field{margin-top:16px}
        .nx-page-content label,.nx-page-content .facilities legend{margin-bottom:8px;color:#243b32;font-size:.93rem;font-weight:700}
        .nx-page-content input[type="text"],.nx-page-content textarea{min-height:50px;padding:12px 14px;border-color:#d5dfd7;border-radius:12px;background:#fff;transition:border-color .18s ease,box-shadow .18s ease}
        .nx-page-content textarea{min-height:100px}
        .nx-page-content input[type="text"]:hover,.nx-page-content textarea:hover{border-color:#a8b9ac}
        .nx-page-content input[type="text"]:focus,.nx-page-content textarea:focus{border-color:#315c4d;box-shadow:0 0 0 4px rgba(49,92,77,.12);outline:0}
        .nx-page-content .facilities{margin-top:18px}
        .nx-page-content .facility-options{gap:9px}
        .nx-page-content .facility-options label{min-height:40px;padding:8px 11px;border-color:#d8e1d9;border-radius:999px;background:#fff;color:#40534a;transition:background .18s ease,border-color .18s ease,color .18s ease}
        .nx-page-content .facility-options label:hover{border-color:#9eb3a5;background:#f2f6f2}
        .nx-page-content .facility-options label:has(input:checked){border-color:#84a291;background:#e6efe8;color:#214a3c}
        .nx-page-content .facility-options input{width:16px;height:16px}
        .nx-page-content .preview{min-height:190px;aspect-ratio:4/3;margin-bottom:14px;border:1px dashed #9fb5a6;border-radius:18px;background:rgba(255,255,255,.68)}
        .nx-page-content .preview-placeholder{gap:10px;padding:24px}
        .nx-page-content input[type="file"]{padding:12px;border:1px solid #d5dfd7;border-radius:12px;background:rgba(255,255,255,.78)}
        .nx-page-content input::file-selector-button{margin-right:12px;padding:9px 13px;border-color:#315c4d;border-radius:9px;background:#315c4d;color:#fff;transition:background .18s ease}
        .nx-page-content input::file-selector-button:hover{background:#274b3f}
        .nx-page-content .hint{margin-top:10px;line-height:1.6}
        .nx-page-content .form-footer{min-height:72px;gap:12px;padding-block:12px;background:#fbfcfa}
        .nx-page-content .cancel{padding:10px 16px;border:1px solid #d6dfd7;border-radius:10px;text-decoration:none;font-weight:700;transition:background .18s ease}
        .nx-page-content .cancel:hover{background:#eef3ee}
        .nx-page-content .submit{min-height:48px;padding-inline:20px;border-color:#315c4d;border-radius:11px;background:#315c4d;color:#fff;transition:background .18s ease,transform .18s ease,box-shadow .18s ease}
        .nx-page-content .submit:hover{background:#274b3f;box-shadow:0 6px 16px rgba(49,92,77,.18);transform:translateY(-1px)}
        @media(max-width:760px){.nx-page-content [data-nx-page-main]{width:min(calc(100% - 28px),620px);padding-block:12px 36px}.nx-page-content .form-grid{grid-template-columns:1fr}.nx-page-content .cover-panel{border-top:1px solid #e1e8e1;border-left:0}.nx-page-content .preview{min-height:180px}}
        @media(max-width:480px){.nx-page-content .form-footer{align-items:stretch}.nx-page-content .cancel,.nx-page-content .submit{flex:1;justify-content:center;text-align:center}}
    </style>
    <link rel="stylesheet" href="{{ asset('css/nexora/workspace-pages.css') }}">
@endpush

@section('content')
<div class="nx-page-content nx-workspace-ui nx-workspace-create">
    <header class="topbar">
        <div class="topbar-inner">
            <span class="brand">NexOra</span>
            <a class="back" href="{{ route('manager.workspaces.create', ['view' => 'list']) }}">&larr; My workspaces</a>
        </div>
    </header>

    <div data-nx-page-main>
        <header class="page-heading">
            <h1>Create workspace</h1>
            <p>Add your workspace details and a cover photo. You can add rooms after creating the workspace.</p>
            <a class="workspace-directory-link" href="{{ route('manager.workspaces.create', ['view' => 'list']) }}">
                View my workspaces <span aria-hidden="true">&rarr;</span>
            </a>
        </header>

        <form class="workspace-form" method="POST"
              action="{{ route('manager.workspaces.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <section class="panel" aria-labelledby="basics-title">
                    <h2 id="basics-title">Workspace basics</h2>

                    <div class="field">
                        <label for="name">Workspace name</label>
                        <input id="name" name="name" type="text"
                               value="{{ old('name') }}" placeholder="e.g. The Foundry"
                               maxlength="255" required
                               @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                        @error('name')
                            <p class="error" id="name-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="location">Location</label>
                        <input id="location" name="location" type="text"
                               value="{{ old('location') }}" placeholder="e.g. Abdali, Amman"
                               maxlength="255" required
                               @error('location') aria-invalid="true" aria-describedby="location-error" @enderror>
                        @error('location')
                            <p class="error" id="location-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="description">Description <span class="optional">(optional)</span></label>
                        <textarea id="description" name="description" rows="4"
                                  placeholder="Describe the space and what people can use it for."
                                  @error('description') aria-invalid="true" aria-describedby="description-error" @enderror>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="error" id="description-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <fieldset class="field facilities">
                        <legend>Facilities <span class="optional">(optional)</span></legend>
                        <div class="facility-options">
                            @foreach (['Wi-Fi', 'Meeting rooms', 'Private offices', 'Parking', 'Printing', 'Coffee bar', 'Air conditioning', 'Wheelchair access'] as $facility)
                                <label>
                                    <input type="checkbox" name="facilities[]" value="{{ $facility }}"
                                           @checked(in_array($facility, (array) old('facilities', []), true))>
                                    {{ $facility }}
                                </label>
                            @endforeach
                        </div>
                        @error('facilities')
                            <p class="error">{{ $message }}</p>
                        @enderror
                        @error('facilities.*')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </fieldset>
                </section>

                <section class="panel cover-panel" aria-labelledby="cover-title">
                    <h2 id="cover-title">Cover photo</h2>

                    <div class="preview">
                        <div class="preview-placeholder" id="cover-placeholder">
                            <span>No photo selected</span>
                        </div>
                        <img id="cover-preview" alt="Selected cover photo preview" hidden>
                    </div>

                    <label for="image">Choose a photo</label>
                    <input id="image" name="image" type="file"
                           accept="image/jpeg,image/png,image/webp" required
                           aria-describedby="image-hint @error('image') image-error @enderror"
                           @error('image') aria-invalid="true" @enderror>
                    <p class="hint" id="image-hint">JPG, PNG, or WebP. A landscape photo works best.</p>
                    @error('image')
                        <p class="error" id="image-error">{{ $message }}</p>
                    @enderror
                </section>
            </div>

            <footer class="form-footer">
                <a class="back cancel" href="{{ route('manager.workspaces.create', ['view' => 'list']) }}">Cancel</a>
                <button class="submit" type="submit">
                    Create workspace <span aria-hidden="true">&rarr;</span>
                </button>
            </footer>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const input = document.querySelector('#image');
        const preview = document.querySelector('#cover-preview');
        const placeholder = document.querySelector('#cover-placeholder');
        let previewUrl;

        input.addEventListener('change', () => {
            if (previewUrl) URL.revokeObjectURL(previewUrl);

            const file = input.files[0];
            preview.hidden = true;
            placeholder.hidden = false;
            preview.removeAttribute('src');

            if (!file) return;

            previewUrl = URL.createObjectURL(file);
            preview.src = previewUrl;
        });

        preview.addEventListener('load', () => {
            preview.hidden = false;
            placeholder.hidden = true;
        });
    </script>
@endpush
@endif
