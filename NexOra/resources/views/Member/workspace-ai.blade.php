@extends('layouts.dashboard')

@section('dashboard-role', 'member')

@section('title') NEXORA | AI Workspace Assistant @endsection
@section('page-title') NEXORA @endsection

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <style>
        
/* =====================================================
   NEXORA — AI WORKSPACE ASSISTANT
   Premium UI Design
   ===================================================== */

.nx-page-content {
    --ai-primary: #315C4D;
    --ai-primary-hover: #274B3F;
    --ai-dark: #203D34;
    --ai-accent: #C58A62;
    --ai-bg: #F5F7F3;
    --ai-surface: #FFFFFF;
    --ai-text: #1D2924;
    --ai-muted: #718078;
    --ai-border: #E2E8E2;

    min-height: 100%;
    background: var(--ai-bg);
    color: var(--ai-text);
    font-family: 'Inter', 'Noto Sans Arabic', sans-serif;
    -webkit-font-smoothing: antialiased;
}

.nx-page-content *,
.nx-page-content *::before,
.nx-page-content *::after {
    box-sizing: border-box;
}

.nx-page-content .page {
    width: min(1120px, calc(100% - 48px));
    margin: 0 auto;
    padding: 42px 0 70px;
}

/* =====================================================
   HERO
   ===================================================== */

.nx-page-content .hero {
    position: relative;
    isolation: isolate;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 245px;
    padding: 42px 48px;
    margin-bottom: 30px;
    border-radius: 25px;
    color: #fff;
    background:
        radial-gradient(circle at 88% 15%, rgba(197,138,98,.23), transparent 28%),
        radial-gradient(circle at 10% 100%, rgba(111,128,110,.28), transparent 35%),
        linear-gradient(120deg, #152A23 0%, #203D34 55%, #315C4D 100%);
    box-shadow: 0 16px 40px rgba(32,61,52,.15);
}

.nx-page-content .hero::before {
    content: "";
    position: absolute;
    z-index: -1;
    width: 280px;
    height: 280px;
    right: -75px;
    bottom: -155px;
    border: 1px solid rgba(255,255,255,.13);
    border-radius: 50%;
    box-shadow:
        0 0 0 28px rgba(255,255,255,.035),
        0 0 0 58px rgba(255,255,255,.025);
}

.nx-page-content .hero::after {
    content: "✦";
    position: absolute;
    top: 38px;
    right: 48px;
    color: var(--ai-accent);
    font-size: 42px;
    opacity: .85;
}

.nx-page-content .hero h1 {
    max-width: 700px;
    margin: 0 0 15px;
    color: #fff;
    font-family: 'Manrope', sans-serif;
    font-size: clamp(28px, 4vw, 39px);
    font-weight: 800;
    letter-spacing: -1.3px;
    line-height: 1.2;
}

.nx-page-content .hero p {
    max-width: 650px;
    margin: 0;
    color: rgba(255,255,255,.78);
    font-size: 15px;
    line-height: 1.9;
}

/* =====================================================
   MAIN PANEL
   ===================================================== */

.nx-page-content .panel {
    position: relative;
    padding: 36px;
    border: 1px solid rgba(226,232,226,.9);
    border-radius: 22px;
    background: #fff;
    box-shadow: 0 8px 30px rgba(29,41,36,.045);
}

.nx-page-content .panel > h2,
.nx-page-content #results > h2 {
    position: relative;
    margin: 0 0 28px;
    color: var(--ai-dark);
    font-family: 'Manrope', sans-serif;
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -.5px;
}

.nx-page-content .panel > h2::after {
    content: "";
    display: block;
    width: 42px;
    height: 3px;
    margin-top: 12px;
    border-radius: 5px;
    background: var(--ai-accent);
}

/* =====================================================
   FORM GRID & FIELDS
   ===================================================== */

.nx-page-content .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 22px;
}

.nx-page-content .field {
    min-width: 0;
    margin-bottom: 24px;
}

.nx-page-content .field > label {
    display: block;
    margin-bottom: 11px;
    color: var(--ai-text);
    font-size: 13px;
    font-weight: 700;
}

.nx-page-content input[type="number"] {
    width: 100%;
    min-height: 53px;
    padding: 0 16px;
    border: 1px solid var(--ai-border);
    border-radius: 12px;
    outline: none;
    background: #FAFCF9;
    color: var(--ai-text);
    font-family: inherit;
    font-size: 14px;
    font-weight: 500;
    transition: border-color .25s ease, box-shadow .25s ease, background .25s ease;
}

.nx-page-content input[type="number"]:hover {
    border-color: #A8B9AA;
    background: #fff;
}

.nx-page-content input[type="number"]:focus {
    border-color: var(--ai-primary);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(49,92,77,.10);
}

