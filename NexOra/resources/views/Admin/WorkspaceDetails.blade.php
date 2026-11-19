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
        .room-image-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 44px;
            height: 44px;
            border: 1px solid #ffffff80;
            border-radius: 50%;
            background: #211a35cc;
            color: #fff;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
        }

        .room-image-button.previous { left: 10px; }
        .room-image-button.next { right: 10px; }
        .room-image-button:hover { background: #211a35; }
        .room-image-button:focus-visible { outline-color: #fff; }
        .workspace-facilities {
            margin-top: 20px;
        }

        .workspace-facilities h2 {
            font-size: .95rem;
        }

        .workspace-facilities-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 8px 0 0;
            padding: 0;
            list-style: none;
            color: #5d5670;
            font-size: .85rem;
        }
    </style>
</head>

<body>
    <main>
        <a class="back" href="{{ route('workspaces') }}">&larr; Workspace directory</a>

        <section class="card overview" aria-labelledby="workspace-title">
            @if ($workspace->relationLoaded('images'))
               @foreach ($workspace->images->take(1) as $image)
          <img class="cover"
         src="{{ asset($image->image_path) }}"
         alt="{{ $image->alt_text ?: $workspace->name }}">
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
                <section class="workspace-facilities" aria-labelledby="facilities-title">
                    <h2 id="facilities-title">Facilities</h2>
                    <ul class="workspace-facilities-list" role="list">
                        @forelse (json_decode($workspace->facilities ?? '[]', true) ?? [] as $facility)
                            <li><span class="pill">{{ $facility }}</span></li>
                        @empty
                            <li>No facilities listed.</li>
                        @endforelse
                    </ul>
                </section>
            </div>
        </section>

        <section class="rooms" aria-labelledby="rooms-title">
            <div class="rooms-header">
                <h2 id="rooms-title">Rooms</h2>
                <span class="pill">{{ ($rooms ?? $workspace->rooms)->count() }}</span>
            </div>

            <div class="room-grid">
                @forelse (($rooms ?? $workspace->rooms) as $room)
                <article class="card room-card">
                    <div class="room-media">
                        <span class="room-placeholder" aria-hidden="true">{{ mb_strtoupper(mb_substr($room->name, 0, 1)) }}</span>

                        @if ($room->relationLoaded('images'))
                            @foreach ($room->images as $image)
                                <img src="{{ asset($image->image_path) }}"
                                     alt="{{ $image->alt_text ?: $room->name }}"
                                     loading="lazy" @if (! $loop->first) hidden @endif>
                            @endforeach

                            @if ($room->images->count() > 1)
                                <button type="button" class="room-image-button previous" data-image-step="-1"
                                        aria-label="Previous image for {{ $room->name }}">&lsaquo;</button>
                                <button type="button" class="room-image-button next" data-image-step="1"
                                        aria-label="Next image for {{ $room->name }}">&rsaquo;</button>
                            @endif
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
        for (const gallery of document.querySelectorAll('.room-media')) {
            const images = gallery.querySelectorAll('img');
            let current = 0;

            for (const button of gallery.querySelectorAll('[data-image-step]')) {
                button.addEventListener('click', () => {
                    images[current].hidden = true;
                    current = (current + Number(button.dataset.imageStep) + images.length) % images.length;
                    images[current].hidden = false;
                });
            }
        }
    </script>
</body>

</html>