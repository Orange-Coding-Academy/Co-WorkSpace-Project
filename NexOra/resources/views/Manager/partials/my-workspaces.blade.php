
{{-- =====================================================
     NEXORA — MANAGER MY WORKSPACES
===================================================== --}}

@section('title')
    My workspaces | NexOra
@endsection

@section('page-title')
    My workspaces
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-17.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-04.css') }}">

    <link rel="stylesheet" href="{{ asset('css/pages/manager-partials-my-workspaces.css') }}">
@endpush

@section('content')

<div class="nx-page-content nx-manager-workspaces">

    {{-- =================================================
         HERO
    ================================================== --}}

    <header class="nx-ai-header">

        <div class="nx-ai-header-content">

            <div class="nx-ai-eyebrow">
                <span class="nx-ai-eyebrow-dot"></span>
                MANAGER WORKSPACE
            </div>

            <h1>My Workspaces</h1>

            <p>
                Manage your workspaces, explore their details,
                and organize your rooms in one place.
            </p>

        </div>


        {{-- Hero Icon --}}

        <div class="nx-ai-header-icon" aria-hidden="true">

            <div class="nx-ai-orbit nx-ai-orbit-one"></div>

            <div class="nx-ai-orbit nx-ai-orbit-two"></div>

            <div class="nx-ai-icon-core">
                <span>✦</span>
            </div>

        </div>

    </header>


    {{-- =================================================
         WORKSPACES TOOLBAR
    ================================================== --}}

    <div class="nx-workspaces-toolbar">

        <span class="workspace-count">
            {{ $workspaces->count() }}
            {{ Illuminate\Support\Str::plural('workspace', $workspaces->count()) }}
        </span>

        <a
            class="button"
            href="{{ route('manager.workspaces.create') }}"
        >
            <span aria-hidden="true">+</span>
            Create Workspace
        </a>

    </div>


    {{-- =================================================
         WORKSPACES GRID
    ================================================== --}}

    <div class="grid">

        @forelse ($workspaces as $workspace)

            <article class="card">

                {{-- Workspace Image --}}

                <div class="media">

                    <span aria-hidden="true">
                        {{ mb_strtoupper(mb_substr($workspace->name, 0, 1)) }}
                    </span>

                    @foreach ($workspace->images->take(1) as $image)

                        <img
                            src="{{ asset($image->image_path) }}"
                            alt="{{ $image->alt_text ?: $workspace->name . ' photo ' . $loop->iteration }}"
                        >

                    @endforeach

                </div>


                {{-- Workspace Information --}}

                <div class="content">

                    <h2>
                        {{ $workspace->name }}
                    </h2>


                    <p class="location">
                        {{ $workspace->location }}
                    </p>


                    {{-- Workspace Status --}}

                    @if (! $workspace->is_active)

                        <span class="status">
                            Inactive
                        </span>

                    @elseif ($workspace->approval_status === 'pending')

                        <span class="status pending">
                            Pending review
                        </span>

                    @elseif ($workspace->approval_status === 'approved')

                        <span class="status active">
                            Approved
                        </span>

                    @else

                        <span class="status">
                            {{ ucfirst($workspace->approval_status) }}
                        </span>

                    @endif


                    {{-- View Details Button --}}

                    <a
                        class="detail"
                        href="{{ route('manager.workspaces.details', ['workspaceId' => $workspace->id]) }}"
                        aria-label="View details for {{ $workspace->name }}"
                    >
                        View details

                        <span aria-hidden="true">
                            &rarr;
                        </span>

                    </a>

                </div>

            </article>

        @empty

            {{-- Empty State --}}

            <section class="empty">

                <h2>
                    No workspaces yet
                </h2>

                <p>
                    Create your first workspace to start adding rooms.
                </p>

            </section>

        @endforelse

    </div>

</div>

@endsection
