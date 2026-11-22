
@extends('layouts.member')

@section('title')
    NEXORA | AI Workspace Assistant
@endsection

@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
/* =========================================================
   NEXORA — AI CONCIERGE
========================================================= */

.nx-page-content {
    --ai-green: #24483B;
    --ai-green-light: #315C4D;
    --ai-deep: #142B23;
    --ai-copper: #C58A62;
    --ai-copper-light: #E5C5AA;
    --ai-cream: #F5F3ED;
    --ai-white: #FFFFFF;
    --ai-text: #202C26;
    --ai-muted: #78847D;
    --ai-border: #E5E8E1;

    min-height: 100%;
    padding: 0;
    background: var(--ai-cream);
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
    width: min(1160px, calc(100% - 52px));
    margin: 0 auto;
    padding: 28px 0 55px;
}

/* ===================== HERO ===================== */

.nx-page-content .hero {
    position: relative;
    isolation: isolate;
    display: grid;
    grid-template-columns: 1.25fr .75fr;
    align-items: center;
    min-height: 270px;
    padding: 40px 46px;
    margin-bottom: 23px;
    overflow: hidden;
    border-radius: 23px;
    color: #fff;
    background:
        radial-gradient(ellipse at 80% 15%, rgba(197,138,98,.22), transparent 35%),
        linear-gradient(120deg, #142B23 0%, #203D34 58%, #315C4D 100%);
    box-shadow: 0 15px 40px rgba(20,43,35,.13);
}

.nx-page-content .hero::before {
    content: "";
    position: absolute;
    z-index: -1;
    width: 410px;
    height: 410px;
    right: -130px;
    top: -190px;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 50%;
    box-shadow:
        0 0 0 35px rgba(255,255,255,.025),
        0 0 0 75px rgba(255,255,255,.02);
}

.nx-page-content .hero::after {
    content: "";
    position: absolute;
    z-index: -1;
    width: 260px;
    height: 260px;
    right: 17%;
    bottom: -205px;
    border: 1px solid rgba(229,197,170,.2);
    border-radius: 50%;
}

.nx-page-content .hero-content {
    position: relative;
    z-index: 2;
}

.nx-page-content .hero-kicker {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 19px;
    color: var(--ai-copper-light);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 2.2px;
    text-transform: uppercase;
}

.nx-page-content .hero-kicker::before {
    content: "";
    width: 23px;
    height: 1px;
    background: var(--ai-copper);
}

.nx-page-content .hero h1 {
    max-width: 570px;
    margin: 0 0 13px;
    color: #fff;
    font-family: 'Manrope', sans-serif;
    font-size: clamp(30px, 4vw, 43px);
    font-weight: 800;
    line-height: 1.13;
    letter-spacing: -1.8px;
}

.nx-page-content .hero h1 span {
    color: var(--ai-copper-light);
}

.nx-page-content .hero p {
    max-width: 490px;
    margin: 0;
    color: rgba(255,255,255,.72);
    font-size: 13px;
    line-height: 1.9;
}

.nx-page-content .hero-visual {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 175px;
}

.nx-page-content .orbit {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 164px;
    height: 164px;
    border: 1px solid rgba(229,197,170,.25);
    border-radius: 50%;
    background: radial-gradient(circle, rgba(197,138,98,.13), transparent 68%);
}

.nx-page-content .orbit::before,
.nx-page-content .orbit::after {
    content: "";
    position: absolute;
    border: 1px solid rgba(255,255,255,.13);
    border-radius: 50%;
}

.nx-page-content .orbit::before {
    inset: 15px;
}

.nx-page-content .orbit::after {
    inset: -17px;
    border-style: dashed;
    border-color: rgba(229,197,170,.24);
    animation: nx-orbit 28s linear infinite;
}

.nx-page-content .orbit-core {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 76px;
    height: 76px;
    border: 1px solid rgba(229,197,170,.4);
    border-radius: 24px;
    background: linear-gradient(145deg, #D7A27D, #A96D49);
    color: #172D24;
    font-size: 30px;
    box-shadow: 0 10px 35px rgba(0,0,0,.2);
    transform: rotate(-8deg);
}

.nx-page-content .orbit-dot {
    position: absolute;
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: var(--ai-copper-light);
    box-shadow: 0 0 14px rgba(229,197,170,.6);
}

.nx-page-content .orbit-dot.one {
    top: 8px;
    right: 35px;
}

.nx-page-content .orbit-dot.two {
    bottom: 21px;
    left: 3px;
}

@keyframes nx-orbit {
    to { transform: rotate(360deg); }
}

/* ===================== INTRO STRIP ===================== */

.nx-page-content .intro-strip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 0 4px;
    margin: 0 0 15px;
}

.nx-page-content .intro-label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--ai-green);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 1.2px;
    text-transform: uppercase;
}

