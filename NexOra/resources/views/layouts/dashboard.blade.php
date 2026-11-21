@php
    $nxRole = trim($__env->yieldContent('dashboard-role', 'admin'));
    if (!in_array($nxRole, ['admin', 'manager', 'member'], true)) {
        $nxRole = 'admin';
    }
    $nxTitle = trim($__env->yieldContent('title', 'Dashboard'));
    $nxTitleFull = stripos($nxTitle, 'nexora') === false ? $nxTitle . ' | NexOra' : $nxTitle;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{!! $nxTitleFull !!}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/nexora/design-tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/chat-widget.css') }}">

    @stack('styles')

    @include('layouts.dashboard.partials.shell-styles')
</head>

<body class="nx-shell-body">

    <a href="#nx-main" class="nx-skip-link">Skip to main content</a>

    <div class="nx-dashboard" id="nxDashboard" data-role="{{ $nxRole }}">

        @include('layouts.dashboard.sidebars.' . $nxRole)

        <div class="nx-dashboard__content">

            @include('layouts.dashboard.header', ['nxRole' => $nxRole])

            <main class="nx-dashboard__main" id="nx-main" tabindex="-1">
                @yield('content')
            </main>

        </div>
    </div>

    @include('layouts.dashboard.partials.shell-script')

    @stack('scripts')
    @if ($nxRole === 'member')
        @include('Member.partials.chat-widget')
    @endif
</body>

</html>
