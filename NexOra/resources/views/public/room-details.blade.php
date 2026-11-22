
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $room->name }} | NexOra</title>
    <meta name="theme-color" content="#172e27">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/pages/room-details.css') }}">
    <script src="{{ asset('js/pages/room-details.js') }}" defer></script>
</head>
<body class="nx-room-page">

<main class="nx-room" data-room-id="{{ $room->id }}">
    <div class="nx-room__container">

        <nav class="nx-room__nav">
            <a href="{{ route('explore') }}" class="nx-room__brand">
                <span class="nx-room__brand-mark">N</span>
                <span>NEXORA</span>
            </a>

            <div class="nx-room__breadcrumb">
                <a href="{{ route('explore') }}">Explore</a>
                <span>/</span>
                <span>{{ $room->workspace->name }}</span>
            </div>

            <a href="{{ route('explore') }}" class="nx-room__back-link">
                <span>←</span> Back to explore
            </a>
        </nav>

        <section class="nx-room__hero">

            <div class="nx-room__visual">
                <div class="nx-room__gallery" id="roomGallery">

                    @forelse($room->images as $index => $image)
                        <img
                            src="{{asset('storage/' . $image->image_path) }}"
                            class="nx-room__gallery-image {{ $index === 0 ? 'is-active' : '' }}"
                            alt="{{ $image->alt_text ?? $room->name }}"
                            loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                        >
                    @empty
                        <div class="nx-room__no-image">
                            <strong>No image available</strong>
                            <span>Room photos will appear here</span>
                        </div>
                    @endforelse

                    <div class="nx-room__image-shade"></div>

                    <div class="nx-room__visual-top">
                        <span class="nx-room__visual-tag">
                            <i></i> SPACE TO THINK. ROOM TO CREATE.
                        </span>

                        <span class="nx-room__image-count">
                            <span id="galleryCurrent">1</span>
                            <span class="nx-room__count-divider">/</span>
                            {{ max($room->images->count(), 1) }}
                        </span>
                    </div>

                    <div class="nx-room__visual-content">
                        <span class="nx-room__eyebrow">DISCOVER THE SPACE</span>
                        <h1>{{ $room->name }}</h1>

                        <div class="nx-room__visual-meta">
                            <span class="nx-room__location-icon">⌖</span>
                            <span>{{ $room->workspace->location }}</span>
                            <i></i>
                            <span>{{ $room->room_type }}</span>
                        </div>

                        <div class="nx-room__visual-price">
                            <strong>{{ number_format((float) $room->hourly_price, 2) }}</strong>
                            <span>/ hour</span>
                        </div>
                    </div>

                    @if($room->images->count() > 1)
                        <div class="nx-room__gallery-controls">
                            <button type="button" data-gallery-step="-1" aria-label="Previous image">←</button>
                            <button type="button" data-gallery-step="1" aria-label="Next image">→</button>
                        </div>
                    @endif
                </div>

                @if($room->images->count() > 1)
                    <div class="nx-room__thumbnails">
                        @foreach($room->images as $index => $image)
                            <button
                                type="button"
                                class="nx-room__thumbnail {{ $index === 0 ? 'is-active' : '' }}"
                                data-gallery-thumb="{{ $index }}"
                                aria-label="Show image {{ $index + 1 }}"
                                aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                            >
                                <img src="{{ asset($image->image_path) }}" alt="" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="nx-room__booking-card" id="booking">
                <div class="nx-room__booking-heading">
                    <div>
                        <span class="nx-room__booking-kicker">MAKE IT HAPPEN</span>
                        <h2>Book your<br><em>space.</em></h2>
                    </div>
                    <span class="nx-room__booking-symbol">✳</span>
                </div>

                <div class="nx-room__booking-rate">
                    <strong>{{ number_format((float) $room->hourly_price, 2) }}</strong>
                    <span>/ hour</span>
                </div>

                <div class="nx-room__booking-rule"></div>

                <form method="POST" action="{{ route('rooms.book', $room->id) }}" id="bookingForm">
                    @csrf

                    <div class="nx-room__field">
                        <label for="bookingDate">
                            <span>01</span> Select a date
                        </label>
                        <div class="nx-room__input-shell">
                            <span class="nx-room__input-icon">▦</span>
                            <input
                                type="date"
                                id="bookingDate"
                                min="{{ now()->toDateString() }}"
                                value="{{ now()->toDateString() }}"
                            >
                        </div>
                    </div>

                    <div class="nx-room__slots-block">
                        <div class="nx-room__slots-heading">
                            <label><span>02</span> Choose a time</label>
                            <small>YOUR START TIME</small>
                        </div>

                        <div id="slots" class="nx-room__slots" aria-live="polite">
                            <p class="nx-room__slots-placeholder">
                                Select a date to discover available times.
                            </p>
                        </div>
                    </div>

                    <div class="nx-room__legend">
                        <span><i class="is-available"></i> Available</span>
                        <span><i class="is-booked"></i> Booked</span>
                        <span><i class="is-past"></i> Unavailable</span>
                    </div>

                    <p id="availabilityMessage" class="nx-room__message" role="status"></p>

                    <div class="nx-room__field nx-room__duration-field">
                        <label for="duration"><span>03</span> Duration</label>
                        <div class="nx-room__input-shell nx-room__select-shell">
                            <select name="duration" id="duration" required>
                                @for($i = 1; $i <= 48; $i++)
                                    <option value="{{ $i }}">
                                        {{ $i }} {{ $i === 1 ? 'Hour' : 'Hours' }}
                                    </option>
                                @endfor
                            </select>
                            <span class="nx-room__select-arrow">⌄</span>
                        </div>
                    </div>

                    <input type="hidden" name="start_date_time" id="startDateTime">

                    <button type="submit" class="nx-room__book-button">
                        <span>Continue to booking</span>
                        <span class="nx-room__button-arrow">↗</span>
                    </button>

                    <p class="nx-room__secure-note">
                        <span>✦</span> Your next productive moment starts here.
                    </p>
                </form>

                @if($errors->has('booking'))
                    <p class="nx-room__server-error">{{ $errors->first('booking') }}</p>
                @endif
            </aside>
        </section>

        <section class="nx-room__details">

            <div class="nx-room__details-intro">
                <span class="nx-room__section-kicker">THE DETAILS</span>
                <h2>Designed for<br><em>better work.</em></h2>
                <p>
                    Everything you need to make your next session
                    comfortable, focused and productive.
                </p>
            </div>

            <article class="nx-room__about">
                <span class="nx-room__detail-number">01 / ABOUT</span>
                <h3>A place to do<br>your best work.</h3>
                <p>
                    {{ $room->workspace->description ?? 'No workspace description available.' }}
                </p>
            </article>

            <div class="nx-room__details-side">
                <div class="nx-room__stats">
                    <div class="nx-room__stat">
                        <span class="nx-room__stat-icon">⌂</span>
                        <div>
                            <small>SPACE TYPE</small>
                            <strong>{{ $room->room_type }}</strong>
                        </div>
                    </div>

                    <div class="nx-room__stat">
                        <span class="nx-room__stat-icon">♙</span>
                        <div>
                            <small>CAPACITY</small>
                            <strong>{{ $room->capacity }} people</strong>
                        </div>
                    </div>

                    <div class="nx-room__stat">
                        <span class="nx-room__stat-icon">◷</span>
                        <div>
                            <small>BOOKING TYPE</small>
                            <strong>Hourly</strong>
                        </div>
                    </div>
                </div>

                <div class="nx-room__facilities">
                    <div class="nx-room__facilities-heading">
                        <span class="nx-room__detail-number">02 / AMENITIES</span>
                        <h3>Thoughtful essentials.</h3>
                    </div>

                    <div class="nx-room__facility-list">
                        @forelse(($room->facilities ?? []) as $facility)
                            <span class="nx-room__facility">
                                <i>✓</i> {{ $facility }}
                            </span>
                        @empty
                            <p class="nx-room__empty-facilities">
                                No equipment information available.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <footer class="nx-room__footer">
            <a href="{{ route('explore') }}" class="nx-room__footer-brand">
                <span>N</span> NEXORA
            </a>
            <p>Find your focus. Find your space.</p>
            <a href="{{ route('explore') }}" class="nx-room__footer-link">
                Explore more spaces <span>↗</span>
            </a>
        </footer>

    </div>
</main>

</body>
</html>
