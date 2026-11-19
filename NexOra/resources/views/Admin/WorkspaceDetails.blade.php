<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $workspace->name }} | Workspace details</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f3f9;
            color: #211a35;
            font: 1rem/1.5 system-ui, sans-serif;
        }

        main {
            width: min(1120px, 100%);
            margin: auto;
            padding: clamp(16px, 4vw, 40px);
        }

        .back {
            display: inline-block;
            margin-bottom: 16px;
            color: #5033c4;
            font-weight: 650;
            text-decoration: none;
        }

        .back:hover {
            text-decoration: underline;
        }

        :focus-visible {
            outline: 2px solid #6042df;
            outline-offset: 3px;
        }

        .card {
            background: #fff;
            border: 1px solid #e8e3ef;
            border-radius: 20px;
        }

        .overview {
            display: grid;
            gap: 24px;
            padding: clamp(18px, 3vw, 28px);
        }

        .overview:has(img:not([hidden])) {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.35fr);
        }

        .cover {
            display: block;
            width: 100%;
            aspect-ratio: 16 / 10;
            border-radius: 14px;
            object-fit: cover;
        }

        .cover[hidden] {
            display: none;
        }

        h1,
        h2,
        p,
        dl,
        dd {
            margin: 0;
        }

        h1 {
            font-size: clamp(1.65rem, 4vw, 2.35rem);
            line-height: 1.15;
            overflow-wrap: anywhere;
        }

        h2 {
            font-size: 1.25rem;
        }

        .description {
            margin-top: 16px;
            white-space: pre-line;
            overflow-wrap: anywhere;
            color: #5d5670;
        }

        .facts {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 28px;
            margin-top: 18px;
        }

        dt {
            color: #5d5670;
            font-size: .85rem;
        }

        dd {
            font-weight: 600;
        }

        .pill {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 999px;
            background: #eeebf4;
            color: #554b69;
            font-size: .84rem;
            font-weight: 650;
        }

        .active {
            background: #def5eb;
            color: #16634d;
        }

        .pending {
            background: #fff0d5;
            color: #814c00;
        }

        .rooms {
            margin-top: 24px;
        }

        .rooms-header {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 260px), 1fr));
            gap: 16px;
        }

        .room-card {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .room-media {
            position: relative;
            display: grid;
            place-items: center;
            aspect-ratio: 16 / 9;
            overflow: hidden;
            background: radial-gradient(circle at 75% 20%, #fff8, transparent 55%), #e9e0fc;
            color: #5d448f;
        }

        .room-media img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .room-media img[hidden] {
            display: none;
        }

        .room-placeholder {
            font-size: 2.5rem;
            font-weight: 750;
        }

        .room-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 16px;
        }

        .room-heading {
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 8px;
        }

        .room-heading .pill {
            flex: none;
        }

        .room-body h3 {
            margin: 4px 0 14px;
            font-size: 1.08rem;
            overflow-wrap: anywhere;
        }

        .room-type {
            color: #5d5670;
            font-size: .85rem;
        }

        .room-facts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #e8e3ef;
        }

        .empty {
            padding: 20px;
            color: #5d5670;
        }

        @media (hover: hover) {
            .room-card:hover {
                border-color: #cfc0ef;
                box-shadow: 0 5px 16px #211a3510;
            }
        }

        @media (max-width: 620px) {
            .overview:has(img:not([hidden])) {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <main>
        <a class="back" href="{{ route('workspaces') }}">&larr; Workspace directory</a>

        <section class="card overview" aria-labelledby="workspace-title">
            @if ($workspace->relationLoaded('images'))
            @foreach ($workspace->images->sortBy([['is_primary', 'desc'], ['sort_order', 'asc']])->take(1) as $cover)
            <img class="cover" data-image-path="{{ $cover->image_path }}" alt="{{ $cover->alt_text ?: $workspace->name }}" hidden>
            @endforeach
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
            </div>
        </section>

        <section class="rooms" aria-labelledby="rooms-title">
            <div class="rooms-header">
                <h2 id="rooms-title">Rooms</h2>
                <span class="pill">{{ $workspace->rooms->count() }}</span>
            </div>

            <div class="room-grid">
                @forelse ($workspace->rooms as $room)
                <article class="card room-card">
                    <div class="room-media">
                        <span class="room-placeholder" aria-hidden="true">{{ mb_strtoupper(mb_substr($room->name, 0, 1)) }}</span>
                        @if (filled($room->image_path))
                        <img data-image-path="{{ $room->image_path }}" alt="{{ $room->name }}" loading="lazy" hidden>
                        @endif
                    </div>

                    <div class="room-body">
                        <div class="room-heading">
                            <p class="room-type">{{ ucfirst(str_replace('_', ' ', $room->room_type)) }}</p>
                            <span @class(['pill', 'active'=> $room->is_active])>{{ $room->is_active ? 'Active' : 'Disabled' }}</span>
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
    </main>
    <script>
        const storageUrl = "";

        for (const image of document.querySelectorAll('[data-image-path]')) {
            const path = image.dataset.imagePath.trim();
            if (!path) continue;

            const relativePath = path.replace(/^\/+/, '').replace(/^storage\//, '');
            const imageUrl = /^https?:\/\//i.test(path) ?
                path :
                storageUrl.href + relativePath;

            image.addEventListener('error', () => {
                image.hidden = true;
            }, {
                once: true
            });
            image.src = imageUrl;
            image.hidden = false;
        }
    </script>
</body>

</html>