.nx-page-content input::placeholder {
    color: #A0AAA3;
    font-weight: 400;
}

/* =====================================================
   EQUIPMENT OPTIONS
   ===================================================== */

.nx-page-content .equipment-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
    margin-top: 14px;
}

.nx-page-content .equipment-option {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 58px;
    padding: 13px 15px;
    border: 1px solid var(--ai-border);
    border-radius: 13px;
    background: #FAFCF9;
    cursor: pointer;
    transition: border-color .25s ease, background .25s ease, transform .25s ease, box-shadow .25s ease;
}

.nx-page-content .equipment-option:hover {
    transform: translateY(-2px);
    border-color: #A8B9AA;
    background: #F4F8F3;
}

.nx-page-content .equipment-option:has(input:checked) {
    border-color: var(--ai-primary);
    background: #EDF4EE;
    box-shadow: 0 0 0 2px rgba(49,92,77,.07);
}

.nx-page-content .equipment-option input[type="checkbox"] {
    width: 17px;
    height: 17px;
    flex: 0 0 17px;
    margin: 0;
    accent-color: var(--ai-primary);
    cursor: pointer;
}

.nx-page-content .equipment-option label {
    margin: 0;
    color: #43534A;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.4;
    cursor: pointer;
}

.nx-page-content .equipment-option:has(input:checked) label {
    color: var(--ai-dark);
}

.nx-page-content .field small {
    display: block;
    margin-top: 13px;
    color: var(--ai-muted);
    font-size: 12px;
    line-height: 1.6;
}

/* =====================================================
   SUBMIT BUTTON
   ===================================================== */

.nx-page-content .submit-btn {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    width: 100%;
    min-height: 56px;
    margin-top: 5px;
    padding: 14px 24px;
    overflow: hidden;
    border: 0;
    border-radius: 13px;
    background: linear-gradient(120deg, #315C4D, #203D34);
    color: #fff;
    font-family: 'Manrope', sans-serif;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: .1px;
    cursor: pointer;
    box-shadow: 0 7px 17px rgba(49,92,77,.17);
    transition: transform .25s ease, box-shadow .25s ease, background .25s ease;
}

.nx-page-content .submit-btn::after {
    content: "→";
    font-size: 19px;
    font-weight: 400;
    transition: transform .25s ease;
}

.nx-page-content .submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    background: linear-gradient(120deg, #274B3F, #152A23);
    box-shadow: 0 11px 24px rgba(49,92,77,.24);
}

.nx-page-content .submit-btn:hover:not(:disabled)::after {
    transform: translateX(4px);
}

.nx-page-content .submit-btn:active:not(:disabled) {
    transform: scale(.99);
}

.nx-page-content .submit-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
    box-shadow: none;
}

/* =====================================================
   LOADING
   ===================================================== */

.nx-page-content #loading {
    display: none;
    margin-top: 22px;
    padding: 25px;
    border: 1px solid #DCE8DD;
    border-radius: 15px;
    background: #F3F8F3;
    text-align: center;
}

.nx-page-content #loading p {
    margin: 0;
    color: var(--ai-primary);
    font-size: 13px;
    font-weight: 600;
    line-height: 1.7;
}

.nx-page-content #loading p::before {
    content: "";
    display: block;
    width: 27px;
    height: 27px;
    margin: 0 auto 13px;
    border: 3px solid #D5E3D7;
    border-top-color: var(--ai-primary);
    border-radius: 50%;
    animation: nx-ai-spin .8s linear infinite;
}

@keyframes nx-ai-spin {
    to { transform: rotate(360deg); }
}

/* =====================================================
   ERROR MESSAGE
   ===================================================== */

.nx-page-content .error {
    display: none;
    margin-top: 20px;
    padding: 15px 18px;
    border: 1px solid #F2C8C5;
    border-radius: 12px;
    background: #FFF5F4;
    color: #A32922;
    font-size: 13px;
    line-height: 1.7;
    white-space: pre-line;
}

/* =====================================================
   RESULTS SECTION
   ===================================================== */

.nx-page-content #results {
    display: none;
    margin-top: 38px;
    animation: nx-ai-appear .45s ease both;
}

