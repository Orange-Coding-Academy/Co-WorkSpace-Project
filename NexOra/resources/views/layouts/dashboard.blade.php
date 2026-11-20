<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dashboard') | NexOra</title>

    <link rel="stylesheet" href="{{ asset('css/nexora/design-tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/utilities.css') }}">




    <link rel="stylesheet" href="{{ asset('css/nexora/layout.css') }}">

    @stack('styles')
</head>

<body>

    <div class="nx-dashboard">

        @include('layouts.dashboard.sidebars.admin')

        <div class="nx-dashboard__content">

            @include('layouts.dashboard.header')

            {{-- Current Page --}}
            <main class="nx-dashboard__main">
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')

</body>

</html>