.nx-page-content .intro-label::before {
    content: "";
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--ai-copper);
    box-shadow: 0 0 0 4px rgba(197,138,98,.12);
}

.nx-page-content .intro-note {
    color: var(--ai-muted);
    font-size: 11px;
}

/* ===================== MAIN PANEL ===================== */

.nx-page-content .panel {
    position: relative;
    padding: 30px 34px 28px;
    overflow: hidden;
    border: 1px solid rgba(226,232,226,.9);
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 8px 30px rgba(29,41,36,.045);
}

.nx-page-content .panel-heading {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    padding-bottom: 22px;
    margin-bottom: 25px;
    border-bottom: 1px solid #EDF0EB;
}

.nx-page-content .panel-heading h2 {
    margin: 0 0 6px;
    color: var(--ai-deep);
    font-family: 'Manrope', sans-serif;
    font-size: 21px;
    font-weight: 800;
    letter-spacing: -.7px;
}

.nx-page-content .panel-heading p {
    margin: 0;
    color: var(--ai-muted);
    font-size: 12px;
    line-height: 1.7;
}

.nx-page-content .private-note {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    flex-shrink: 0;
    padding: 8px 11px;
    border: 1px solid #E5EDE5;
    border-radius: 30px;
    background: #F5F8F3;
    color: var(--ai-green);
    font-size: 10px;
    font-weight: 700;
}

/* ===================== FORM ===================== */

.nx-page-content .form-section-label {
    display: flex;
    align-items: center;
    gap: 11px;
    margin: 0 0 17px;
    color: var(--ai-deep);
    font-size: 12px;
    font-weight: 800;
}

.nx-page-content .form-section-label span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 25px;
    height: 25px;
    border-radius: 8px;
    background: #EAF0E9;
    color: var(--ai-green);
    font-size: 11px;
    font-weight: 800;
}

.nx-page-content .form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0,1fr));
    gap: 18px;
}

.nx-page-content .field {
    min-width: 0;
    margin-bottom: 24px;
}

.nx-page-content .field > label {
    display: block;
    margin-bottom: 9px;
    color: #35443B;
    font-size: 12px;
    font-weight: 700;
}

.nx-page-content .field input[type="number"] {
    width: 100%;
    height: 51px;
    padding: 0 15px;
    border: 1px solid #E1E7E0;
    border-radius: 11px;
    outline: none;
    background: #FAFBF8;
    color: var(--ai-text);
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    transition: .25s ease;
}

.nx-page-content .field input[type="number"]:hover {
    border-color: #B8C8B9;
    background: #fff;
}

.nx-page-content .field input[type="number"]:focus {
    border-color: var(--ai-green-light);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(49,92,77,.09);
}

.nx-page-content .field input::placeholder {
    color: #A3ADA5;
    font-weight: 400;
}

/* ===================== EQUIPMENT ===================== */

.nx-page-content .equipment-area {
    margin-top: 1px;
    margin-bottom: 23px;
}

.nx-page-content .equipment-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0,1fr));
    gap: 10px;
}

.nx-page-content .equipment-option {
    position: relative;
    display: flex;
    align-items: center;
    gap: 11px;
    min-height: 55px;
    padding: 11px 13px;
    border: 1px solid #E5E9E3;
    border-radius: 11px;
    background: #FAFBF8;
    cursor: pointer;
    transition: border-color .22s ease, background .22s ease, transform .22s ease, box-shadow .22s ease;
}

.nx-page-content .equipment-option:hover {
    transform: translateY(-2px);
    border-color: #B7C8B9;
    background: #F5F8F3;
}