@keyframes nx-ai-appear {
    from {
        opacity: 0;
        transform: translateY(12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.nx-page-content #results > h2 {
    margin-bottom: 20px;
}

.nx-page-content #results > h2:not(:first-child) {
    margin-top: 34px;
}

/* AI answer */
.nx-page-content .ai-answer {
    position: relative;
    padding: 25px 27px;
    border: 1px solid #DCE7DD;
    border-left: 4px solid var(--ai-accent);
    border-radius: 15px;
    background:
        radial-gradient(circle at 100% 0%, rgba(197,138,98,.08), transparent 35%),
        #F5F8F4;
    color: #35473D;
    font-size: 14px;
    line-height: 1.95;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.nx-page-content .ai-answer::before {
    content: "AI INSIGHT";
    display: block;
    margin-bottom: 12px;
    color: var(--ai-primary);
    font-family: 'Manrope', sans-serif;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
}

/* =====================================================
   ROOM RECOMMENDATION CARDS
   ===================================================== */

.nx-page-content .room-card {
    position: relative;
    padding: 25px;
    margin-bottom: 16px;
    overflow: hidden;
    border: 1px solid var(--ai-border);
    border-radius: 17px;
    background: #fff;
    box-shadow: 0 4px 15px rgba(29,41,36,.035);
    transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
}

.nx-page-content .room-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--ai-accent);
    opacity: .8;
}

.nx-page-content .room-card:hover {
    transform: translateY(-3px);
    border-color: #C7D7C9;
    box-shadow: 0 12px 27px rgba(29,41,36,.075);
}

.nx-page-content .room-card h3 {
    margin: 0 0 10px;
    color: var(--ai-dark);
    font-family: 'Manrope', sans-serif;
    font-size: 19px;
    font-weight: 800;
}

.nx-page-content .room-card > p {
    margin: 0;
    color: var(--ai-muted);
    font-size: 13px;
    line-height: 1.7;
}

.nx-page-content .room-card > p strong {
    color: var(--ai-text);
}

.nx-page-content .room-details {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin-top: 20px;
    padding-top: 18px;
    border-top: 1px solid #EDF0EC;
    color: var(--ai-muted);
    font-size: 13px;
    line-height: 1.7;
}

.nx-page-content .room-details > div {
    min-width: 0;
    padding: 12px 14px;
    border-radius: 10px;
    background: #F7F9F6;
    overflow-wrap: anywhere;
}

.nx-page-content .room-details strong {
    display: block;
    margin-bottom: 4px;
    color: #52645A;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .45px;
}

/* Empty results message */
.nx-page-content #roomsList > p {
    padding: 22px;
    border: 1px dashed #D5DFD5;
    border-radius: 13px;
    background: #F8FAF7;
    color: var(--ai-muted);
    font-size: 13px;
    line-height: 1.8;
    text-align: center;
}

/* =====================================================
   RESPONSIVE
   ===================================================== */

