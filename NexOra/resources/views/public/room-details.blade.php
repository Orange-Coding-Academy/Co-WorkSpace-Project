
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $room->name }} | NexOra</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', 'Noto Sans Arabic', system-ui, sans-serif;
            background: #F5F7F3;
            color: #1D2924;
        }

        .page {
            max-width: 1150px;
            margin: auto;
            padding: 40px 22px;
        }

        .back {
            display: inline-block;
            margin-bottom: 25px;
            color: #203D34;
            text-decoration: none;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .gallery {
            position: relative;
            height: 380px;
            border-radius: 16px;
            overflow: hidden;
            background: #E9EEE9;
        }

        .gallery img {
            display: none;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery img.active {
            display: block;
        }

        .gallery button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            background: rgba(0,0,0,.6);
            color: white;
        }

        .gallery .prev {
            left: 12px;
        }

        .gallery .next {
            right: 12px;
        }

        .room-info,
        .booking-box,
        .calendar-box {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
        }

        .workspace {
            color: #7F8B84;
            margin-bottom: 12px;
        }

        h1 {
            font-size: 30px;
            margin-bottom: 15px;
        }

        .description {
            line-height: 1.7;
            color: #7F8B84;
            margin: 18px 0;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 20px 0;
        }

        .meta span,
        .facility {
            background: #F5F7F3;
            padding: 9px 12px;
            border-radius: 20px;
            font-size: 13px;
        }

        .facilities {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
        }

        .price {
            font-size: 27px;
            font-weight: bold;
            color: #203D34;
            margin-top: 25px;
        }

        .price small {
            font-size: 13px;
            color: #7F8B84;
        }

        .booking-section {
            margin-top: 30px;
        }

        .booking-section h2 {
            margin-bottom: 18px;
        }

        .calendar-box {
            margin-bottom: 20px;
        }

        .calendar-box label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input, select {
            padding: 12px;
            width: 100%;
            border: 1px solid #E2E8E2;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .slots {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .slot {
            border: 0;
            border-radius: 8px;
            padding: 13px 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .slot.available {
            background: #dcfce7;
            color: #166534;
        }

        .slot.booked {
            background: #fee2e2;
            color: #991b1b;
            cursor: not-allowed;
        }

        .slot.past {
            background: #E9EEE9;
            color: #53615A;
            cursor: not-allowed;
        }

        .slot.selected {
            outline: 3px solid #203D34;
        }

        .legend {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
            font-size: 13px;
        }

        .legend span::before {
            content: "";
            display: inline-block;
            width: 11px;
            height: 11px;
            margin-right: 7px;
            border-radius: 3px;
        }

        .legend .green::before {
            background: #22c55e;
        }

        .legend .red::before {
            background: #ef4444;
        }

        .legend .gray::before {
            background: #7F8B84;
        }

        .booking-box {
            margin-top: 20px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            border: 0;
            border-radius: 9px;
            background: #203D34;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .message {
            margin-top: 15px;
            color: #b91c1c;
        }

        @media(max-width: 800px) {
            .details-grid {
                grid-template-columns: 1fr;
            }

            .slots {
                grid-template-columns: repeat(3, 1fr);
            }

            .gallery {
                height: 280px;
            }
        }
    </style>
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

<body>

<div class="page">

    <a href="{{ route('explore') }}" class="back">
        ← Back to Explore
    </a>

    <div class="details-grid">

        <div class="gallery">

            @forelse($room->images as $index => $image)

                <img
                    src="{{ asset('storage/' . $image->image_path) }}"
                    class="{{ $index === 0 ? 'active' : '' }}"
                    alt="{{ $image->alt_text ?? $room->name }}"
                >

            @empty

                <div style="height:100%;display:grid;place-items:center;">
                    No Image Available
                </div>

            @endforelse

            @if($room->images->count() > 1)

                <button type="button" class="prev"
                    onclick="changeImage(this,-1)">&#10094;</button>

                <button type="button" class="next"
                    onclick="changeImage(this,1)">&#10095;</button>

            @endif

        </div>

        <div class="room-info">

            <div class="workspace">
                {{ $room->workspace->name }}
            </div>

            <h1>{{ $room->name }}</h1>

            <div class="meta">
                <span>{{ $room->room_type }}</span>
                <span>{{ $room->capacity }} People</span>
                <span>{{ $room->workspace->location }}</span>
            </div>

            <p class="description">
                {{ $room->workspace->description ?? 'No workspace description available.' }}
            </p>

            <h3>Room Equipment</h3>

            <div class="facilities">
                @forelse(($room->facilities ?? []) as $facility)
                    <span class="facility">{{ $facility }}</span>
                @empty
                    <span>No equipment information available.</span>
                @endforelse
            </div>

            <div class="price">
                {{ number_format((float)$room->hourly_price, 2) }}
                <small>/ hour</small>
            </div>

        </div>

    </div>

    <div class="booking-section">

        <h2>Check Availability</h2>

        <div class="calendar-box">

            <label for="bookingDate">Select Date</label>

            <input
                type="date"
                id="bookingDate"
                min="{{ now()->toDateString() }}"
                value="{{ now()->toDateString() }}"
            >

            <div id="slots" class="slots">
                <p>Select a date to view availability.</p>
            </div>

            <div class="legend">
                <span class="green">Available</span>
                <span class="red">Booked</span>
                <span class="gray">Past / Unavailable</span>
            </div>

            <p id="availabilityMessage" class="message"></p>

        </div>

        <div class="booking-box">

            <form
                method="POST"
                action="{{ route('rooms.book', $room->id) }}"
                id="bookingForm"
            >

                @csrf

                <label for="duration">Booking Duration</label>

                <select name="duration" id="duration" required>
                    @for($i = 1; $i <= 48; $i++)
                        <option value="{{ $i }}">
                            {{ $i }} {{ $i === 1 ? 'Hour' : 'Hours' }}
                        </option>
                    @endfor
                </select>

                <input
                    type="hidden"
                    name="start_date_time"
                    id="startDateTime"
                >

                <button type="submit" class="btn">
                    Book Now
                </button>

            </form>

            @if($errors->has('booking'))
                <p class="message">{{ $errors->first('booking') }}</p>
            @endif

        </div>

    </div>

</div>

<script>

const roomId = {{ $room->id }};

const dateInput = document.getElementById('bookingDate');
const slotsContainer = document.getElementById('slots');
const startDateTimeInput = document.getElementById('startDateTime');
const durationInput = document.getElementById('duration');
const message = document.getElementById('availabilityMessage');

let selectedStart = null;
let availableSlots = [];

async function loadAvailability() {

    selectedStart = null;
    startDateTimeInput.value = '';

    message.textContent = 'Loading availability...';
    slotsContainer.innerHTML = '';

    const date = dateInput.value;

    if (!date) return;

    try {

        const response = await fetch(
            `/rooms/${roomId}/availability?date=${encodeURIComponent(date)}`
        );

        if (!response.ok) {
            throw new Error('Could not load availability.');
        }

        const data = await response.json();

        availableSlots = data.slots;

        data.slots.forEach(slot => {

            const button = document.createElement('button');

            button.type = 'button';
            button.className = `slot ${slot.status}`;
            button.textContent = slot.start;

            if (slot.available) {

                button.addEventListener('click', () => {

                    document.querySelectorAll('.slot.selected')
                        .forEach(item => item.classList.remove('selected'));

                    button.classList.add('selected');

                    selectedStart = slot.start;

                    startDateTimeInput.value =
                        `${date} ${slot.start}:00`;

                    message.textContent =
                        `Selected start time: ${slot.start}`;

                });

            } else {

                button.disabled = true;

            }

            slotsContainer.appendChild(button);

        });

        message.textContent = 'Choose an available start time.';

    } catch (error) {

        message.textContent = error.message;

    }
}

dateInput.addEventListener('change', loadAvailability);

document.getElementById('bookingForm').addEventListener('submit', function(event) {

    if (!selectedStart || !startDateTimeInput.value) {

        event.preventDefault();

        message.textContent =
            'Please select an available start time first.';

        return;
    }

    const duration = parseInt(durationInput.value, 10);

    const start = new Date(
        `${dateInput.value}T${selectedStart}:00`
    );

    const end = new Date(start.getTime() + duration * 60 * 60 * 1000);

    // Check the selected booking period against all displayed hourly slots.
    let cursor = new Date(start);

    while (cursor < end) {

        const hour = cursor.getHours();

        const slot = availableSlots.find(item =>
            item.start === String(hour).padStart(2, '0') + ':00'
        );

        if (!slot || !slot.available) {

            event.preventDefault();

            message.textContent =
                'The selected duration includes an unavailable hour. Please choose another time.';

            return;
        }

        cursor.setHours(cursor.getHours() + 1);
    }

});

function changeImage(button, direction) {

    const gallery = button.closest('.gallery');
    const images = gallery.querySelectorAll('img');

    if (images.length < 2) return;

    let current = Array.from(images).findIndex(
        image => image.classList.contains('active')
    );

    if (current < 0) current = 0;

    images[current].classList.remove('active');

    current = (current + direction + images.length) % images.length;

    images[current].classList.add('active');
}

loadAvailability();

</script>

</body>
</html>
