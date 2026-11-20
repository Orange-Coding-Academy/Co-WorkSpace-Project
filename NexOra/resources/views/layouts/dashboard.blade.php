
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') | NexOra</title>

    <link rel="stylesheet" href="{{ asset('css/nexora/design-tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/utilities.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/pages-extracted.css') }}">

    @stack('styles')
</head>

<body>

    <div class="nx-dashboard">

        @if(request()->routeIs('manager.*'))
            @include('layouts.dashboard.sidebars.manager')
        @else
            @include('layouts.dashboard.sidebars.admin')
        @endif

        <div class="nx-dashboard__content">

            @include('layouts.dashboard.header')

            <main class="nx-dashboard__main">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')

</body>
</html>