@media (max-width: 850px) {
    .nx-page-content .page {
        width: min(100% - 36px, 720px);
        padding-top: 30px;
    }

    .nx-page-content .hero {
        min-height: 220px;
        padding: 32px;
    }

    .nx-page-content .panel {
        padding: 27px;
    }

    .nx-page-content .equipment-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 600px) {
    .nx-page-content .page {
        width: calc(100% - 28px);
        padding: 22px 0 45px;
    }

    .nx-page-content .hero {
        min-height: 205px;
        padding: 27px 23px;
        margin-bottom: 20px;
        border-radius: 19px;
    }

    .nx-page-content .hero::after {
        top: 20px;
        right: 22px;
        font-size: 29px;
    }

    .nx-page-content .hero h1 {
        max-width: 90%;
        font-size: 27px;
    }

    .nx-page-content .hero p {
        font-size: 13px;
        line-height: 1.8;
    }

    .nx-page-content .panel {
        padding: 22px 18px;
        border-radius: 17px;
    }

    .nx-page-content .panel > h2,
    .nx-page-content #results > h2 {
        font-size: 19px;
    }

    .nx-page-content .form-grid,
    .nx-page-content .equipment-grid,
    .nx-page-content .room-details {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .nx-page-content .field {
        margin-bottom: 20px;
    }

    .nx-page-content .equipment-option {
        min-height: 52px;
    }

    .nx-page-content .submit-btn {
        min-height: 54px;
        font-size: 13px;
    }

    .nx-page-content .ai-answer {
        padding: 20px;
        font-size: 13px;
    }

    .nx-page-content .room-card {
        padding: 21px 18px;
    }

    .nx-page-content .room-card h3 {
        font-size: 17px;
    }

    .nx-page-content .room-details {
        gap: 9px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .nx-page-content *,
    .nx-page-content *::before,
    .nx-page-content *::after {
        animation-duration: .01ms !important;
        transition-duration: .01ms !important;
    }
}

  
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
<div class="page">

    <section class="hero">
        <h1>AI Workspace Assistant</h1>

        <p>
            Find the perfect workspace for your next meeting.
            Tell us your meeting size, required equipment,
            and budget. Our AI assistant will help you choose
            the most suitable rooms.
        </p>
    </section>

    <section class="panel">

        <h2>Tell us what you need</h2>

        <form id="workspaceAIForm"
              data-url="{{ route('member.workspace-ai.recommend') }}">

            <div class="form-grid">

                <div class="field">
                    <label for="attendees">Meeting size</label>

                    <input
                        type="number"
                        id="attendees"
                        name="attendees"
                        min="1"
                        max="100"
                        placeholder="Number of people"
                        required
                    >
                </div>

                <div class="field">
                    <label for="budget">Maximum hourly budget</label>

                    <input
                        type="number"
                        id="budget"
                        name="budget"
                        min="0.01"
                        step="0.01"
                        placeholder="Enter your budget"
                        required
                    >
                </div>

            </div>

            <div class="field">

                <label>Required equipment</label>

                <div class="equipment-grid">

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="Projector">
                        <label>Projector</label>
                    </div>

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="Whiteboard">
                        <label>Whiteboard</label>
                    </div>

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="WiFi">
                        <label>WiFi</label>
                    </div>

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="Screen">
                        <label>Screen</label>
                    </div>

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="TV">
                        <label>TV</label>
                    </div>

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="Video Conference">
                        <label>Video Conference</label>
                    </div>

                </div>

                <small>
                    Select all equipment required for your meeting.
                </small>

            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                Find My Perfect Workspace
            </button>

        </form>

        <div id="loading">
            <p>AI is analyzing your requirements and finding suitable rooms...</p>
        </div>

        <div id="errorMessage" class="error"></div>

    </section>

    <section id="results">

        <h2>AI Recommendations</h2>

        <div id="aiAnswer" class="ai-answer"></div>

        <h2>Matching Rooms</h2>

        <div id="roomsList"></div>

    </section>

</div>
</div>
@endsection

@push('scripts')
<script>

const form = document.getElementById('workspaceAIForm');

const loading = document.getElementById('loading');
const results = document.getElementById('results');
const aiAnswer = document.getElementById('aiAnswer');
const roomsList = document.getElementById('roomsList');
const errorMessage = document.getElementById('errorMessage');
const submitBtn = document.getElementById('submitBtn');

function escapeHTML(value) {
    return String(value ?? '').replace(/[&<>"']/g, function (char) {
        const entities = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return entities[char];
    });
}

form.addEventListener('submit', async function (event) {

    event.preventDefault();

    loading.style.display = 'block';
    results.style.display = 'none';
    errorMessage.style.display = 'none';

    submitBtn.disabled = true;
    submitBtn.textContent = 'Analyzing...';

    const formData = new FormData(form);

    try {

        const response = await fetch(form.dataset.url, {

            method: 'POST',

            headers: {
                'X-CSRF-TOKEN': document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,

                'Accept': 'application/json'
            },

            body: formData

        });

        const data = await response.json();

        if (!response.ok) {

            if (response.status === 422 && data.errors) {
                const messages = Object.values(data.errors).flat();
                throw new Error(messages.join('\n'));
            }

            throw new Error(
                data.message || 'Something went wrong.'
            );
        }

        aiAnswer.textContent = data.answer || 'No AI explanation received.';

        displayRooms(data.rooms || []);

        results.style.display = 'block';

    } catch (error) {

        errorMessage.textContent = error.message;
        errorMessage.style.display = 'block';

    } finally {

        loading.style.display = 'none';

        submitBtn.disabled = false;
        submitBtn.textContent = 'Find My Perfect Workspace';

    }

});


function displayRooms(rooms) {

    roomsList.innerHTML = '';

    if (rooms.length === 0) {

        roomsList.innerHTML = `
            <p>No matching rooms were found.
               Try increasing your budget or changing your requirements.</p>
        `;

        return;
    }

    rooms.forEach(function (room) {

        const facilities = Array.isArray(room.facilities)
            ? room.facilities.map(escapeHTML).join(', ')
            : 'Not specified';

        const card = document.createElement('div');

        card.className = 'room-card';

        card.innerHTML = `
            <h3>${escapeHTML(room.room_name)}</h3>

            <p>
                <strong>Workspace:</strong>
                ${escapeHTML(room.workspace_name)}
            </p>

            <div class="room-details">

                <div>
                    <strong>Location:</strong>
                    ${escapeHTML(room.location || 'Not specified')}
                </div>

                <div>
                    <strong>Room type:</strong>
                    ${escapeHTML(room.room_type || 'Not specified')}
                </div>

                <div>
                    <strong>Capacity:</strong>
                    ${escapeHTML(room.capacity)} people
                </div>

                <div>
                    <strong>Hourly price:</strong>
                    ${Number(room.hourly_price).toFixed(2)}
                </div>

                <div>
                    <strong>Facilities:</strong>
                    ${facilities}
                </div>

            </div>
        `;

        roomsList.appendChild(card);

    });

}

</script>
@endpush
