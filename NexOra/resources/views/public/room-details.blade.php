<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $room->name }} | NexOra</title>
    <meta name="theme-color" content="#203D34">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/room-details.css') }}">
    <script src="{{ asset('js/pages/room-details.js') }}" defer></script>
</head>
<body class="nx-room-page">

<main class="nx-room" data-room-id="{{ $room->id }}">
    <div class="nx-room__container">

        <nav class="nx-room__breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('explore') }}" class="nx-room__back">
                <span aria-hidden="true">←</span>
                <span>Back to Explore</span>
            </a>
            <span class="nx-room__breadcrumb-divider">/</span>
            <span class="nx-room__breadcrumb-current">Room details</span>
        </nav>

        <header class="nx-room__heading">
            <div class="nx-room__heading-copy">
                <p class="nx-room__eyebrow">
                    <span class="nx-room__eyebrow-line"></span>
                    {{ $room->workspace->name }}
                </p>
                <h1>{{ $room->name }}</h1>
                <div class="nx-room__quick-meta">
                    <span class="nx-room__meta-item">
                        <span class="nx-room__meta-icon" aria-hidden="true">⌖</span>
                        {{ $room->workspace->location }}
                    </span>
                    <span class="nx-room__meta-dot"></span>
                    <span class="nx-room__meta-item">{{ $room->room_type }}</span>
                </div>
            </div>
            <div class="nx-room__rate-chip">
                <span class="nx-room__rate-label">Hourly rate</span>
                <strong>{{ number_format((float) $room->hourly_price, 2) }}</strong>
                <span class="nx-room__rate-unit">/ hour</span>
            </div>
        </header>

        <section class="nx-room__top-grid" aria-label="Room overview and booking">
            <div class="nx-room__gallery-card">
                <div class="nx-room__gallery" id="roomGallery">
                    @forelse($room->images as $index => $image)
                        <img
                            src="{{ asset('storage/' . $image->image_path) }}"
                            class="nx-room__gallery-image {{ $index === 0 ? 'is-active' : '' }}"
                            alt="{{ $image->alt_text ?? $room->name }}"
                            loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                        >
                    @empty
                        <div class="nx-room__no-image">
                            <span class="nx-room__no-image-icon" aria-hidden="true">▧</span>
                            <strong>No image available</strong>
                            <span>Room photos will appear here</span>
                        </div>
                    @endforelse

                    @if($room->images->count() > 1)
                        <div class="nx-room__gallery-controls">
                            <button type="button" class="nx-room__gallery-arrow" data-gallery-step="-1" aria-label="Previous image">‹</button>
                            <span class="nx-room__gallery-counter">
                                <span id="galleryCurrent">1</span>
                                <span class="nx-room__counter-divider">/</span>
                                {{ $room->images->count() }}
                            </span>
                            <button type="button" class="nx-room__gallery-arrow" data-gallery-step="1" aria-label="Next image">›</button>
                        </div>
                    @endif
                    <div class="nx-room__gallery-caption">
                        <span class="nx-room__gallery-caption-dot"></span>
                        Workspace gallery
                    </div>
                </div>

                @if($room->images->count() > 1)
                    <div class="nx-room__thumbnails" aria-label="Choose room photo">
                        @foreach($room->images as $index => $image)
                            <button
                                type="button"
                                class="nx-room__thumbnail {{ $index === 0 ? 'is-active' : '' }}"
                                data-gallery-thumb="{{ $index }}"
                                aria-label="Show image {{ $index + 1 }}"
                                aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                            >
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="nx-room__booking-card" id="booking">
                <div class="nx-room__booking-top">
                    <div>
                        <p class="nx-room__section-kicker">Reserve your space</p>
                        <h2>Book this room</h2>
                    </div>
                    <span class="nx-room__booking-mark" aria-hidden="true">✦</span>
                </div>

                <div class="nx-room__booking-price">
                    <strong>{{ number_format((float) $room->hourly_price, 2) }}</strong>
                    <span>/ hour</span>
                </div>

                <div class="nx-room__booking-divider"></div>

                <div class="nx-room__field">
                    <label for="bookingDate">Choose your date</label>
                    <div class="nx-room__date-wrap">
                        <span class="nx-room__field-icon" aria-hidden="true">▦</span>
                        <input
                            type="date"
                            id="bookingDate"
                            min="{{ now()->toDateString() }}"
                            value="{{ now()->toDateString() }}"
                        >
                    </div>
                </div>

                <div class="nx-room__slots-heading">
                    <label>Available time slots</label>
                    <span>Choose a start time</span>
                </div>

                <div id="slots" class="nx-room__slots" aria-live="polite">
                    <p class="nx-room__slots-placeholder">Select a date to view available times.</p>
                </div>

                <div class="nx-room__legend" aria-label="Availability legend">
                    <span><i class="is-available"></i>Available</span>
                    <span><i class="is-booked"></i>Booked</span>
                    <span><i class="is-past"></i>Unavailable</span>
                </div>

                <p id="availabilityMessage" class="nx-room__message" role="status"></p>

                <form method="POST" action="{{ route('rooms.book', $room->id) }}" id="bookingForm" class="nx-room__booking-form">
                    @csrf

                    <div class="nx-room__field">
                        <label for="duration">Booking duration</label>
                        <div class="nx-room__select-wrap">
                            <select name="duration" id="duration" required>
                                @for($i = 1; $i <= 48; $i++)
                                    <option value="{{ $i }}">
                                        {{ $i }} {{ $i === 1 ? 'Hour' : 'Hours' }}
                                    </option>
                                @endfor
                            </select>
                            <span aria-hidden="true">⌄</span>
                        </div>
                    </div>

                    <input type="hidden" name="start_date_time" id="startDateTime">

                    <button type="submit" class="nx-room__book-button">
                        <span>Continue to booking</span>
                        <span aria-hidden="true">→</span>
                    </button>
                    <p class="nx-room__secure-note">
                        <span aria-hidden="true">✓</span>
                        Select a time slot to continue
                    </p>
                </form>

                @if($errors->has('booking'))
                    <p class="nx-room__server-error">{{ $errors->first('booking') }}</p>
                @endif
            </aside>
        </section>

        <section class="nx-room__details-grid">
            <article class="nx-room__content-card">
                <div class="nx-room__section-heading">
                    <div>
                        <p class="nx-room__section-kicker">Get to know the space</p>
                        <h2>About this workspace</h2>
                    </div>
                    <span class="nx-room__heading-decoration" aria-hidden="true">01</span>
                </div>
                <p class="nx-room__description">
                    {{ $room->workspace->description ?? 'No workspace description available.' }}
                </p>
            </article>

            <article class="nx-room__content-card nx-room__capacity-card">
                <div class="nx-room__section-heading">
                    <div>
                        <p class="nx-room__section-kicker">Designed for your work</p>
                        <h2>Room essentials</h2>
                    </div>
                    <span class="nx-room__heading-decoration" aria-hidden="true">02</span>
                </div>

                <div class="nx-room__essential-row">
                    <span class="nx-room__essential-icon" aria-hidden="true">♧</span>
                    <div>
                        <span class="nx-room__essential-label">Room type</span>
                        <strong>{{ $room->room_type }}</strong>
                    </div>
                </div>
                <div class="nx-room__essential-row">
                    <span class="nx-room__essential-icon" aria-hidden="true">♙</span>
                    <div>
                        <span class="nx-room__essential-label">Capacity</span>
                        <strong>{{ $room->capacity }} people</strong>
                    </div>
                </div>
            </article>
        </section>

        <section class="nx-room__content-card nx-room__facilities-card">
            <div class="nx-room__section-heading">
                <div>
                    <p class="nx-room__section-kicker">Everything you need</p>
                    <h2>Room equipment & facilities</h2>
                </div>
                <span class="nx-room__heading-decoration" aria-hidden="true">03</span>
            </div>

            <div class="nx-room__facilities">
                @forelse(($room->facilities ?? []) as $facility)
                    <span class="nx-room__facility">
                        <span aria-hidden="true">✓</span>
                        {{ $facility }}
                    </span>
                @empty
                    <p class="nx-room__empty-facilities">No equipment information available.</p>
                @endforelse
            </div>
        </section>

        <footer class="nx-room__footer-note">
            <span class="nx-room__footer-sparkle" aria-hidden="true">✦</span>
            <span>Make room for great work.</span>
            <a href="{{ route('explore') }}">Explore more spaces <span aria-hidden="true">→</span></a>
        </footer>

    </div>
</main>

</body>
</html>
