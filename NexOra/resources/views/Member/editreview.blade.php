@extends('layouts.dashboard')

@section('dashboard-role', 'member')

@section('title') Edit Review | NexOra @endsection
@section('page-title') Edit Review @endsection

@push('styles')
    <style>
        .nx-page-content *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .nx-page-content{
            font-family: 'Inter', 'Noto Sans Arabic', system-ui, sans-serif;
            background: #F5F7F3;
            color: #1D2924;
            min-height: 100vh;
        }

        .nx-page-content .container{
            max-width: 780px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .nx-page-content .back-link{
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #274B3F;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 28px;
        }

        .nx-page-content .back-link:hover{
            color: #203D34;
        }

        .nx-page-content .page-header{
            margin-bottom: 30px;
        }

        .nx-page-content .page-title{
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .nx-page-content .page-description{
            color: #7F8B84;
            font-size: 15px;
            line-height: 1.7;
        }

        .nx-page-content .review-card{
            background: #ffffff;
            border: 1px solid #E9EEE9;
            border-radius: 18px;
            padding: 38px;
            box-shadow: 0 8px 30px rgba(21, 42, 35, 0.04);
        }

        .nx-page-content .card-heading{
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .nx-page-content .card-description{
            color: #7F8B84;
            font-size: 14px;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .nx-page-content .form-group{
            margin-bottom: 28px;
        }

        .nx-page-content .form-label{
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1D2924;
            margin-bottom: 12px;
        }

        .nx-page-content .required{
            color: #274B3F;
        }

        .nx-page-content .form-control{
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #E2E8E2;
            border-radius: 10px;
            background: #ffffff;
            color: #1D2924;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .nx-page-content .form-control:focus{
            border-color: #274B3F;
            box-shadow: 0 0 0 3px rgba(39, 75, 63, 0.1);
        }

        .nx-page-content .form-control:disabled{
            background: #F5F7F3;
            color: #53615A;
            cursor: not-allowed;
        }

        .nx-page-content .form-hint{
            display: block;
            font-size: 12px;
            color: #7F8B84;
            margin-top: 9px;
            line-height: 1.6;
        }

        .nx-page-content textarea.form-control{
            min-height: 150px;
            resize: vertical;
            line-height: 1.7;
        }

        /* Star Rating */
        .nx-page-content .rating-options{
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 7px;
            width: fit-content;
        }

        .nx-page-content .rating-options input{
            position: absolute;
            opacity: 0;
            width: 1px;
            height: 1px;
        }

        .nx-page-content .rating-options label{
            font-size: 38px;
            color: #E2E8E2;
            cursor: pointer;
            line-height: 1;
            transition: color 0.2s, transform 0.2s;
        }

        .nx-page-content .rating-options label:hover{
            transform: scale(1.1);
        }

        .nx-page-content .rating-options label:hover,
        .nx-page-content .rating-options label:hover ~ label,
        .nx-page-content .rating-options input:checked ~ label{
            color: #f4b942;
        }

        .nx-page-content .rating-options input:focus-visible + label{
            outline: 2px solid #274B3F;
            outline-offset: 3px;
            border-radius: 4px;
        }

        .nx-page-content .divider{
            height: 1px;
            background: #F5F7F3;
            margin: 30px 0;
        }

        .nx-page-content .form-actions{
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
        }

        .nx-page-content .btn{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 13px 24px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
            cursor: pointer;
            transition: 0.2s;
            border: none;
        }

        .nx-page-content .btn-secondary{
            background: #F5F7F3;
            color: #53615A;
        }

        .nx-page-content .btn-secondary:hover{
            background: #E9EEE9;
        }

        .nx-page-content .btn-primary{
            background: #274B3F;
            color: #ffffff;
        }

        .nx-page-content .btn-primary:hover{
            background: #203D34;
        }

        .nx-page-content .alert-error{
            background: #F5F7F3;
            border: 1px solid #f5cccc;
            border-radius: 10px;
            color: #b42332;
            padding: 15px 18px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .nx-page-content .alert-error ul{
            padding-left: 20px;
        }

        .nx-page-content .alert-error li{
            margin: 5px 0;
        }

        @media (max-width: 600px) {
            .nx-page-content .container{
                margin: 30px auto;
                padding: 0 15px;
            }

            .nx-page-content .page-title{
                font-size: 26px;
            }

            .nx-page-content .review-card{
                padding: 24px 20px;
            }

            .nx-page-content .form-actions{
                flex-direction: column-reverse;
            }

            .nx-page-content .btn{
                width: 100%;
            }

            .nx-page-content .rating-options label{
                font-size: 34px;
            }
        }
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
    </style>
@endpush

@section('content')
<div class="nx-page-content">
    <div class="container">

        <a href="{{ route('member.reviews.index') }}" class="back-link">
            ← Back to My Reviews
        </a>

        <div class="page-header">
            <h1 class="page-title">Edit Review</h1>
            <p class="page-description">
                Update your rating and share your latest experience
                with this workspace.
            </p>
        </div>

        <div class="review-card">

            <h2 class="card-heading">Update Your Review</h2>

            <p class="card-description">
                Make changes to your review below.
                Your feedback helps us improve the workspace experience.
            </p>

            @if ($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('member.reviews.update', $review->id) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <!-- Workspace -->
                <div class="form-group">
                    <label for="workspace" class="form-label">
                        Workspace
                    </label>

                    <input
                        type="text"
                        id="workspace"
                        class="form-control"
                        value="{{ $review->workspace->name }}"
                        disabled
                    >

                    <span class="form-hint">
                        You cannot change the workspace of an existing review.
                    </span>
                </div>

                <!-- Rating -->
                <div class="form-group">

                    <label class="form-label">
                        Your Rating <span class="required">*</span>
                    </label>

                    <div class="rating-options">

                        <input
                            type="radio"
                            name="rating"
                            id="star5"
                            value="5"
                            {{ old('rating', $review->rating) == 5 ? 'checked' : '' }}
                            required
                        >
                        <label for="star5" title="5 Stars">★</label>

                        <input
                            type="radio"
                            name="rating"
                            id="star4"
                            value="4"
                            {{ old('rating', $review->rating) == 4 ? 'checked' : '' }}
                        >
                        <label for="star4" title="4 Stars">★</label>

                        <input
                            type="radio"
                            name="rating"
                            id="star3"
                            value="3"
                            {{ old('rating', $review->rating) == 3 ? 'checked' : '' }}
                        >
                        <label for="star3" title="3 Stars">★</label>

                        <input
                            type="radio"
                            name="rating"
                            id="star2"
                            value="2"
                            {{ old('rating', $review->rating) == 2 ? 'checked' : '' }}
                        >
                        <label for="star2" title="2 Stars">★</label>

                        <input
                            type="radio"
                            name="rating"
                            id="star1"
                            value="1"
                            {{ old('rating', $review->rating) == 1 ? 'checked' : '' }}
                        >
                        <label for="star1" title="1 Star">★</label>

                    </div>

                    <span class="form-hint">
                        Choose a rating between 1 and 5 stars.
                    </span>

                </div>

                <!-- Comment -->
                <div class="form-group">

                    <label for="comment" class="form-label">
                        Your Comment (Optional)
                    </label>

                    <textarea
                        name="comment"
                        id="comment"
                        class="form-control"
                        placeholder="Tell us about your experience..."
                    >{{ old('comment', $review->comment) }}</textarea>

                    <span class="form-hint">
                        Share what you liked or what could be improved.
                    </span>

                </div>

                <div class="divider"></div>

                <!-- Actions -->
                <div class="form-actions">

                    <a
                        href="{{ route('member.reviews.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>
</div>
@endsection
