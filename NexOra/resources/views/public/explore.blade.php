<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f5f7f3">

    <title>Explore Workspaces | NexOra</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/pages/explore.css') }}">
    <script src="{{ asset('js/pages/explore.js') }}" defer></script>
</head>

<body>
<main class="page nx-explore">

    <section class="nx-explore__hero" aria-labelledby="explore-title">
        <div class="nx-explore__hero-copy">
            <span class="nx-explore__eyebrow">
                <span class="nx-explore__eyebrow-dot"></span>
                FIND YOUR SPACE
            </span>

            <h1 id="explore-title">
                Find a space where<br>
                <span>great work happens.</span>
            </h1>

            <p>
                Discover thoughtfully designed workspaces and meeting rooms
                made for focus, collaboration, and your next big idea.
            </p>

            <div class="nx-explore__hero-note">
                <span class="nx-explore__note-mark" aria-hidden="true">✦</span>
                <span>Spaces that work the way you do.</span>
            </div>
        </div>

        <div class="nx-explore__hero-art" aria-hidden="true">
            <div class="nx-explore__art-orbit nx-explore__art-orbit--one"></div>
            <div class="nx-explore__art-orbit nx-explore__art-orbit--two"></div>
            <div class="nx-explore__art-card">
                <span class="nx-explore__art-kicker">YOUR NEXT</span>
                <strong>Good<br>workspace.</strong>
                <span class="nx-explore__art-line"></span>
                <span class="nx-explore__art-caption">A better place to do your best work.</span>
            </div>
            <span class="nx-explore__art-spark nx-explore__art-spark--one">✳</span>
            <span class="nx-explore__art-spark nx-explore__art-spark--two">✦</span>
        </div>
    </section>

    <section class="nx-explore__search-section" aria-label="Find a workspace">
        <div class="nx-explore__section-kicker">DISCOVER YOUR SPACE</div>
        <div class="nx-explore__filter-heading">
            <div>
                <h2>Find the right fit</h2>
                <p>Fine-tune your search to find a space that suits your needs.</p>
            </div>
            <span class="nx-explore__filter-icon" aria-hidden="true">⌕</span>
        </div>

        <form id="explore-filters" action="{{ route('explore') }}" method="GET" class="filters nx-explore__filters">

            <div class="field field--search">
                <label for="explore-search">Search</label>
                <div class="nx-explore__input-wrap nx-explore__input-wrap--search">
                    <span class="nx-explore__input-icon" aria-hidden="true">⌕</span>
                    <input
                        id="explore-search"
                        type="text"
                        name="search"
                        placeholder="Search rooms or workspaces..."
                        value="{{ request('search') }}"
                    >
                </div>
            </div>

            <div class="field">
                <label for="explore-workspace">Workspace</label>
                <select id="explore-workspace" name="workspace">
                    <option value="">All Workspaces</option>

                    @foreach($workspaces as $workspace)
                        <option
                            value="{{ $workspace->id }}"
                            @selected(request('workspace') == $workspace->id)
                        >
                            {{ $workspace->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="explore-room-type">Room Type</label>
                <select id="explore-room-type" name="room_type">
                    <option value="">All Types</option>

                    @foreach($roomTypes as $type)
                        <option
                            value="{{ $type }}"
                            @selected(request('room_type') == $type)
                        >
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="explore-equipment">Equipment</label>
                <select id="explore-equipment" name="equipment">
                    <option value="">All Equipment</option>

                    @foreach($equipmentOptions as $equipment)
                        <option
                            value="{{ $equipment }}"
                            @selected(request('equipment') == $equipment)
                        >
                            {{ $equipment }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="explore-capacity">Minimum Capacity</label>
                <input
                    id="explore-capacity"
                    type="number"
                    name="capacity"
                    min="1"
                    value="{{ request('capacity') }}"
                    placeholder="Number of people"
                >
            </div>

            <div class="field">
                <label for="explore-min-price">Minimum Price / Hour</label>
                <input
                    id="explore-min-price"
                    type="number"
                    name="min_price"
                    min="0"
                    step="0.01"
                    value="{{ request('min_price') }}"
                    placeholder="Min price"
                >
            </div>

            <div class="field">
                <label for="explore-max-price">Maximum Price / Hour</label>
                <input
                    id="explore-max-price"
                    type="number"
                    name="max_price"
                    min="0"
                    step="0.01"
                    value="{{ request('max_price') }}"
                    placeholder="Max price"
                >
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <span aria-hidden="true">⌕</span>
                    Apply Filters
                </button>

                <a href="{{ route('explore') }}" class="btn btn-light">
                    Reset
                </a>
            </div>

        </form>
    </section>

    <section id="explore-results" class="nx-explore__results" aria-labelledby="available-rooms-title">
        <div class="results-header nx-explore__results-header">
            <div>
                <span class="nx-explore__section-kicker">CURATED FOR YOUR DAY</span>
                <h2 id="available-rooms-title">Explore available rooms</h2>
                <p>Find a place to focus, meet, and make things happen.</p>
            </div>
            <div class="nx-explore__result-summary" aria-live="polite">
                @if($rooms->total() > 0)
                    <span>Showing</span>
                    <strong>{{ $rooms->firstItem() }}–{{ $rooms->lastItem() }}</strong>
                    <span>of</span>
                    <strong>{{ $rooms->total() }}</strong>
                    <span>rooms</span>
                @else
                    <strong>0</strong>
                    <span>rooms found</span>
                @endif
            </div>
        </div>

        <div class="rooms-grid nx-explore__rooms-grid">

            @forelse($rooms as $room)

                <article class="room-card">

                    <div class="gallery">

                        @forelse($room->images as $index => $image)

                            <img
                                src="{{ asset('storage/' . $image->image_path) }}"
                                class="{{ $index === 0 ? 'active' : '' }}"
                                alt="{{ $image->alt_text ?? $room->name }}"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                            >

                        @empty

                            <div class="no-image">
                                <span class="nx-explore__no-image-icon" aria-hidden="true">⌂</span>
                                <span>Image coming soon</span>
                            </div>

                        @endforelse

                        @if($room->images->count() > 1)
                            <button type="button" class="prev" aria-label="Previous room image"
                                onclick="changeImage(this, -1)">&#10094;</button>

                            <button type="button" class="next" aria-label="Next room image"
                                onclick="changeImage(this, 1)">&#10095;</button>

                            <span class="counter" aria-live="polite">
                                <span class="current">1</span>/{{ $room->images->count() }}
                            </span>
                        @endif

                        <span class="nx-explore__image-label">NEXORA SPACE</span>
                    </div>

                    <div class="room-content">

                        <div class="nx-explore__card-topline">
                            <div class="workspace-name">
                                <span class="nx-explore__workspace-dot"></span>
                                {{ $room->workspace->name }}
                            </div>
                            <span class="nx-explore__card-mark" aria-hidden="true">↗</span>
                        </div>

                        <h3>{{ $room->name }}</h3>

                        <div class="room-meta">
                            <span><i aria-hidden="true">▦</i>{{ $room->room_type }}</span>
                            <span><i aria-hidden="true">♧</i>Up to {{ $room->capacity }} people</span>
                        </div>

                        <div class="facilities">
                            @foreach(($room->facilities ?? []) as $facility)
                                <span class="facility">
                                    {{ $facility }}
                                </span>
                            @endforeach
                        </div>

                        <div class="room-bottom">
                            <div class="price">
                                <strong>{{ number_format((float)$room->hourly_price, 2) }}</strong>
                                <span>/ hour</span>
                            </div>
                            <span class="nx-explore__price-caption">Flexible booking</span>
                        </div>

                        <div class="card-actions">
                            <a
                                href="{{ route('rooms.details', $room->id) }}"
                                class="btn btn-light"
                            >
                                View Details
                            </a>
                            <a
                                href="{{ route('rooms.details', $room->id) }}"
                                class="btn btn-primary"
                            >
                                Book Now <span aria-hidden="true">→</span>
                            </a>
                        </div>

                    </div>

                </article>

            @empty

                <div class="empty">
                    <span class="nx-explore__empty-icon" aria-hidden="true">⌕</span>
                    <span class="nx-explore__section-kicker">NO MATCHES THIS TIME</span>
                    <h3>No rooms found</h3>
                    <p>Try adjusting your filters or broadening your search to discover more spaces.</p>
                    <a href="{{ route('explore') }}" class="btn btn-primary">Clear all filters</a>
                </div>

            @endforelse

        </div>

        @if($rooms->hasPages())
            <div class="pagination nx-explore__pagination">
                {{ $rooms->onEachSide(1)->links() }}
            </div>
        @endif
    </section>

</main>
</body>
</html>