.nx-page-content .equipment-option:has(input:checked) {
    border-color: var(--ai-green-light);
    background: #EDF4EC;
    box-shadow: inset 0 0 0 1px rgba(49,92,77,.08);
}

.nx-page-content .equipment-option input {
    width: 16px;
    height: 16px;
    flex: 0 0 16px;
    margin: 0;
    accent-color: var(--ai-green);
    cursor: pointer;
}

.nx-page-content .equipment-option label {
    margin: 0;
    color: #59675E;
    font-size: 11px;
    font-weight: 700;
    line-height: 1.4;
    cursor: pointer;
}

.nx-page-content .equipment-option:has(input:checked) label {
    color: var(--ai-deep);
}

.nx-page-content .equipment-help {
    margin: 12px 0 0;
    color: #8A958D;
    font-size: 11px;
}

/* ===================== FORM FOOTER ===================== */

.nx-page-content .form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    padding-top: 20px;
    border-top: 1px solid #EDF0EB;
}

.nx-page-content .form-footer-note {
    display: flex;
    align-items: center;
    gap: 9px;
    color: #849087;
    font-size: 10px;
    line-height: 1.6;
}

.nx-page-content .form-footer-note strong {
    color: var(--ai-green);
}

.nx-page-content .submit-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-width: 245px;
    min-height: 51px;
    padding: 13px 22px;
    overflow: hidden;
    border: 0;
    border-radius: 10px;
    background: linear-gradient(115deg,#315C4D,#203D34);
    color: #fff;
    font-family: 'Manrope', sans-serif;
    font-size: 12px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 7px 17px rgba(32,61,52,.16);
    transition: .25s ease;
}

.nx-page-content .submit-btn::after {
    content: "↗";
    color: var(--ai-copper-light);
    font-size: 17px;
    transition: transform .25s ease;
}

.nx-page-content .submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    background: linear-gradient(115deg,#274B3F,#142B23);
    box-shadow: 0 11px 24px rgba(32,61,52,.23);
}

.nx-page-content .submit-btn:hover:not(:disabled)::after {
    transform: translate(3px,-3px);
}

.nx-page-content .submit-btn:disabled {
    opacity: .65;
    cursor: not-allowed;
}

/* ===================== LOADING ===================== */

.nx-page-content #loading {
    display: none;
    margin-top: 20px;
    padding: 22px;
    border: 1px solid #DFE9DF;
    border-radius: 13px;
    background: #F5F8F3;
    text-align: center;
}

.nx-page-content .loading-orbit {
    position: relative;
    width: 34px;
    height: 34px;
    margin: 0 auto 12px;
    border: 2px solid #DCE7DC;
    border-top-color: var(--ai-green);
    border-right-color: var(--ai-copper);
    border-radius: 50%;
    animation: nx-spin .8s linear infinite;
}

@keyframes nx-spin {
    to { transform: rotate(360deg); }
}

.nx-page-content #loading p {
    margin: 0;
    color: var(--ai-green);
    font-size: 12px;
    font-weight: 700;
}

/* ===================== ERROR ===================== */

.nx-page-content .error {
    display: none;
    margin-top: 18px;
    padding: 14px 17px;
    border: 1px solid #F2C8C5;
    border-radius: 11px;
    background: #FFF5F4;
    color: #A32922;
    font-size: 12px;
    line-height: 1.8;
    white-space: pre-line;
}

/* ===================== RESULTS ===================== */

.nx-page-content #results {
    display: none;
    margin-top: 28px;
    animation: nx-result-in .4s ease both;
}

