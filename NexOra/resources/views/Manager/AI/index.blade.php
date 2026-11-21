@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Manager AI | NEXORA @endsection
@section('page-title') Manager AI @endsection

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/manager-ai.css') }}">

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

    <div class="header">
        <h1>Manager AI Assistant</h1>
        <p>Your intelligent assistant for managing your workspace.</p>
    </div>

    <div class="chat-box">

        <div class="messages" id="messages">
            <div class="message ai">
                Hello! I'm your NEXORA Manager AI.
                How can I help you today?
            </div>
        </div>

        <div class="input-area">
            <textarea
                id="question"
                rows="2"
                placeholder="Ask me anything..."
            ></textarea>

            <button id="sendBtn" onclick="sendQuestion()">
                Send
            </button>
        </div>

        <div class="suggestions">
            <button onclick="askSuggestion('Analyze my bookings.')">
                Analyze Bookings
            </button>

            <button onclick="askSuggestion('What are my peak booking days?')">
                Peak Days
            </button>

            <button onclick="askSuggestion('Give me marketing ideas.')">
                Marketing Ideas
            </button>
        </div>

    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="card mt-4">
    <div class="card-header">
        <h4>Marketing Assistant</h4>
    </div>

    <div class="card-body">

        <div class="mb-3">
            <label for="workspace_id" class="form-label">
                Select Workspace
            </label>

            <select id="workspace_id" class="form-control">
                <option value="">Choose Workspace</option>

                @foreach($workspaces as $workspace)
                    <option value="{{ $workspace->id }}">
                        {{ $workspace->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="marketing_request" class="form-label">
                What kind of advertisement do you want?
            </label>

            <textarea
                id="marketing_request"
                class="form-control"
                rows="3"
                placeholder="Write a request for AI..."
            ></textarea>
        </div>

        <button type="button" id="generateMarketingBtn" class="btn btn-primary">
            Generate Advertisement
        </button>

        <hr>

        <div class="mb-3">
            <label for="marketing_subject" class="form-label">
                Email Subject
            </label>

            <input
                type="text"
                id="marketing_subject"
                class="form-control"
                placeholder="Enter email subject"
            >
        </div>

        <div class="mb-3">
            <label for="marketing_content" class="form-label">
                Advertisement Content
            </label>

            <textarea
                id="marketing_content"
                class="form-control"
                rows="8"
                placeholder="AI generated advertisement will appear here..."
            ></textarea>
        </div>

        <button type="button" id="sendMarketingBtn" class="btn btn-success">
            Approve & Send Advertisement
        </button>

        <div id="marketingMessage" class="mt-3"></div>

    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    ).getAttribute('content');

    const workspaceSelect = document.getElementById('workspace_id');
    const requestInput = document.getElementById('marketing_request');
    const subjectInput = document.getElementById('marketing_subject');
    const contentInput = document.getElementById('marketing_content');
    const messageBox = document.getElementById('marketingMessage');

    // Generate advertisement using AI
    document.getElementById('generateMarketingBtn')
        .addEventListener('click', async function () {

            const workspaceId = workspaceSelect.value;
            const marketingRequest = requestInput.value.trim();

            if (!workspaceId || !marketingRequest) {
                messageBox.innerText =
                    'Please select a workspace and enter your request.';
                return;
            }

            this.disabled = true;
            this.innerText = 'Generating...';

            try {
                const response = await fetch(
                    "{{ route('manager.ai.marketing') }}",
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            workspace_id: workspaceId,
                            request: marketingRequest
                        })
                    }
                );

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'AI generation failed.');
                }

                contentInput.value = data.content;

                if (!subjectInput.value) {
                    subjectInput.value =
                        'Discover ' +
                        workspaceSelect.options[
                            workspaceSelect.selectedIndex
                        ].text;
                }

                messageBox.innerText =
                    'Advertisement generated. Review it before sending.';

            } catch (error) {
                messageBox.innerText = error.message;
            } finally {
                this.disabled = false;
                this.innerText = 'Generate Advertisement';
            }
        });


    // Send advertisement after manager approval
    document.getElementById('sendMarketingBtn')
        .addEventListener('click', async function () {

            const workspaceId = workspaceSelect.value;
            const subject = subjectInput.value.trim();
            const content = contentInput.value.trim();

            if (!workspaceId || !subject || !content) {
                messageBox.innerText =
                    'Please select a workspace and provide an email subject and advertisement.';
                return;
            }

            const approved = confirm(
                'Are you sure you want to send this advertisement to all eligible previous bookers?'
            );

            if (!approved) {
                return;
            }

            this.disabled = true;
            this.innerText = 'Sending...';

            try {
                const response = await fetch(
                    "{{ route('manager.ai.marketing.send') }}",
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            workspace_id: workspaceId,
                            subject: subject,
                            content: content
                        })
                    }
                );

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Email sending failed.');
                }

                messageBox.innerText =
                    'Campaign completed! Total recipients: ' +
                    data.total_recipients +
                    ' | Sent: ' + data.sent +
                    ' | Failed: ' + data.failed;

            } catch (error) {
                messageBox.innerText = error.message;
            } finally {
                this.disabled = false;
                this.innerText = 'Approve & Send Advertisement';
            }
        });

});
</script>
<script>
    const messages = document.getElementById('messages');
    const questionInput = document.getElementById('question');
    const sendBtn = document.getElementById('sendBtn');

    function addMessage(text, type) {
        const div = document.createElement('div');

        div.classList.add('message', type);
        div.textContent = text;

        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;

        return div;
    }

    function askSuggestion(text) {
        questionInput.value = text;
        sendQuestion();
    }

    async function sendQuestion() {
        const question = questionInput.value.trim();

        if (!question || sendBtn.disabled) return;

        addMessage(question, 'user');

        questionInput.value = '';
        sendBtn.disabled = true;

        const loading = addMessage('Thinking...', 'ai');

        try {
            const response = await fetch(
                "{{ route('manager.ai.ask') }}",
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                    },

                    body: JSON.stringify({
                        question: question
                    })
                }
            );

            const data = await response.json();

            loading.remove();

            if (!response.ok || !data.success) {
                throw new Error(
                    data.message || 'Something went wrong.'
                );
            }

            addMessage(data.answer, 'ai');

        } catch (error) {
            loading.remove();
            addMessage(error.message, 'ai');

        } finally {
            sendBtn.disabled = false;
            questionInput.focus();
        }
    }

    
</script>
@endpush
