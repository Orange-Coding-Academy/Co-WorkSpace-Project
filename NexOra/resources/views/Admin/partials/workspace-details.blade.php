{{-- Shared Admin workspace-details page content (sections/stacks). Used by Admin.WorkspaceDetails and Admin.MangeWorkSpace. --}}
@section('title') {{ $workspace->name }} | Workspace details @endsection
@section('page-title') {{ $workspace->name }} @endsection

@push('styles')
    <style>
        /* Use the complete dashboard canvas with one consistent small gutter. */
        .nx-dashboard__main:has(> .nx-page-content){
            max-width: none;
            margin: 0;
            padding: clamp(12px, 1.25vw, 20px);
        }

        .nx-page-content *{
            box-sizing: border-box;
        }

        .nx-page-content{
            margin: 0;
            background: #F5F7F3;
            color: #1D2924;
            font: 1rem/1.5 system-ui, sans-serif;
        }

        .nx-page-content [data-nx-page-main]{
            width: 100%;
            max-width: 1800px;
            margin: auto;
            padding: 0;
        }

        .nx-page-content .back{
            display: inline-block;
            margin-bottom: 18px;
            color: #315C4D;
            font-weight: 650;
            text-decoration: none;
        }

        .nx-page-content .back:hover{
            text-decoration: underline;
        }

        .nx-page-content .workspace-breadcrumb{
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 7px;
            margin-bottom: 10px;
            color: #718078;
            font-size: .86rem;
        }

        .nx-page-content .workspace-breadcrumb a{
            color: #285B4B;
            font-weight: 700;
            text-decoration: none;
        }

        .nx-page-content .workspace-breadcrumb strong{ color: #1D2925; }

        .nx-page-content :focus-visible{
            outline: 2px solid #315C4D;
            outline-offset: 3px;
        }

        .nx-page-content .card{
            background: #fff;
            border: 1px solid #E9EEE9;
            border-radius: 20px;
        }

        .nx-page-content .card.overview{
            position: relative;
            isolation: isolate;
            overflow: hidden;
            display: grid;
            gap: clamp(20px, 3vw, 30px);
            align-items: center;
            padding: clamp(22px, 3vw, 32px);
            border: 1px solid #315C4D;
            border-radius: 22px;
            background: #315C4D;
            color: #fff;
            box-shadow: 0 14px 32px rgba(40, 91, 75, .18);
            transition: transform .28s ease, background-color .28s ease, box-shadow .28s ease;
        }

        .nx-page-content .overview::before,
        .nx-page-content .overview::after{
            position: absolute;
            z-index: -1;
            content: '';
            border-radius: 50%;
            pointer-events: none;
            transition: transform .42s ease;
        }

        .nx-page-content .overview::before{
            top: -150px;
            right: 7%;
            width: 245px;
            height: 245px;
            background: rgba(255, 255, 255, .10);
        }

        .nx-page-content .overview::after{
            right: 25%;
            bottom: -122px;
            width: 165px;
            height: 165px;
            background: rgba(197, 138, 98, .24);
        }

        .nx-page-content .overview > *{ position: relative; z-index: 1; }

        .nx-page-content .overview:has(img:not([hidden])){
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.35fr);
        }

        .nx-page-content .cover{
            display: block;
            order: 2;
            width: 100%;
            aspect-ratio: 16 / 10;
            border-radius: 14px;
            object-fit: cover;
            box-shadow: 0 12px 24px rgba(8, 32, 25, .24);
            transition: transform .35s ease;
        }

        .nx-page-content .cover[hidden]{
            display: none;
        }

        @media (hover: hover) {
            .nx-page-content .overview:hover{
                background: #274B3F;
                box-shadow: 0 20px 42px rgba(39, 75, 63, .24);
                transform: translateY(-3px);
            }

            .nx-page-content .overview:hover::before{ transform: translate(-10px, 10px) scale(1.05); }
            .nx-page-content .overview:hover::after{ transform: translate(8px, -8px) scale(1.08); }
            .nx-page-content .overview:hover .cover{ transform: scale(1.025); }
        }

        .nx-page-content h1,
        .nx-page-content h2,
        .nx-page-content p,
        .nx-page-content dl,
        .nx-page-content dd{
            margin: 0;
        }

        .nx-page-content .overview h1{
            font-size: clamp(1.65rem, 4vw, 2.35rem);
            line-height: 1.15;
            overflow-wrap: anywhere;
            color: #fff;
        }

        .nx-page-content h2{
            font-size: 1.25rem;
        }

        .nx-page-content .description{
            margin-top: 16px;
            white-space: pre-line;
            overflow-wrap: anywhere;
            color: #E3EFE8;
        }

        .nx-page-content .facts{
            display: flex;
            flex-wrap: wrap;
            gap: 12px 28px;
            margin-top: 18px;
        }

        .nx-page-content .overview dt{
            color: #CFE1D8;
            font-size: .85rem;
        }

        .nx-page-content .overview dd{
            font-weight: 600;
            color: #fff;
        }

        .nx-page-content .overview .pill{
            display: inline-block;
            padding: 3px 9px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .18);
            background: rgba(255, 255, 255, .14);
            color: #fff;
            font-size: .84rem;
            font-weight: 650;
        }

        .nx-page-content .overview .active{
            background: #def5eb;
            color: #16634d;
            border-color: transparent;
        }

        .nx-page-content .pending{
            background: #fff0d5;
            color: #814c00;
        }

        .nx-page-content .rooms{
            margin-top: 24px;
        }

        .nx-page-content .rooms-header{
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nx-page-content .room-grid{
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 260px), 1fr));
            gap: 16px;
        }

        /* Match the manager view: one room becomes a balanced, full-width feature card. */
        .nx-page-content .room-grid:has(.room-card:only-child){
            grid-template-columns: 1fr;
        }

        .nx-page-content .room-card:only-child{
            display: grid;
            grid-template-columns: minmax(300px, .9fr) minmax(0, 1.1fr);
            min-height: 300px;
        }

        .nx-page-content .room-card:only-child .room-media{
            min-height: 300px;
            aspect-ratio: auto;
        }

        .nx-page-content .room-card:only-child .room-body{
            padding: 24px;
        }

        .nx-page-content .room-card{
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .nx-page-content .room-media{
            position: relative;
            display: grid;
            place-items: center;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background: radial-gradient(circle at 75% 20%, #fff8, transparent 55%), #F5F7F3;
            color: #315C4D;
        }

        .nx-page-content .room-media img{
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .nx-page-content .room-media img[hidden]{
            display: none;
        }

        .nx-page-content .room-placeholder{
            font-size: 2.5rem;
            font-weight: 750;
        }

        .nx-page-content .room-body{
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 16px;
        }

        .nx-page-content .room-heading{
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 8px;
        }

        .nx-page-content .room-heading .pill{
            flex: none;
        }

        .nx-page-content .room-body h3{
            margin: 4px 0 14px;
            font-size: 1.08rem;
            overflow-wrap: anywhere;
        }

        .nx-page-content .room-type{
            color: #53615A;
            font-size: .85rem;
        }

        .nx-page-content .room-facts{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #E9EEE9;
        }

        .nx-page-content .empty{
            padding: 20px;
            color: #53615A;
        }

        @media (hover: hover) {
            .nx-page-content .room-card:hover{
                border-color: #E7EEE7;
                box-shadow: 0 5px 16px #211a3510;
            }
        }

        @media (max-width: 620px) {
            .nx-page-content .overview:has(img:not([hidden])){
                grid-template-columns: 1fr;
            }

            .nx-page-content .room-card:only-child{
                grid-template-columns: 1fr;
            }

            .nx-page-content .room-card:only-child .room-media{
                min-height: 0;
                aspect-ratio: 16 / 9;
            }
        }
        .nx-page-content .room-image-button{
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border: 1px solid #ffffff80;
            border-radius: 50%;
            background: #211a35cc;
            color: #fff;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
        }

        .nx-page-content .room-image-button.previous{ left: 10px; }
        .nx-page-content .room-image-button.next{ right: 10px; }
        .nx-page-content .room-image-button:hover{ background: #1D2924; }
        .nx-page-content .room-image-button:focus-visible{ outline-color: #fff; }
        .nx-page-content .workspace-facilities{
            margin-top: 20px;
        }

        .nx-page-content .workspace-facilities h2{
            font-size: .95rem;
            color: #fff;
        }

        .nx-page-content .workspace-facilities-list{
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 8px 0 0;
            padding: 0;
            list-style: none;
            color: #E3EFE8;
            font-size: .85rem;
        }
        .nx-page-content .approve-workspace{
            margin-top: 18px;
            padding: 10px 16px;
            border: 0;
            border-radius: 10px;
            background: #315C4D;
            color: #fff;
            font: inherit;
            font-weight: 650;
            cursor: pointer;
        }

        .nx-page-content .approve-workspace:hover:enabled{
            background: #274B3F;
        }
        .nx-page-content .approve-workspace:disabled{
            background: #def5eb;
            color: #16634d;
            cursor: default;
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
    <link rel="stylesheet" href="{{ asset('css/nexora/workspace-pages.css') }}">
@endpush

@section('content')
<div class="nx-page-content nx-workspace-ui nx-workspace-details">
    <div data-nx-page-main>

        <nav class="workspace-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('admin.AdminDashboard') }}">Admin</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('admin.workspaces') }}">Workspace directory</a>
            <span aria-hidden="true">/</span>
            <strong>{{ $workspace->name }}</strong>
        </nav>

        <a class="back" href="{{ route('admin.workspaces') }}">&larr; Workspace directory</a>

        <section class="card overview" aria-labelledby="workspace-title">

            {{-- Workspace cover image --}}
          @php
    $coverImage = $workspace->images->first();
@endphp

@if ($coverImage)
    <img
        class="cover"
        src="{{ asset('storage/' . ltrim($coverImage->image_path, '/')) }}"
        alt="{{ $coverImage->alt_text ?? $workspace->name }}"
        onerror="this.style.display='none';"
    >
@endif
            <div>
                <h1 id="workspace-title">{{ $workspace->name }}</h1>

                @if (filled($workspace->description))
                    <p class="description">{{ $workspace->description }}</p>
                @endif

                <dl class="facts">
                    <div>
                        <dt>Location</dt>
                        <dd>{{ $workspace->location ?: 'Unavailable' }}</dd>
                    </div>

                    <div>
                        <dt>Manager</dt>
                        <dd>{{ $workspace->manager?->full_name ?? $workspace->manager?->name ?? 'Unassigned' }}</dd>
                    </div>

                    <div>
                        <dt>Status</dt>
                        <dd>
                            @if (! $workspace->is_active)
                                <span class="pill">Disabled</span>
                            @elseif ($workspace->approval_status === 'approved')
                                <span class="pill active">Active</span>
                            @elseif ($workspace->approval_status === 'pending')
                                <span class="pill pending">Pending review</span>
                            @else
                                <span class="pill">{{ ucfirst($workspace->approval_status) }}</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                <section class="workspace-facilities" aria-labelledby="facilities-title">
                    <h2 id="facilities-title">Facilities</h2>

                    <ul class="workspace-facilities-list" role="list">
                        @forelse ($workspace->facilities ?? [] as $facility)
                            <li><span class="pill">{{ $facility }}</span></li>
                        @empty
                            <li>No facilities listed.</li>
                        @endforelse
                    </ul>
                </section>

                <form method="POST" action="{{ route('admin.UpdateStatus', $workspace) }}">
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="approve-workspace"
                        @if ($workspace->approval_status === 'approved') disabled @endif
                    >
                        {{ $workspace->approval_status === 'approved' ? 'Approved' : 'Approve workspace' }}
                    </button>
                </form>
            </div>
        </section>

        <section class="rooms" aria-labelledby="rooms-title">

            <div class="rooms-header">
                <h2 id="rooms-title">Rooms</h2>
                <span class="pill">{{ ($rooms ?? $workspace->rooms)->count() }}</span>
            </div>

            <div class="room-grid">

                @forelse (($rooms ?? $workspace->rooms) as $room)

                    <article class="card room-card">

                        <div class="room-media">

                            <span class="room-placeholder" aria-hidden="true">
                                {{ mb_strtoupper(mb_substr($room->name, 0, 1)) }}
                            </span>

                            {{-- Room images --}}
                            @foreach ($room->images as $image)

                                @php
    $roomImagePath = ltrim($image->image_path, '/');

    if (str_starts_with($roomImagePath, 'storage/')) {
        $roomImagePath = substr($roomImagePath, strlen('storage/'));
    }
@endphp

                               <img
    src="{{ asset('storage/' . $roomImagePath) }}"
    alt="{{ $image->alt_text ?: $room->name }}"
    loading="lazy"
    onerror="this.style.display='none';"
    @if (! $loop->first) hidden @endif
>

                            @endforeach

                            @if ($room->images->count() > 1)
                                <button
                                    type="button"
                                    class="room-image-button previous"
                                    data-image-step="-1"
                                    aria-label="Previous image for {{ $room->name }}"
                                >&lsaquo;</button>

                                <button
                                    type="button"
                                    class="room-image-button next"
                                    data-image-step="1"
                                    aria-label="Next image for {{ $room->name }}"
                                >&rsaquo;</button>
                            @endif

                        </div>

                        <div class="room-body">

                            <div class="room-heading">
                                <p class="room-type">
                                    {{ ucfirst(str_replace('_', ' ', $room->room_type)) }}
                                </p>

                                <span @class(['pill', 'active' => $room->is_active])>
                                    {{ $room->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </div>

                            <h3>{{ $room->name }}</h3>

                            <dl class="room-facts">
                                <div>
                                    <dt>Capacity</dt>
                                    <dd>{{ $room->capacity }}</dd>
                                </div>

                                <div>
                                    <dt>Hourly price</dt>
                                    <dd>{{ number_format($room->hourly_price, 2) }}</dd>
                                </div>
                            </dl>

                        </div>
                    </article>

                @empty
                    <p class="card empty">No rooms have been added yet.</p>
                @endforelse

            </div>
        </section>

    </div>
</div>
@endsection


@push('scripts')
    <script>
        for (const gallery of document.querySelectorAll('.room-media')) {
            const images = gallery.querySelectorAll('img');
            let current = 0;

            for (const button of gallery.querySelectorAll('[data-image-step]')) {
                button.addEventListener('click', () => {
                    images[current].hidden = true;
                    current = (current + Number(button.dataset.imageStep) + images.length) % images.length;
                    images[current].hidden = false;
                });
            }
        }
    </script>
@endpush
