{{--
Profile Routes 

use App\Http\Controllers\Manager\ManagerProfileController;

Route::get('/profile', [ManagerProfileController::class, 'index'])->name('profile.index');

Route::put('/profile', [ManagerProfileController::class, 'updatePersonal'])->name('profile.updatePersonal');

Route::put('/profile/password', [ManagerProfileController::class, 'updatePassword'])->name('profile.updatePassword');

--}}

@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Manager Profile | NexOra @endsection
@section('page-title') Manager Profile @endsection

@push('styles')
    <style>
        .nx-page-content{
            --navy: #152A23;
            --teal: #274B3F;
            --background: #F5F7F3;
            --border: #E9EEE9;
            --muted: #53615A;
        }

        .nx-page-content *{
            box-sizing: border-box;
        }

        .nx-page-content{
            margin: 0;
            font-family: 'Inter', 'Noto Sans Arabic', system-ui, sans-serif;
            background: var(--background);
            color: var(--navy);
        }

        .nx-page-content .topbar{
            background: white;
            padding: 18px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .nx-page-content .brand{
            font-size: 24px;
            font-weight: 800;
            color: var(--navy);
        }

        .nx-page-content .brand span{
            color: var(--teal);
        }

        .nx-page-content .topbar-label{
            font-size: 14px;
            color: var(--muted);
        }

        .nx-page-content .container{
            max-width: 1100px;
            margin: 38px auto;
            padding: 0 20px 50px;
        }

        .nx-page-content .page-heading{
            margin-bottom: 25px;
        }

        .nx-page-content .page-heading h1{
            font-size: 30px;
            margin: 0 0 8px;
        }

        .nx-page-content .page-heading p{
            color: var(--muted);
            margin: 0;
        }

        .nx-page-content .card{
            background: white;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 24px;
            box-shadow: 0 5px 22px rgba(21, 42, 35,.035);
        }

        .nx-page-content .profile-header{
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .nx-page-content .avatar{
            width: 78px;
            height: 78px;
            border-radius: 50%;
            background: #E2E8E2;
            color: var(--teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .nx-page-content .profile-header h2{
            margin: 0 0 5px;
            font-size: 21px;
        }

        .nx-page-content .profile-header p{
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .nx-page-content .profile-divider{
            height: 1px;
            background: var(--border);
            margin: 25px 0;
        }

        .nx-page-content .section-title{
            font-size: 20px;
            margin: 0 0 8px;
        }

        .nx-page-content .section-description{
            font-size: 14px;
            color: var(--muted);
            margin: 0 0 24px;
        }

        .nx-page-content .form-grid{
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .nx-page-content .form-group{
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .nx-page-content .form-group.full{
            grid-column: 1 / -1;
        }

        .nx-page-content label{
            font-size: 14px;
            font-weight: 600;
        }

        .nx-page-content input,
        .nx-page-content textarea{
            width: 100%;
            padding: 13px 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            font: inherit;
            font-size: 14px;
            background: white;
            color: var(--navy);
            outline: none;
            transition: border-color .2s;
        }

        .nx-page-content input:focus,
        .nx-page-content textarea:focus{
            border-color: var(--teal);
        }

        .nx-page-content textarea{
            min-height: 110px;
            resize: vertical;
        }

        .nx-page-content input[type="file"]{
            padding: 11px;
            cursor: pointer;
        }

        .nx-page-content .hint{
            font-size: 12px;
            color: var(--muted);
        }

        .nx-page-content .upload-area{
            border: 2px dashed #C9D4CC;
            border-radius: 12px;
            background: #F5F7F3;
            padding: 25px;
            text-align: center;
        }

        .nx-page-content .upload-icon{
            font-size: 30px;
            color: var(--teal);
            margin-bottom: 10px;
        }

        .nx-page-content .upload-area p{
            font-size: 14px;
            color: var(--muted);
            margin: 0 0 15px;
        }

        .nx-page-content .upload-area input{
            max-width: 360px;
            margin: auto;
        }

        .nx-page-content .actions{
            display: flex;
            justify-content: flex-end;
            margin-top: 26px;
        }

        .nx-page-content .btn{
            background: var(--teal);
            color: white;
            border: none;
            border-radius: 9px;
            padding: 13px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }

        .nx-page-content .btn:hover{
            background: #203D34;
        }

        .nx-page-content .security-note{
            background: #F5F7F3;
            border-left: 3px solid var(--teal);
            border-radius: 5px;
            padding: 14px;
            margin-top: 22px;
            font-size: 13px;
            color: #53615A;
        }

        .nx-page-content .success-message{
            background: #e8f7ef;
            border: 1px solid #a9dfc2;
            color: #236844;
            padding: 14px 18px;
            border-radius: 9px;
            margin-bottom: 22px;
            font-size: 14px;
        }

        .nx-page-content .error-message{
            color: #d62828;
            font-size: 12px;
        }

        @media (max-width: 700px) {
            .nx-page-content .container{
                margin-top: 25px;
            }

            .nx-page-content .card{
                padding: 20px;
            }

            .nx-page-content .form-grid{
                grid-template-columns: 1fr;
            }

            .nx-page-content .form-group.full{
                grid-column: auto;
            }

            .nx-page-content .page-heading h1{
                font-size: 25px;
            }

            .nx-page-content .topbar{
                padding: 16px 20px;
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

    <style>
        /* Manager Profile: page-specific layout refinements */
        .nx-page-content { min-height: 100%; background: #F5F7F3; }
        .nx-page-content .topbar { padding: 18px clamp(20px, 5%, 64px); border-bottom: 1px solid #E2E8E2; }
        .nx-page-content .brand { font-family: 'Manrope', sans-serif; letter-spacing: -.04em; }
        .nx-page-content .container[data-nx-page-main] { width: 100%; max-width: 1100px; margin: 0 auto; padding: 36px 28px 64px; }
        .nx-page-content .page-heading { margin-bottom: 28px; }
        .nx-page-content .page-heading h1 { font-family: 'Manrope', sans-serif; font-weight: 800; color: #152A23; font-size: clamp(25px, 3vw, 32px); }
        .nx-page-content .page-heading p { line-height: 1.6; }
        .nx-page-content section.card { padding: 32px; margin-bottom: 24px; border: 1px solid #E2E8E2; border-radius: 18px; box-shadow: 0 5px 20px rgba(21,42,35,.045); }
        .nx-page-content .profile-header { gap: 20px; }
        .nx-page-content .avatar { overflow: hidden; border: 2px solid #DCE6DC; background: #EDF2EC; }
        .nx-page-content .profile-header h2, .nx-page-content .section-title { font-family: 'Manrope', sans-serif; color: #152A23; font-weight: 750; }
        .nx-page-content .profile-divider { margin: 28px 0; background: #E9EEE9; }
        .nx-page-content .section-description { margin-bottom: 26px; line-height: 1.6; }
        .nx-page-content .form-grid { gap: 22px 24px; }
        .nx-page-content .form-group { min-width: 0; gap: 9px; }
        .nx-page-content .form-group label { color: #1D2924; font-size: 13px; }
        .nx-page-content .form-group input { min-height: 46px; border: 1px solid #C9D4CC; border-radius: 11px; padding: 11px 14px; transition: border-color .2s, box-shadow .2s; }
        .nx-page-content .form-group input:focus { border-color: #315C4D; box-shadow: 0 0 0 3px rgba(49,92,77,.15); }
        .nx-page-content .form-group input[type=file] { padding: 10px 12px; }
        .nx-page-content .hint { line-height: 1.5; }
        .nx-page-content .actions { margin-top: 28px; }
        .nx-page-content .actions .btn { min-height: 44px; border-radius: 999px; padding: 11px 22px; background: #315C4D; color: #fff; font-family: 'Manrope', sans-serif; font-weight: 700; }
        .nx-page-content .actions .btn:hover { background: #274B3F; color: #fff; }
        .nx-page-content .security-note { margin-top: 24px; padding: 16px 18px; border-radius: 10px; background: #F8FAF7; line-height: 1.6; }
        .nx-page-content .success-message { border-radius: 12px; }
        @media (max-width: 767px) {
            .nx-page-content .container[data-nx-page-main] { padding: 24px 16px 44px; }
            .nx-page-content section.card { padding: 22px 18px; }
            .nx-page-content .form-grid { grid-template-columns: 1fr; gap: 18px; }
            .nx-page-content .form-group.full { grid-column: auto; }
            .nx-page-content .topbar { padding: 15px 18px; }
            .nx-page-content .actions .btn { width: 100%; }
        }
        @media (max-width: 420px) {
            .nx-page-content .profile-header { gap: 14px; }
            .nx-page-content .avatar { width: 62px; height: 62px; font-size: 24px; }
            .nx-page-content .profile-header h2 { font-size: 18px; }
            .nx-page-content .topbar-label { font-size: 12px; }
        }
        /* Compact spacing: avoid duplicated dashboard header and excess whitespace */
.nx-page-content .container[data-nx-page-main] {
    margin: 0 auto;
    padding: 0 22px 24px;
}        .nx-page-content .page-heading { margin-bottom: 16px; }
        .nx-page-content .page-heading h1 { margin-bottom: 4px; font-size: clamp(23px, 2.5vw, 28px); }
        .nx-page-content section.card { padding: 22px 24px; margin-bottom: 16px; }
        .nx-page-content .profile-header { gap: 14px; }
        .nx-page-content .avatar { width: 64px; height: 64px; font-size: 25px; }
        .nx-page-content .profile-divider { margin: 18px 0; }
        .nx-page-content .section-title { margin-bottom: 5px; }
        .nx-page-content .section-description { margin-bottom: 16px; }
        .nx-page-content .form-grid { gap: 15px 18px; }
        .nx-page-content .form-group { gap: 6px; }
        .nx-page-content .actions { margin-top: 18px; }
        .nx-page-content .security-note { margin-top: 16px; padding: 12px 14px; }
        @media (max-width: 767px) {
            .nx-page-content .container[data-nx-page-main] { padding: 10px 12px 20px; }
            .nx-page-content section.card { padding: 18px 16px; margin-bottom: 14px; }
            .nx-page-content .form-grid { gap: 14px; }
        }
    </style>
@endpush

@section('content')
<div class="nx-page-content">
<div data-nx-page-main class="container">

    <div class="page-heading">
        <h1>My Profile</h1>
        <p>Manage your personal and business information.</p>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <!-- PERSONAL PROFILE -->
    <section class="card">

        <div class="profile-header">
            <div class="avatar">
                @if($manager->avatar)
                    <img src="{{ asset('storage/' . $manager->avatar) }}"
                         alt="Profile Photo"
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr($manager->name, 0, 1)) }}
                @endif
            </div>

            <div>
                <h2>{{ $manager->name }}</h2>
                <p>Space Manager</p>
            </div>
        </div>

        <div class="profile-divider"></div>

        <h2 class="section-title">Personal Information</h2>
        <p class="section-description">
            Update your personal contact details.
        </p>

        <form action="{{ route('manager.profile.updatePersonal') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label for="name">Full Name</label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name', $manager->name) }}">

                    @error('name')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>

                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $manager->email) }}">

                    @error('email')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>

                    <input type="tel"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $manager->phone) }}">

                    @error('phone')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="avatar">Profile Photo</label>

                    <input type="file"
                           id="avatar"
                           name="avatar"
                           accept="image/png,image/jpeg,image/webp">

                    <span class="hint">JPG, PNG or WEBP.</span>

                    @error('avatar')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>

            <div class="actions">
                <button type="submit" class="btn">
                    Save Personal Information
                </button>
            </div>

        </form>
    </section>

   
    <!-- CHANGE PASSWORD -->
    <section class="card">

        <h2 class="section-title">Change Password</h2>

        <p class="section-description">
            Keep your account secure by updating your password.
        </p>

        <form action="{{ route('manager.profile.updatePassword') }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group full">
                    <label for="current_password">
                        Current Password
                    </label>

                    <input type="password"
                           id="current_password"
                           name="current_password"
                           placeholder="Enter current password">

                    @error('current_password')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">
                        New Password
                    </label>

                    <input type="password"
                           id="password"
                           name="password"
                           placeholder="Enter new password">

                    @error('password')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        Confirm New Password
                    </label>

                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           placeholder="Confirm new password">

                    @error('password_confirmation')
                        <span class="error-message">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>

            <div class="security-note">
                For security, your current password is required
                before changing to a new password.
            </div>

            <div class="actions">
                <button type="submit" class="btn">
                    Update Password
                </button>
            </div>

        </form>
    </section>

</div>
</div>
@endsection