@keyframes nx-result-in {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

.nx-page-content .results-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.nx-page-content .results-heading h2 {
    margin: 0;
    color: var(--ai-deep);
    font-family: 'Manrope',sans-serif;
    font-size: 19px;
    font-weight: 800;
}

.nx-page-content .results-heading .heading-line {
    height: 1px;
    flex: 1;
    background: #DFE5DC;
}

.nx-page-content .results-heading .result-tag {
    color: var(--ai-copper);
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.2px;
}

.nx-page-content .ai-answer {
    position: relative;
    padding: 24px 26px;
    border: 1px solid #DFE8DD;
    border-left: 3px solid var(--ai-copper);
    border-radius: 13px;
    background:
        radial-gradient(circle at 100% 0,rgba(197,138,98,.08),transparent 34%),
        #F7F9F5;
    color: #405047;
    font-size: 13px;
    line-height: 1.95;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.nx-page-content .ai-answer::before {
    content: "NEXORA INTELLIGENCE";
    display: block;
    margin-bottom: 10px;
    color: var(--ai-green);
    font-family: 'Manrope',sans-serif;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: 1.7px;
}

/* ===================== ROOM CARDS ===================== */

.nx-page-content #roomsList {
    display: grid;
    gap: 13px;
}

.nx-page-content .room-card {
    position: relative;
    padding: 22px 24px;
    overflow: hidden;
    border: 1px solid #E4E9E2;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 3px 13px rgba(29,41,36,.025);
    transition: .25s ease;
}

.nx-page-content .room-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 3px;
    height: 100%;
    background: var(--ai-copper);
}

.nx-page-content .room-card:hover {
    transform: translateY(-2px);
    border-color: #C9D8CA;
    box-shadow: 0 10px 25px rgba(29,41,36,.065);
}

.nx-page-content .room-card h3 {
    margin: 0 0 7px;
    color: var(--ai-deep);
    font-family: 'Manrope',sans-serif;
    font-size: 17px;
    font-weight: 800;
}

.nx-page-content .room-card > p {
    margin: 0;
    color: var(--ai-muted);
    font-size: 11px;
    line-height: 1.7;
}

.nx-page-content .room-card > p strong {
    color: #43534A;
}

.nx-page-content .room-details {
    display: grid;
    grid-template-columns: repeat(3,minmax(0,1fr));
    gap: 9px;
    margin-top: 17px;
    padding-top: 16px;
    border-top: 1px solid #EDF0EB;
}

.nx-page-content .room-details > div {
    min-width: 0;
    padding: 11px 12px;
    border-radius: 9px;
    background: #F7F9F6;
    color: #53645A;
    font-size: 11px;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.nx-page-content .room-details strong {
    display: block;
    margin-bottom: 4px;
    color: #849087;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .6px;
    text-transform: uppercase;
}

.nx-page-content #roomsList > p {
    padding: 22px;
    border: 1px dashed #D6DFD5;
    border-radius: 12px;
    background: #F8FAF7;
    color: var(--ai-muted);
    font-size: 12px;
    line-height: 1.8;
    text-align: center;
}

/* ===================== BOOK ROOM BUTTON ===================== */

.nx-page-content .room-card-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-top: 17px;
    padding-top: 15px;
    border-top: 1px solid #EDF0EB;
}

.nx-page-content .room-book-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    min-width: 190px;
    min-height: 43px;
    padding: 11px 18px;
    border: 1px solid transparent;
    border-radius: 9px;
    background: linear-gradient(115deg, #315C4D, #203D34);
    color: #fff;
    font-family: 'Manrope', sans-serif;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .2px;
    text-decoration: none;
    box-shadow: 0 5px 14px rgba(32,61,52,.13);
    transition: .25s ease;
}

.nx-page-content .room-book-btn .book-arrow {
    color: var(--ai-copper-light);
    font-size: 16px;
    transition: transform .25s ease;
}

