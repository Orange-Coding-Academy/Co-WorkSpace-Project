{{-- Shared Admin workspace-details page content (sections/stacks). Used by Admin.WorkspaceDetails and Admin.MangeWorkSpace. --}}
@section('title') {{ $workspace->name }} | Workspace details @endsection
@section('page-title') {{ $workspace->name }} @endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-05.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-04.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/workspace-pages.css') }}?v=20261125-ui5">
@endpush

@section('content')
<div class="nx-page-content nx-workspace-ui nx-workspace-details">
    <div data-nx-page-main>

        <nav class="workspace-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('admin.AdminDashboard') }}">Admin</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('admin.workspaces') }}">Workspace directory</a>
            <span aria-hidden="true">/</span>
            <strong>{{ $workspace->name }}</strong>
        </nav>

        <a class="back" href="{{ route('admin.workspaces') }}">&larr; Workspace directory</a>

        <section class="card overview" aria-labelledby="workspace-title">

            {{-- Workspace cover image --}}
            @php
                $coverImage = $workspace->images->first();
            @endphp

            @if ($coverImage)
                <img
                    class="cover"
                    src="{{ asset(ltrim($coverImage->image_path, '/')) }}"
                    alt="{{ $coverImage->alt_text ?? $workspace->name }}"
                    onerror="this.style.display='none';"
                >
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
                        @forelse ($workspace->facilities ?? [] as $facility)
                            <li><span class="pill">{{ $facility }}</span></li>
                        @empty
                            <li>No facilities listed.</li>
                        @endforelse
                    </ul>
                </section>

                <form method="POST" action="{{ route('admin.UpdateStatus', $workspace) }}">
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        class="approve-workspace"
                        @if ($workspace->approval_status === 'approved') disabled @endif
                    >
                        {{ $workspace->approval_status === 'approved' ? 'Approved' : 'Approve workspace' }}
                    </button>
                </form>
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

                            <span class="room-placeholder" aria-hidden="true">
                                {{ mb_strtoupper(mb_substr($room->name, 0, 1)) }}
                            </span>

                            {{-- Room images --}}
                            @foreach ($room->images as $image)

                                @php
    $roomImagePath = ltrim($image->image_path, '/');

    if (str_starts_with($roomImagePath, 'storage/')) {
        $roomImagePath = substr($roomImagePath, strlen('storage/'));
    }
@endphp

                               <img
    src="{{ asset('storage/' . $roomImagePath) }}"
    alt="{{ $image->alt_text ?: $room->name }}"
    loading="lazy"
    onerror="this.style.display='none';"
    @if (! $loop->first) hidden @endif
>

                            @endforeach

                            @if ($room->images->count() > 1)
                                <button
                                    type="button"
                                    class="room-image-button previous"
                                    data-image-step="-1"
                                    aria-label="Previous image for {{ $room->name }}"
                                >&lsaquo;</button>

                                <button
                                    type="button"
                                    class="room-image-button next"
                                    data-image-step="1"
                                    aria-label="Next image for {{ $room->name }}"
                                >&rsaquo;</button>
                            @endif

                        </div>

                        <div class="room-body">

                            <div class="room-heading">
                                <p class="room-type">
                                    {{ ucfirst(str_replace('_', ' ', $room->room_type)) }}
                                </p>

                                <span @class(['pill', 'active' => $room->is_active])>
                                    {{ $room->is_active ? 'Active' : 'Disabled' }}
                                </span>
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

    </div>
</div>
@endsection


@push('scripts')
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
@endpush