
@extends('layouts.member')

@section('title', 'Explore Workspaces | NexOra')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/pages/explore.css') }}">
@endpush

@section('content')
<main class="nx-explore">
    <div class="nx-explore__shell">

        {{-- HERO --}}
        <section class="nx-explore__hero">
            <div class="nx-explore__hero-copy">
                <span class="nx-explore__eyebrow">
                    <span></span>
                    WORK BETTER, TOGETHER
                </span>

                <h1>
                    A space that<br>
                    <em>moves you forward.</em>
                </h1>

                <p>
                    Find inspiring workspaces and meeting rooms designed
                    around the way you think, create and collaborate.
                </p>

                <a href="#explore-results" class="nx-explore__hero-link">
                    Explore spaces <span>↘</span>
                </a>

                <div class="nx-explore__hero-foot">
                    <span>✳</span>
                    Make room for your next big idea.
                </div>
            </div>

            <div class="nx-explore__visual">
                <img
                    class="nx-explore__visual-image"
                    src="{{ asset('images/nexora/workspace-lounge-01.jpg') }}"
                    alt="Bright coworking lounge with city views and comfortable seating"
                    fetchpriority="high"
                >

                <div class="nx-explore__floating-note">
                    <span>✦</span>
                    <div>
                        <strong>Find your focus</strong>
                        <small>Space for better ideas</small>
                    </div>
                </div>

            </div>
        </section>

        {{-- FILTERS --}}
        <section class="nx-explore__search" aria-labelledby="filter-title">

            <div class="nx-explore__search-heading">
                <div>
                    <span class="nx-explore__overline">THE RIGHT SPACE STARTS HERE</span>
                    <h2 id="filter-title">Find your perfect fit</h2>
                    <p>Choose your preferences to discover the right space.</p>
                </div>

                <span class="nx-explore__search-symbol">⌕</span>
            </div>

            <form id="explore-filters"
                  action="{{ route('explore') }}"
                  method="GET"
                  class="nx-explore__form">

                {{-- WORKSPACE --}}
                <div class="nx-explore__field">
                    <label for="explore-workspace">Workspace</label>

                    <select id="explore-workspace" name="workspace">
                        <option value="">All workspaces</option>

                        @foreach ($workspaces as $workspace)
                            <option value="{{ $workspace->id }}"
                                @selected(request('workspace') == $workspace->id)>
                                {{ $workspace->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- ROOM TYPE --}}
                <div class="nx-explore__field">
                    <label for="explore-room-type">Room type</label>

                    <select id="explore-room-type" name="room_type">
                        <option value="">All room types</option>

                        @foreach ($roomTypes as $type)
                            <option value="{{ $type }}"
                                @selected(request('room_type') == $type)>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- EQUIPMENT --}}
                <div class="nx-explore__field">
                    <label for="explore-equipment">Equipment</label>

                    <select id="explore-equipment" name="equipment">
                        <option value="">Any equipment</option>

                        @foreach ($equipmentOptions as $equipment)
                            <option value="{{ $equipment }}"
                                @selected(request('equipment') == $equipment)>
                                {{ $equipment }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CAPACITY --}}
                <div class="nx-explore__field">
                    <label for="explore-capacity">People</label>

                    <input id="explore-capacity"
                           type="number"
                           name="capacity"
                           min="1"
                           value="{{ request('capacity') }}"
                           placeholder="Min. capacity">
                </div>

                {{-- MIN PRICE --}}
                <div class="nx-explore__field">
                    <label for="explore-min-price">Min. price / hour</label>

                    <input id="explore-min-price"
                           type="number"
                           name="min_price"
                           min="0"
                           step="0.01"
                           value="{{ request('min_price') }}"
                           placeholder="Any">
                </div>

                {{-- MAX PRICE --}}
                <div class="nx-explore__field">
                    <label for="explore-max-price">Max. price / hour</label>

                    <input id="explore-max-price"
                           type="number"
                           name="max_price"
                           min="0"
                           step="0.01"
                           value="{{ request('max_price') }}"
                           placeholder="Any">
                </div>

            </form>
        </section>

        {{-- RESULTS --}}
        <section class="nx-explore__results"
                 id="explore-results"
                 aria-labelledby="rooms-title">

            <div class="nx-explore__results-heading">
                <div>
                    <span class="nx-explore__overline">SPACES WORTH DISCOVERING</span>

                    <h2 id="rooms-title">
                        Explore available rooms<span class="nx-explore__heading-period">.</span>
                    </h2>

                    <p>
                        Thoughtful places for focused work, meaningful meetings
                        and fresh ideas.
                    </p>
                </div>

                <div class="nx-explore__count">
                    <strong>{{ $rooms->total() }}</strong>
                    <span>SPACES AVAILABLE</span>
                </div>
            </div>

            @forelse ($rooms as $room)

                <article class="nx-room-card">

                    {{-- IMAGE GALLERY --}}
                    <div class="nx-room-card__media" data-gallery>

                        @forelse ($room->images as $index => $image)

                            <img
                                src="{{  asset('storage/' . $image->image_path)}}"
                                class="nx-room-card__image {{ $index === 0 ? 'is-active' : '' }}"
                                alt="{{ $image->alt_text ?? $room->name }}"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                            >

                        @empty

                            <img
                                src="{{ asset('images/nexora/room-boardroom.jpg') }}"
                                class="nx-room-card__image is-active"
                                alt="{{ $room->name }} at NexOra"
                                loading="lazy"
                            >

                        @endforelse

                        @if ($room->images->count() > 1)

                            <div class="nx-room-card__gallery-controls">

                                <button type="button"
                                        data-gallery-prev
                                        aria-label="Previous image">
                                    ‹
                                </button>

                                <span>
                                    <b data-gallery-current>1</b>
                                    <i>/</i>
                                    {{ $room->images->count() }}
                                </span>

                                <button type="button"
                                        data-gallery-next
                                        aria-label="Next image">
                                    ›
                                </button>

                            </div>

                        @endif

                        <span class="nx-room-card__photo-tag">
                            NEXORA SPACE
                        </span>
                    </div>

                    {{-- ROOM INFORMATION --}}
                    <div class="nx-room-card__body">

                        <div class="nx-room-card__workspace">
                            <span></span>
                            {{ $room->workspace->name ?? 'NexOra Workspace' }}
                        </div>

                        <h3>{{ $room->name }}</h3>

                        <div class="nx-room-card__details">
                            <span>
                                <i>▦</i>
                                {{ $room->room_type }}
                            </span>

                            <span>
                                <i>♧</i>
                                Up to {{ $room->capacity }} people
                            </span>
                        </div>

                        @php
                            $facilities = collect($room->facilities ?? []);
                        @endphp

                        @if ($facilities->isNotEmpty())

                            <div class="nx-room-card__facilities">

                                @foreach ($facilities->take(3) as $facility)
                                    <span>{{ $facility }}</span>
                                @endforeach

                                @if ($facilities->count() > 3)
                                    <span class="nx-room-card__more">
                                        +{{ $facilities->count() - 3 }} more
                                    </span>
                                @endif

                            </div>

                        @endif

                        <div class="nx-room-card__footer">

                            <div class="nx-room-card__price">
                                <strong>
                                    {{ number_format((float) $room->hourly_price, 2) }}
                                </strong>
                                <span>/ hour</span>
                            </div>

                            <span class="nx-room-card__price-note">
                                Flexible booking
                            </span>

                        </div>

                        {{-- BOOK BUTTON ONLY --}}
                        <div class="nx-room-card__actions">

                            <a href="{{ route('rooms.details', $room->id) }}"
                               class="nx-explore__button nx-explore__button--primary">
                                Book this room <span>→</span>
                            </a>

                        </div>

                    </div>
                </article>

            @empty

                <div class="nx-explore__empty">
                    <span class="nx-explore__empty-icon">⌕</span>

                    <span class="nx-explore__overline">NO MATCHES FOUND</span>

                    <h3>Nothing here just yet.</h3>

                    <p>
                        Try changing your filters
                        to see more available spaces.
                    </p>

                    <a href="{{ route('explore') }}"
                       class="nx-explore__button nx-explore__button--primary">
                        Clear all filters <span>↗</span>
                    </a>
                </div>

            @endforelse

            {{-- NUMBERS ONLY PAGINATION --}}
            @if ($rooms->hasPages())

                <nav class="nx-explore__pagination"
                     aria-label="Room results pages">

                    <div class="nx-explore__pagination-summary">
                        Showing
                        <strong>{{ $rooms->firstItem() }}–{{ $rooms->lastItem() }}</strong>
                        of
                        <strong>{{ $rooms->total() }}</strong>
                    </div>

                    <div class="nx-explore__pagination-links">

                        @foreach ($rooms->getUrlRange(1, $rooms->lastPage()) as $page => $url)

                            <a class="nx-page-number {{ $page === $rooms->currentPage() ? 'is-current' : '' }}"
                               href="{{ $url }}"
                               @if ($page === $rooms->currentPage()) aria-current="page" @endif>
                                {{ $page }}
                            </a>

                        @endforeach

                    </div>
                </nav>

            @endif

        </section>
    </div>
</main>
@endsection

@push('scripts')
    <script src="{{ asset('js/pages/explore.js') }}" defer></script>
@endpush