.nx-page-content .room-book-btn:hover {
    transform: translateY(-2px);
    background: linear-gradient(115deg, #274B3F, #142B23);
    color: #fff;
    box-shadow: 0 9px 20px rgba(32,61,52,.2);
}

.nx-page-content .room-book-btn:hover .book-arrow {
    transform: translateX(4px);
}

.nx-page-content .room-book-btn:focus-visible {
    outline: 3px solid rgba(197,138,98,.45);
    outline-offset: 3px;
}

.nx-page-content .room-book-unavailable {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 15px;
    border: 1px solid #E7EAE5;
    border-radius: 9px;
    background: #F7F8F6;
    color: #89938B;
    font-size: 11px;
    font-weight: 700;
}

/* ===================== RESPONSIVE ===================== */

@media(max-width: 850px) {
    .nx-page-content .page {
        width: min(100% - 36px,720px);
    }

    .nx-page-content .hero {
        grid-template-columns: 1fr .45fr;
        padding: 34px;
    }

    .nx-page-content .hero-visual {
        transform: scale(.85);
    }

    .nx-page-content .panel {
        padding: 26px;
    }

    .nx-page-content .equipment-grid {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }
}

@media(max-width: 600px) {
    .nx-page-content .page {
        width: calc(100% - 26px);
        padding: 19px 0 38px;
    }

    .nx-page-content .hero {
        display: block;
        min-height: auto;
        padding: 27px 23px;
        margin-bottom: 18px;
        border-radius: 17px;
    }

    .nx-page-content .hero h1 {
        max-width: 95%;
        font-size: 30px;
    }

    .nx-page-content .hero p {
        font-size: 12px;
    }

    .nx-page-content .hero-visual {
        display: none;
    }

    .nx-page-content .intro-note {
        display: none;
    }

    .nx-page-content .panel {
        padding: 22px 17px;
        border-radius: 15px;
    }

    .nx-page-content .panel-heading {
        flex-direction: column;
        gap: 12px;
        padding-bottom: 17px;
        margin-bottom: 19px;
    }

    .nx-page-content .panel-heading h2 {
        font-size: 19px;
    }

    .nx-page-content .form-grid,
    .nx-page-content .equipment-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .nx-page-content .field {
        margin-bottom: 18px;
    }

    .nx-page-content .equipment-option {
        min-height: 49px;
    }

    .nx-page-content .form-footer {
        align-items: stretch;
        flex-direction: column;
    }

    .nx-page-content .submit-btn {
        width: 100%;
        min-width: 0;
    }

    .nx-page-content .room-details {
        grid-template-columns: repeat(2,minmax(0,1fr));
    }

    .nx-page-content .room-card {
        padding: 19px 17px;
    }

    .nx-page-content .room-card-footer {
        justify-content: stretch;
    }

    .nx-page-content .room-book-btn {
        width: 100%;
    }

    .nx-page-content .ai-answer {
        padding: 19px;
    }

    .nx-page-content .results-heading h2 {
        font-size: 17px;
    }
}

@media(prefers-reduced-motion:reduce) {
    .nx-page-content *,
    .nx-page-content *::before,
    .nx-page-content *::after {
        animation-duration: .01ms !important;
        transition-duration: .01ms !important;
    }
}
</style>
@endpush

@section('content')
<div class="nx-page-content">
    <div class="page">

        <section class="hero">
            <div class="hero-content">
                <div class="hero-kicker">NEXORA INTELLIGENCE</div>

                <h1>
                    Your next great
                    <span>workspace</span>
                    starts here.
                </h1>

                <p>
                    A smarter way to find your ideal meeting space.
                    Share your requirements and let our AI discover
                    the workspace that fits your plans.
                </p>
            </div>

            <div class="hero-visual" aria-hidden="true">
                <div class="orbit">
                    <span class="orbit-dot one"></span>
                    <span class="orbit-dot two"></span>
                    <div class="orbit-core">✦</div>
                </div>
            </div>
        </section>

        <div class="intro-strip">
            <div class="intro-label">PERSONALIZED WORKSPACE MATCHING</div>
            <div class="intro-note">Thoughtfully matched. Intelligently selected.</div>
        </div>

        <section class="panel">

            <div class="panel-heading">
                <div>
                    <h2>Let's find your perfect space</h2>
                    <p>Tell us a little about your meeting to get tailored recommendations.</p>
                </div>

                <div class="private-note">
                    <span>✦</span>
                    AI Powered Search
                </div>
            </div>

            <form
                id="workspaceAIForm"
                data-url="{{ route('member.workspace-ai.recommend') }}"
                data-room-url="{{ route('rooms.details', ['room' => '__ROOM_ID__']) }}"
            >

                <div class="form-section-label">
                    <span>01</span>
                    Meeting requirements
                </div>

                <div class="form-grid">

                    <div class="field">
                        <label for="attendees">How many people are attending?</label>
                        <input
                            type="number"
                            id="attendees"
                            name="attendees"
                            min="1"
                            max="100"
                            placeholder="e.g. 12 attendees"
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
                            placeholder="e.g. 25.00"
                            required
                        >
                    </div>

                </div>

                <div class="equipment-area">

                    <div class="form-section-label">
                        <span>02</span>
                        What equipment will you need?
                    </div>

                    <div class="equipment-grid">

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="Projector">
                            <span>Projector</span>
                        </label>

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="Whiteboard">
                            <span>Whiteboard</span>
                        </label>

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="WiFi">
                            <span>High-speed WiFi</span>
                        </label>

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="Screen">
                            <span>Presentation Screen</span>
                        </label>

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="TV">
                            <span>Smart TV</span>
                        </label>

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="Video Conference">
                            <span>Video Conference</span>
                        </label>

                    </div>

                    <p class="equipment-help">
                        Select all the facilities that matter for your meeting.
                    </p>

                </div>

                <div class="form-footer">
                    <div class="form-footer-note">
                        <span>✧</span>
                        <span>
                            <strong>Smart recommendations</strong><br>
                            Based on your requirements and budget.
                        </span>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        Find My Perfect Workspace
                    </button>
                </div>

            </form>

            <div id="loading">
                <div class="loading-orbit"></div>
                <p>Our AI is finding the right workspace for you...</p>
            </div>

            <div id="errorMessage" class="error"></div>

        </section>

        <section id="results">

            <div class="results-heading">
                <h2>AI Recommendations</h2>
                <span class="result-tag">PERSONALIZED INSIGHT</span>
                <span class="heading-line"></span>
            </div>

            <div id="aiAnswer" class="ai-answer"></div>

            <div class="results-heading" style="margin-top:27px;">
                <h2>Matching Workspaces</h2>
                <span class="heading-line"></span>
            </div>

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
        return String(value ?? '').replace(/[&<>"']/g, function(char) {
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

    form.addEventListener('submit', async function(event) {

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
                <p>
                    No matching rooms were found.
                    Try increasing your budget or changing your requirements.
                </p>
            `;

            return;
        }

        const roomUrlTemplate = form.dataset.roomUrl;

        rooms.forEach(function(room) {

            const facilities = Array.isArray(room.facilities) ?
                room.facilities.map(escapeHTML).join(', ') :
                'Not specified';

            const card = document.createElement('div');

            card.className = 'room-card';

            // Generate the correct details URL using the room ID.
            const hasRoomId =
                room.id !== undefined &&
                room.id !== null &&
                String(room.id).trim() !== '';

            const bookUrl = hasRoomId
                ? roomUrlTemplate.replace(
                    '__ROOM_ID__',
                    encodeURIComponent(String(room.id))
                )
                : null;

            const bookButton = bookUrl
                ? `
                    <a href="${escapeHTML(bookUrl)}" class="room-book-btn">
                        <span>Book This Room</span>
                        <span class="book-arrow" aria-hidden="true">→</span>
                    </a>
                `
                : `
                    <span class="room-book-unavailable">
                        Booking currently unavailable
                    </span>
                `;

            const hourlyPrice = Number(room.hourly_price);

            const formattedPrice = Number.isFinite(hourlyPrice)
                ? hourlyPrice.toFixed(2)
                : 'Not specified';

            card.innerHTML = `
                <h3>${escapeHTML(room.room_name)}</h3>

                <p>
                    <strong>Workspace:</strong>
                    ${escapeHTML(room.workspace_name)}
                </p>

                <div class="room-details">

                    <div>
                        <strong>Location</strong>
                        ${escapeHTML(room.location || 'Not specified')}
                    </div>

                    <div>
                        <strong>Room type</strong>
                        ${escapeHTML(room.room_type || 'Not specified')}
                    </div>

                    <div>
                        <strong>Capacity</strong>
                        ${escapeHTML(room.capacity ?? 'Not specified')} people
                    </div>

                    <div>
                        <strong>Hourly price</strong>
                        ${formattedPrice}
                    </div>

                    <div>
                        <strong>Facilities</strong>
                        ${facilities}
                    </div>

                </div>

                <div class="room-card-footer">
                    ${bookButton}
                </div>
            `;

            roomsList.appendChild(card);

        });

    }
</script>
@endpush
