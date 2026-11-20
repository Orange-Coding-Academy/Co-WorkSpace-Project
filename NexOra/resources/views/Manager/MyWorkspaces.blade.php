<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My workspaces | NexOra</title>
    <style>
        :root {
            --forest: #103b34;
            --ink: #18352d;
            --muted: #53665c;
            --cream: #f7f1e7;
            --paper: #fffdf8;
            --line: #dbe0d3;
            --orange: #ff8750;
        }

        * { box-sizing: border-box; }
        body { margin: 0; background: var(--cream); color: var(--ink); font: 1rem/1.5 system-ui, sans-serif; }
        h1, h2, p { margin: 0; }
        a:focus-visible { outline: 3px solid #29745e; outline-offset: 4px; }
        .wrap { width: min(1140px, calc(100% - 32px)); margin-inline: auto; }
        .topbar { background: var(--forest); color: var(--paper); padding-block: 15px; }
        .topbar span { font-weight: 800; font-size: 1.24rem; letter-spacing: -.04em; }
        main { padding-block: clamp(26px, 5vw, 56px); }
        .heading { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
        h1 { font-size: clamp(2rem, 5vw, 3.35rem); line-height: 1.08; letter-spacing: -.05em; }
        .heading p { margin-top: 7px; color: var(--muted); }
        .button { display: inline-flex; align-items: center; gap: 8px; padding: 10px 15px; border-radius: 10px; background: var(--orange); color: #252016; font-weight: 750; text-decoration: none; }
        .button:hover { background: #ffa071; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 290px), 1fr)); gap: 16px; }
        .card { display: flex; flex-direction: column; min-width: 0; overflow: hidden; border: 1px solid var(--line); border-radius: 16px; background: var(--paper); }
        .card:hover { border-color: #9ab4a3; box-shadow: 0 8px 22px #103b3410; }
        .media { position: relative; display: grid; place-items: center; aspect-ratio: 16 / 9; background: #e3ede2; color: #2c5f4e; font-size: 2.2rem; font-weight: 750; }
        .media img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
        .content { flex: 1; display: flex; flex-direction: column; gap: 6px; padding: 16px; }
        .card h2 { font-size: 1.2rem; overflow-wrap: anywhere; }
        .location { color: var(--muted); }
        .status { align-self: flex-start; margin-top: 6px; padding: 3px 9px; border-radius: 999px; background: #ecebe4; color: #465149; font-size: .8rem; font-weight: 700; }
        .status.active { background: #dceee3; color: #185940; }
        .status.pending { background: #fff0d0; color: #774a00; }
        .detail { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: auto; padding-top: 12px; color: var(--forest); font-weight: 700; text-decoration: none; }
        .detail:hover { text-decoration: underline; }
        .empty { padding: 24px; border: 1px dashed #a7b9a8; border-radius: 16px; background: var(--paper); }
        .empty p { margin-top: 6px; color: var(--muted); }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="wrap"><span>NexOra</span></div>
    </header>

    <main class="wrap">
        <header class="heading">
            <div>
                <h1>My workspaces</h1>
                <p>Open a workspace to view its details and manage its rooms.</p>
            </div>
            <a class="button" href="{{ route('manager.workspaces.create') }}"><span aria-hidden="true">+</span> Create workspace</a>
        </header>

        <div class="grid">
            @forelse ($workspaces as $workspace)
                <article class="card">
                    <div class="media">
                        <span aria-hidden="true">{{ mb_strtoupper(mb_substr($workspace->name, 0, 1)) }}</span>
                        @foreach ($workspace->images->take(1) as $image)
                            <img src="{{ asset(\Illuminate\Support\Str::startsWith($image->image_path, ['http://', 'https://', 'img/', '/img/', 'storage/', '/storage/']) ? $image->image_path : 'storage/' . $image->image_path) }}"
                                 alt="{{ $image->alt_text ?: $workspace->name }}" loading="lazy"
                                 onerror="this.hidden = true">
                        @endforeach
                    </div>
                    <div class="content">
                        <h2>{{ $workspace->name }}</h2>
                        <p class="location">{{ $workspace->location }}</p>
                        @if (! $workspace->is_active)
                            <span class="status">Inactive</span>
                        @elseif ($workspace->approval_status === 'pending')
                            <span class="status pending">Pending review</span>
                        @elseif ($workspace->approval_status === 'approved')
                            <span class="status active">Approved</span>
                        @else
                            <span class="status">{{ ucfirst($workspace->approval_status) }}</span>
                        @endif
                        <a class="detail" href="{{ route('manager.workspaces.details', ['workspaceId' => $workspace->id]) }}"
                           aria-label="View details for {{ $workspace->name }}">
                            View details <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </article>
            @empty
                <section class="empty">
                    <h2>No workspaces yet</h2>
                    <p>Create your first workspace to start adding rooms.</p>
                </section>
            @endforelse
        </div>
    </main>
</body>
</html>
