
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Workspaces | NexOra</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f7f8fa;
            color: #202b3c;
        }

        .page {
            max-width: 1250px;
            margin: auto;
            padding: 45px 24px;
        }

        .hero {
            margin-bottom: 35px;
        }

        .hero h1 {
            font-size: 36px;
            margin-bottom: 12px;
        }

        .hero p {
            color: #718096;
            line-height: 1.6;
        }

        .filters {
            background: white;
            padding: 24px;
            border-radius: 16px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field label {
            font-size: 13px;
            font-weight: 600;
        }

        .field input,
        .field select {
            width: 100%;
            padding: 12px;
            border: 1px solid #e0e5eb;
            border-radius: 8px;
            outline: none;
            background: white;
        }

        .filter-actions {
            display: flex;
            align-items: end;
            gap: 10px;
        }

        .btn {
            padding: 12px 18px;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
        }

        .btn-primary {
            background: #263b5b;
            color: white;
        }

        .btn-light {
            background: #edf0f4;
            color: #263b5b;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .room-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,.05);
        }

        .gallery {
            height: 225px;
            position: relative;
            background: #e9edf2;
        }

        .gallery img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        .gallery img.active {
            display: block;
        }

        .gallery button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 50%;
            background: rgba(0,0,0,.6);
            color: white;
            cursor: pointer;
        }

        .gallery .prev {
            left: 12px;
        }

        .gallery .next {
            right: 12px;
        }

        .gallery .counter {
            position: absolute;
            bottom: 12px;
            right: 12px;
            color: white;
            background: rgba(0,0,0,.6);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .no-image {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #718096;
        }

        .room-content {
            padding: 20px;
        }

        .workspace-name {
            color: #7a8798;
            font-size: 13px;
            margin-bottom: 9px;
        }

        .room-content h3 {
            font-size: 20px;
            margin-bottom: 12px;
        }

        .room-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            color: #718096;
            font-size: 13px;
            margin: 15px 0;
        }

        .facilities {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin: 15px 0;
        }

        .facility {
            background: #edf2f7;
            color: #46566d;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 11px;
        }

        .room-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #edf0f4;
            padding-top: 15px;
            margin-top: 15px;
        }

        .price strong {
            font-size: 21px;
            color: #263b5b;
        }

        .price span {
            font-size: 12px;
            color: #718096;
        }

        .card-actions {
            display: flex;
            gap: 8px;
            margin-top: 18px;
        }

        .card-actions a {
            flex: 1;
            font-size: 13px;
        }

        .empty {
            grid-column: 1 / -1;
            background: white;
            padding: 55px 20px;
            border-radius: 14px;
            text-align: center;
            color: #718096;
        }

        .pagination {
            margin-top: 35px;
        }

        @media(max-width: 950px) {
            .rooms-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .filters {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width: 600px) {
            .rooms-grid,
            .filters {
                grid-template-columns: 1fr;
            }

            .hero h1 {
                font-size: 28px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="hero">
        <h1>Explore Workspaces</h1>
        <p>
            Discover the perfect space for your next meeting,
            collaboration or productive day.
        </p>
    </div>

    <form action="{{ route('explore') }}" method="GET" class="filters">

        <div class="field">
            <label>Search</label>
            <input
                type="text"
                name="search"
                placeholder="Search rooms or workspaces..."
                value="{{ request('search') }}"
            >
        </div>

        <div class="field">
            <label>Workspace</label>
            <select name="workspace">
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
            <label>Room Type</label>
            <select name="room_type">
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
            <label>Equipment</label>
            <select name="equipment">
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
            <label>Minimum Capacity</label>
            <input
                type="number"
                name="capacity"
                min="1"
                value="{{ request('capacity') }}"
                placeholder="People"
            >
        </div>

        <div class="field">
            <label>Minimum Price / Hour</label>
            <input
                type="number"
                name="min_price"
                min="0"
                step="0.01"
                value="{{ request('min_price') }}"
                placeholder="Min price"
            >
        </div>

        <div class="field">
            <label>Maximum Price / Hour</label>
            <input
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
                Apply Filters
            </button>

            <a href="{{ route('explore') }}" class="btn btn-light">
                Reset
            </a>
        </div>

    </form>

    <div class="results-header">
        <h2>Available Rooms</h2>
        <span>{{ $rooms->total() }} rooms found</span>
    </div>

    <div class="rooms-grid">

        @forelse($rooms as $room)

            <div class="room-card">

                <div class="gallery">

                    @forelse($room->images as $index => $image)

                        <img
                            src="{{ asset('storage/' . $image->image_path) }}"
                            class="{{ $index === 0 ? 'active' : '' }}"
                            alt="{{ $image->alt_text ?? $room->name }}"
                        >

                    @empty

                        <div class="no-image">
                            No Image Available
                        </div>

                    @endforelse

                    @if($room->images->count() > 1)
                        <button type="button" class="prev"
                            onclick="changeImage(this, -1)">&#10094;</button>

                        <button type="button" class="next"
                            onclick="changeImage(this, 1)">&#10095;</button>

                        <span class="counter">
                            <span class="current">1</span>/{{ $room->images->count() }}
                        </span>
                    @endif

                </div>

                <div class="room-content">

                    <div class="workspace-name">
                        {{ $room->workspace->name }}
                    </div>

                    <h3>{{ $room->name }}</h3>

                    <div class="room-meta">
                        <span>{{ $room->room_type }}</span>
                        <span>Up to {{ $room->capacity }} people</span>
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
                            <strong>
                                {{ number_format((float)$room->hourly_price, 2) }}
                            </strong>
                            <span>/ hour</span>
                        </div>

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
                            Book Now
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="empty">
                <h3>No rooms found</h3>
                <p>Try changing your filters and search again.</p>
            </div>

        @endforelse

    </div>

    <div class="pagination">
        {{ $rooms->links() }}
    </div>

</div>

<script>
function changeImage(button, direction) {

    const gallery = button.closest('.gallery');
    const images = gallery.querySelectorAll('img');

    if (images.length < 2) return;

    let current = Array.from(images).findIndex(
        image => image.classList.contains('active')
    );

    if (current === -1) current = 0;

    images[current].classList.remove('active');

    current = (current + direction + images.length) % images.length;

    images[current].classList.add('active');

    const counter = gallery.querySelector('.current');

    if (counter) {
        counter.textContent = current + 1;
    }
}
</script>

</body>
</html>
