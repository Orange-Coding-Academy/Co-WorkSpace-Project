{{--
    NexOra — cinematic landing page (Blade view only; styles and scripts live in public/).

    Files:
      public/css/pages/nexora-cinematic.css
      public/js/pages/nexora-cinematic.js

    Kept from the previous version:
    - Member links only when role === 'member'; admin / spaceManager get a Dashboard button.
    - Header is transparent over the hero, `.is-solid` on scroll. On other pages add
      `is-solid is-forced` to <header> and the script will skip the scroll toggle.
    - When extracting the header into a partial: logo -> url('/'), and every "#anchor"
      -> url('/') . '#anchor'.
    - All classes are prefixed `nx-` so they do not collide with Bootstrap.

    GSAP: loaded from cdnjs below. If you already bundle GSAP with Vite, delete the two
    <script> tags and expose `window.gsap` / `window.ScrollTrigger` (or import them in the JS).
--}}
@php
    $nxUser = auth()->user();
    $nxIsMember = $nxUser && $nxUser->role === 'member';
    $nxIsStaff = $nxUser && !$nxIsMember;

    $nxStaffUrl = null;
    if ($nxIsStaff) {
        $nxStaffRoute = $nxUser->role === 'admin' ? 'admin.AdminDashboard' : 'manager.dashboard';
        $nxStaffUrl = Route::has($nxStaffRoute) ? route($nxStaffRoute) : url('/');
    }

    $nxExplore = route('explore');
    $nxRegister = route('register');
    $nxLogin = route('login');

    // "Become a Member" / plan CTAs: members go to their memberships, guests register.
    if ($nxIsMember && Route::has('member.memberships.index')) {
        $nxJoinUrl = route('member.memberships.index');
        $nxJoinLabel = 'View my memberships';
        $nxHeroSecondary = 'My Memberships';
    } elseif (!$nxUser) {
        $nxJoinUrl = $nxRegister;
        $nxJoinLabel = 'Become a member';
        $nxHeroSecondary = 'Become a Member';
    } else {
        $nxJoinUrl = '#memberships';
        $nxJoinLabel = 'See memberships';
        $nxHeroSecondary = 'Become a Member';
    }

    $nxMemberMain = [
        ['member.bookings.index', 'My Bookings', ['member.bookings.*', 'member.checkout.*']],
        ['member.memberships.index', 'My Memberships', ['member.memberships.*']],
    ];
    $nxMemberMore = [
        ['member.workspace-ai.index', 'Workspace Finder'],
        ['member.reviews.index', 'My Reviews'],
        ['member.profile.index', 'My Profile'],
    ];

    $img = fn(string $f) => asset('images/nexora/' . $f);

    // PLACEHOLDER plans and prices — connect to your real membership data / config.
    $nxPlans = [
        [
            'name' => 'Hot Desk',
            'tag' => 'Drop in, settle anywhere',
            'price' => '90',
            'per' => '/ month',
            'items' => ['Any open desk, any day', 'Fast Wi-Fi, coffee and printing', 'Community events'],
            'featured' => false,
        ],
        [
            'name' => 'Dedicated Desk',
            'tag' => 'Your desk, waiting for you',
            'price' => '160',
            'per' => '/ month',
            'items' => ['A desk that stays yours', 'Lockable storage', 'Meeting-room credit every month', 'Extended-hours access'],
            'featured' => true,
        ],
        [
            'name' => 'Private Office',
            'tag' => 'A door that closes',
            'price' => '420',
            'per' => '/ month, from',
            'items' => ['Private, lockable room for your team', 'Priority meeting-room booking', 'Dedicated phone booth access'],
            'featured' => false,
        ],
    ];

    $nxAmenities = [
        ['High-speed internet', 'Fibre-backed Wi-Fi that stays steady through video calls.', 'M2 9a15 15 0 0 1 20 0M5 12.5a10 10 0 0 1 14 0M8.5 16a5 5 0 0 1 7 0M12 19.5h.01'],
        ['Meeting rooms', 'Bookable rooms with screens and whiteboards.', 'M3 11h18M5 11v8M19 11v8M7 6h10v5H7z'],
        ['Coffee and refreshments', 'Good coffee within reach of every desk.', 'M4 9h12v6a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4zM16 11h2a2 2 0 0 1 0 4h-2M7 3v3M11 3v3'],
        ['Comfortable seating', 'Chairs chosen for long, focused days.', 'M7 4h8v8H7zM5 12h14v4H5zM8 16v4M16 16v4'],
        ['Printing facilities', 'Print, scan and copy without leaving your seat.', 'M7 9V3h10v6M7 17H5a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2M7 14h10v7H7z'],
        ['Community events', 'Talks, workshops and evenings with the people next door.', 'M4 5h16v15H4zM4 10h16M9 3v4M15 3v4'],
        ['Quiet working areas', 'Rooms where silence is the house rule.', 'M4 15v-3a8 8 0 0 1 16 0v3M4 15h3v5H4zM17 15h3v5h-3z'],
        ['24/7 access', 'Where a space offers it, your key works at any hour.', 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18zM12 7v5l3 2'],
    ];

    $nxSketches = [
        'M-34 22V-18H-6V22M-6 2H30M30 -18V22M-34 -18H30',
        'M-34 20L-16 -2L0 10L18 -18L34 -6',
        'M-30 18Q-30 -22 0 -22Q30 -22 30 4Q30 20 6 18Q-14 16 -10 0',
        'M-32 -14H-8V10H-32ZM8 -14H32V10H8ZM-8 -2H8',
        'M-34 0H34M0 -22V22M-24 -16L24 16',
    ];

    $nxPersonas = [
        ['The freelancer', 'A quiet desk, steady Wi-Fi and a good reason to leave the house.', 'workspace-abdali-lounge.jpg', 'Freelancer working alone at a desk beside a softly lit window'],
        ['The startup team', 'A table that fits everyone, and a wall to think out loud on.', 'workspace-meeting-02.jpg', 'Small team gathered around laptops at a wooden table'],
        ['The student', 'Long hours without distraction, among people who are also getting things done.', 'workspace-open-office-03.jpg', 'People studying along a long timber table with plants'],
        ['The creative', 'Room to spread out, sketch, rearrange and start again.', 'room-collaboration.jpg', 'Collaborative room with a shared table and relaxed seating'],
        ['The team meeting', 'A proper room for a conversation that matters.', 'room-boardroom.jpg', 'Bright meeting room with a round table and shelving wall'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(substr(app()->getLocale(), 0, 2), ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr' }}" class="no-js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NexOra — Find your space. Unlock your potential.</title>
    <meta name="description"
        content="NexOra is a premium coworking marketplace in Amman. Find private offices, desks, meeting rooms and a community to turn ideas into reality.">
    <meta name="theme-color" content="#151311">
    <meta property="og:type" content="website">
    <meta property="og:title" content="NexOra — Find your space. Unlock your potential.">
    <meta property="og:description" content="A workspace designed to bring people, ideas and possibilities together.">
    <meta property="og:image" content="{{ $img('workspace-lounge-01.jpg') }}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,400;0,9..144,500;1,9..144,300;1,9..144,400&family=Manrope:wght@400;500;600;700&family=Noto+Sans+Arabic:wght@400;600&display=swap" rel="stylesheet">
    <script>
        (function(d) {
            d.classList.remove('no-js');
            d.classList.add('js');
            // Reduced motion and phones get the static final composition (no pinned film).
            if (matchMedia('(prefers-reduced-motion: reduce)').matches || matchMedia('(max-width: 767px)').matches) {
                d.classList.add('nx-static');
            }
        })(document.documentElement);
    </script>
    <link rel="stylesheet" href="{{ asset('css/pages/nexora-cinematic.css') }}?v=1">
</head>

<body>
    <a class="nx-skip" href="#main">Skip to content</a>
    <noscript><div class="nx-noscript">JavaScript is off. The page is fully readable and every link works.</div></noscript>

    {{-- ================= HEADER ================= --}}
    <header class="nx-header" id="nxHeader">
        <div class="nx-wrap nx-header__in">
            <a href="#top" class="nx-logo" aria-label="NexOra, back to top">
                <svg viewBox="0 0 40 40" aria-hidden="true"><rect x="1.5" y="1.5" width="37" height="37" rx="10" /><path d="M12 29V11l16 18V11" /></svg>
                NexOra
            </a>

            <nav class="nx-nav" aria-label="Primary">
                <ul>
                    @if ($nxIsMember)
                        <li><a href="{{ $nxExplore }}">Explore</a></li>
                        @foreach ($nxMemberMain as [$n, $l, $pat])
                            @if (Route::has($n))
                                <li><a href="{{ route($n) }}" @if (request()->routeIs(...$pat)) aria-current="page" @endif>{{ $l }}</a></li>
                            @endif
                        @endforeach
                        <li class="nx-account">
                            <details>
                                <summary>{{ \Illuminate\Support\Str::before(trim($nxUser->name), ' ') }}
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6" /></svg>
                                </summary>
                                <div class="nx-account__panel">
                                    @foreach ($nxMemberMore as [$n, $l])
                                        @if (Route::has($n))
                                            <a href="{{ route($n) }}">{{ $l }}</a>
                                        @endif
                                    @endforeach
                                    @if (Route::has('logout'))
                                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Log out</button></form>
                                    @endif
                                </div>
                            </details>
                        </li>
                    @else
                        <li><a href="#spaces">Spaces</a></li>
                        <li><a href="#memberships">Memberships</a></li>
                        <li><a href="#community">Community</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    @endif
                </ul>
            </nav>

            <div class="nx-header__act">
                @guest
                    <a class="nx-signin" href="{{ $nxLogin }}">Sign in</a>
                    <a class="nx-join" href="{{ $nxRegister }}" data-magnet>Join now</a>
                @endguest
                @if ($nxIsStaff)
                    <a class="nx-btn nx-btn--sm nx-btn--solid" href="{{ $nxStaffUrl }}" data-magnet>Dashboard</a>
                @endif
                <button type="button" class="nx-burger" id="nxBurger" aria-expanded="false" aria-controls="nxMenu" aria-label="Open menu"><span></span><span></span></button>
            </div>
        </div>

        <nav class="nx-menu" id="nxMenu" aria-label="Mobile">
            <div class="nx-menu__in">
                @if ($nxIsMember)
                    <a href="{{ $nxExplore }}">Explore</a>
                    @foreach ($nxMemberMain as [$n, $l])
                        @if (Route::has($n))<a href="{{ route($n) }}">{{ $l }}</a>@endif
                    @endforeach
                    @foreach ($nxMemberMore as [$n, $l])
                        @if (Route::has($n))<a href="{{ route($n) }}">{{ $l }}</a>@endif
                    @endforeach
                    @if (Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="nx-menu__logout">Log out</button></form>
                    @endif
                @else
                    <a href="#spaces">Spaces</a>
                    <a href="#memberships">Memberships</a>
                    <a href="#community">Community</a>
                    <a href="#about">About</a>
                    <a href="#contact">Contact</a>
                    <div class="nx-menu__act">
                        @if ($nxIsStaff)
                            <a class="nx-btn nx-btn--solid" href="{{ $nxStaffUrl }}">Dashboard</a>
                        @else
                            <a class="nx-btn nx-btn--solid" href="{{ $nxRegister }}">Join now</a>
                            <a class="nx-btn nx-btn--line" href="{{ $nxLogin }}">Sign in</a>
                        @endif
                    </div>
                @endif
            </div>
        </nav>
    </header>

    <main id="main">

        {{-- ================= HERO — THE FILM ================= --}}
        <section class="nx-hero" id="top" aria-labelledby="hero-title">
            <div class="nx-hero__pin" id="heroPin">

                <svg class="nx-scene" id="scene" viewBox="0 0 1600 900" preserveAspectRatio="xMidYMid slice" aria-hidden="true" focusable="false">
                    <defs>
                        <linearGradient id="gSky" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#14110f" /><stop offset=".55" stop-color="#2f251d" /><stop offset="1" stop-color="#5b4630" /></linearGradient>
                        <linearGradient id="gWall" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#d9cab0" /><stop offset="1" stop-color="#bda987" /></linearGradient>
                        <linearGradient id="gFloor" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#8d6139" /><stop offset="1" stop-color="#53351d" /></linearGradient>
                        <linearGradient id="gWin" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#fff4d8" /><stop offset="1" stop-color="#f1d9a6" /></linearGradient>
                        <linearGradient id="gDoor" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffe2ae" /><stop offset="1" stop-color="#ffb867" /></linearGradient>
                        <linearGradient id="gSpill" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffc57a" stop-opacity=".75" /><stop offset="1" stop-color="#ffc57a" stop-opacity="0" /></linearGradient>
                        <linearGradient id="gCone" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ffd9a0" stop-opacity=".5" /><stop offset="1" stop-color="#ffd9a0" stop-opacity="0" /></linearGradient>
                        <radialGradient id="gGlow"><stop offset="0" stop-color="#ffc57a" stop-opacity=".95" /><stop offset="1" stop-color="#ffc57a" stop-opacity="0" /></radialGradient>
                        <pattern id="pSlat" width="26" height="10" patternUnits="userSpaceOnUse"><rect width="21" height="10" fill="#3b322a" /></pattern>
                    </defs>

                    {{-- ======== INSIDE ======== --}}
                    <g id="worldIn">
                        <g id="room">
                            <rect width="1600" height="110" fill="#2b2723" />
                            <rect y="86" width="1600" height="24" fill="#1d1a17" />
                            <rect y="110" width="1600" height="540" fill="url(#gWall)" />

                            {{-- timber slat wall --}}
                            <g id="slats">
                                @for ($i = 0; $i < 16; $i++)
                                    <rect class="nx-slat" x="{{ 18 + $i * 40 }}" y="110" width="28" height="540" fill="{{ $i % 2 ? '#8a5a33' : '#9c6a3d' }}" />
                                @endfor
                            </g>
                            <rect x="936" y="110" width="40" height="540" fill="#8b857b" />

                            {{-- window --}}
                            <g id="win">
                                <rect x="1000" y="170" width="520" height="340" fill="url(#gWin)" />
                                <g fill="#cdb98f" opacity=".7"><rect x="1030" y="360" width="60" height="150" /><rect x="1110" y="320" width="80" height="190" /><rect x="1220" y="380" width="50" height="130" /><rect x="1300" y="300" width="90" height="210" /><rect x="1410" y="350" width="70" height="160" /></g>
                                <g fill="none" stroke="#2b2723" stroke-width="10"><rect x="1000" y="170" width="520" height="340" /><path d="M1173 170v340M1347 170v340M1000 340h520" /></g>
                            </g>
                            <polygon id="beam" points="1000,510 1520,510 1580,900 880,900" fill="#fff3d0" opacity=".16" />

                            {{-- floor --}}
                            <rect y="650" width="1600" height="250" fill="url(#gFloor)" />
                            @for ($i = 1; $i < 8; $i++)
                                <line x1="0" x2="1600" y1="{{ 650 + $i * $i * 5 }}" y2="{{ 650 + $i * $i * 5 }}" stroke="#3d2614" stroke-opacity=".45" />
                            @endfor

                            {{-- plants --}}
                            @foreach ([[70, 1.0], [700, .8], [1548, 1.05]] as [$px, $ps])
                                <g transform="translate({{ $px }} 700) scale({{ $ps }})">
                                    <path class="nx-pot" d="M-28 0 L28 0 L22 -48 L-22 -48Z" fill="#c9b48e" />
                                    <g transform="translate(0 -46)">
                                        @foreach ([-52, -32, -12, 8, 28, 46, 62] as $li => $la)
                                            <g transform="rotate({{ $la }})"><ellipse class="nx-leaf" cx="0" cy="-62" rx="15" ry="62" fill="{{ $li % 2 ? '#61804f' : '#4c6742' }}" /></g>
                                        @endforeach
                                    </g>
                                </g>
                            @endforeach

                            {{-- desks, chairs, objects --}}
                            @foreach ([820, 1130, 1430] as $di => $dx)
                                <g transform="translate({{ $dx }} 0)">
                                    <g class="nx-chair"><rect x="-46" y="505" width="92" height="120" rx="16" fill="#2f2a25" /><rect x="-40" y="512" width="80" height="12" rx="6" fill="#473f37" /></g>

                                    @if ($di === 1)
                                        <g class="nx-sitter">
                                            <path d="M-36 600 L-32 528 Q0 506 32 528 L36 600Z" fill="#d9c7a6" />
                                            <circle cx="0" cy="488" r="22" fill="#ecd9ba" />
                                            <path d="M-22 486 A22 22 0 0 1 22 486 Q8 474 -22 486Z" fill="#2a1f17" />
                                            <path d="M-30 540 L-44 584 L-18 590 M30 540 L44 584 L18 590" stroke="#d9c7a6" stroke-width="14" stroke-linecap="round" stroke-linejoin="round" fill="none" />
                                        </g>
                                    @endif

                                    <g class="nx-desk">
                                        <rect x="-150" y="588" width="300" height="16" rx="3" fill="#a8733f" />
                                        <rect x="-150" y="604" width="300" height="7" fill="#6f4725" />
                                        <rect x="-138" y="611" width="10" height="92" fill="#3b2f25" />
                                        <rect x="128" y="611" width="10" height="92" fill="#3b2f25" />
                                        <rect x="-128" y="618" width="256" height="46" fill="#4a3624" />
                                    </g>

                                    <g class="nx-lap"><rect x="-46" y="522" width="92" height="64" rx="4" fill="#8d867c" /><circle cx="0" cy="554" r="5" fill="#b8935a" /></g>
                                    <rect x="-54" y="584" width="108" height="5" rx="2" fill="#6b655c" />
                                    <g class="nx-cup"><rect x="78" y="566" width="18" height="22" rx="4" fill="#f3ecdf" /><path class="nx-steam" d="M84 560q-4-8 0-14M91 560q-4-8 0-14" stroke="#f3ecdf" stroke-opacity=".6" fill="none" stroke-linecap="round" /></g>
                                    <g class="nx-book"><rect x="-128" y="574" width="52" height="14" fill="#5b6b5a" /><rect x="-124" y="560" width="44" height="14" fill="#b0623f" transform="rotate(-3 -124 574)" /></g>
                                    <rect class="nx-paper" x="20" y="583" width="44" height="4" fill="#f6f0e2" />
                                    <rect class="nx-paper" x="24" y="579" width="40" height="4" fill="#ece3cf" />
                                    <rect class="nx-paper" x="-100" y="583" width="36" height="4" fill="#f6f0e2" />
                                </g>
                            @endforeach

                            {{-- lighting: dim layer, then lights above it --}}
                            <rect id="dim" width="1600" height="900" fill="#0d0b0a" />

                            @foreach ([520, 820, 1130, 1430] as $px)
                                <g transform="translate({{ $px }} 0)">
                                    <g class="nx-pend">
                                        <line x1="0" y1="110" x2="0" y2="244" stroke="#1d1a17" stroke-width="3" />
                                        <path d="M-46 282 Q-46 246 0 240 Q46 246 46 282Z" fill="#1d1a17" />
                                        <rect x="-46" y="280" width="92" height="4" fill="#b8935a" />
                                    </g>
                                    <polygon class="nx-pcone" points="-40,284 40,284 170,640 -170,640" fill="url(#gCone)" />
                                    <circle class="nx-pglow" cy="294" r="150" fill="url(#gGlow)" />
                                </g>
                            @endforeach

                            {{-- ideas --}}
                            <g id="ideas">
                                <circle id="ideaGlow" cx="1130" cy="440" r="210" fill="url(#gGlow)" opacity=".28" />
                                @for ($i = 0; $i < 18; $i++)
                                    @php
                                        $a = $i * 2.399;
                                        $r = 50 + ($i % 6) * 22;
                                    @endphp
                                    <circle class="nx-pt" cx="{{ round(1130 + cos($a) * $r * 1.5) }}" cy="{{ round(420 + sin($a) * $r * .9) }}" r="{{ 2 + ($i % 3) }}" fill="#ffc57a" />
                                @endfor

                                <g id="constellation" fill="none" stroke="#b8935a" stroke-width="1.6" stroke-linecap="round">
                                    <path class="nx-conn" pathLength="1" d="M960 330L1010 280L1070 320" />
                                    <path class="nx-conn" pathLength="1" d="M1070 320L1000 380L960 330" />
                                    <path class="nx-conn" pathLength="1" d="M930 410L1000 380" />
                                    <path class="nx-conn" pathLength="1" d="M1010 280L1058 244" />
                                    @foreach ([[960, 330], [1010, 280], [1070, 320], [1000, 380], [930, 410], [1058, 244]] as [$nx, $ny])
                                        <circle class="nx-node" cx="{{ $nx }}" cy="{{ $ny }}" r="5.5" fill="#151311" />
                                    @endforeach
                                </g>

                                <g id="bulb">
                                    <circle cx="1130" cy="418" r="17" fill="#ffd9a0" />
                                    <rect x="1122" y="433" width="16" height="9" rx="2" fill="#b8935a" />
                                    <path d="M1124 418l3 -6 3 6 3 -6 3 6" stroke="#8a5a33" stroke-width="1.5" fill="none" />
                                    <g stroke="#ffd9a0" stroke-width="2.4" stroke-linecap="round">
                                        @foreach (range(0, 6) as $ri)
                                            @php $ra = deg2rad(-150 + $ri * 50); @endphp
                                            <line class="nx-ray" x1="{{ round(1130 + cos($ra) * 28) }}" y1="{{ round(418 + sin($ra) * 28) }}" x2="{{ round(1130 + cos($ra) * 42) }}" y2="{{ round(418 + sin($ra) * 42) }}" />
                                        @endforeach
                                    </g>
                                </g>

                                @foreach (range(0, 4) as $ci)
                                    <g class="nx-card" data-k="{{ $ci }}">
                                        <g class="nx-cardin">
                                            <rect x="-48" y="-34" width="96" height="68" rx="4" fill="#f3ecdf" fill-opacity=".94" />
                                            <path class="nx-sketch" pathLength="1" fill="none" stroke="#2a2520" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                d="{{ $nxSketches[$ci] }}" />
                                        </g>
                                    </g>
                                @endforeach
                            </g>
                        </g>
                    </g>

                    {{-- ======== OUTSIDE ======== --}}
                    <g id="worldOut">
                        <g id="cam">
                            <rect width="1600" height="900" fill="url(#gSky)" />
                            <circle cx="380" cy="640" r="420" fill="url(#gGlow)" opacity=".12" />
                            <g fill="#12100e" opacity=".9">
                                <rect x="0" y="470" width="140" height="240" /><rect x="150" y="420" width="110" height="290" /><rect x="270" y="520" width="170" height="190" /><rect x="450" y="450" width="120" height="260" /><rect x="580" y="500" width="150" height="210" /><rect x="740" y="430" width="120" height="280" />
                            </g>
                            <rect y="700" width="1600" height="200" fill="#171310" />
                            <rect y="700" width="1600" height="2" fill="#4a3b2d" />

                            @foreach ([300, 640] as $lx)
                                <g transform="translate({{ $lx }} 700)">
                                    <rect x="-4" y="-330" width="8" height="330" fill="#2a231d" />
                                    <circle cy="-340" r="110" fill="url(#gGlow)" opacity=".5" />
                                    <circle cy="-340" r="9" fill="#ffd9a0" />
                                </g>
                            @endforeach

                            {{-- facade --}}
                            <rect x="880" y="140" width="720" height="560" fill="#26211d" />
                            <rect x="880" y="170" width="720" height="530" fill="url(#pSlat)" opacity=".55" />
                            <rect x="880" y="140" width="720" height="30" fill="#1a1613" />

                            <g id="sconces">
                                @foreach ([1070, 1430] as $sx)
                                    <circle cx="{{ $sx }}" cy="330" r="120" fill="url(#gGlow)" opacity=".55" />
                                    <rect x="{{ $sx - 5 }}" y="318" width="10" height="24" rx="3" fill="#ffd9a0" />
                                @endforeach
                            </g>

                            <g id="doorGrp">
                                <rect x="1154" y="284" width="192" height="416" fill="#100d0b" stroke="#b8935a" stroke-width="3" />
                                <rect id="doorLight" x="1170" y="300" width="160" height="400" fill="url(#gDoor)" />
                                <g id="doorLeaf">
                                    <rect x="1170" y="300" width="160" height="400" fill="#8c5e35" />
                                    <rect x="1186" y="318" width="128" height="150" fill="none" stroke="#6b4426" stroke-width="3" />
                                    <rect x="1186" y="488" width="128" height="196" fill="none" stroke="#6b4426" stroke-width="3" />
                                    <circle cx="1314" cy="510" r="6" fill="#d8b878" />
                                </g>
                                <polygon id="spill" points="1170,700 1330,700 1560,900 940,900" fill="url(#gSpill)" />
                            </g>

                            {{-- the person --}}
                            <g transform="translate(0 700)">
                                <g id="walker">
                                    <ellipse cx="0" cy="2" rx="46" ry="7" fill="#000" opacity=".35" />
                                    <rect id="legL" x="-14" y="-118" width="15" height="116" rx="7" fill="#b9a584" />
                                    <rect id="legR" x="2" y="-118" width="15" height="116" rx="7" fill="#e6d6b8" />
                                    <rect x="-34" y="-176" width="26" height="40" rx="5" fill="#7a4f2b" />
                                    <path d="M-27 -222 Q-36 -160 -30 -108 L30 -108 Q36 -160 27 -222 Q0 -240 -27 -222Z" fill="#e0cfae" />
                                    <g id="armB"><rect x="-8" y="-214" width="14" height="94" rx="7" fill="#cdbb98" /></g>
                                    <g id="head"><circle cx="0" cy="-250" r="20" fill="#ecd9ba" /><path d="M-20 -252 A20 20 0 0 1 20 -252 Q8 -262 -20 -252Z" fill="#2a1f17" /></g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>

                <div class="nx-hero__scrim" id="scrim"></div>

                <div class="nx-wrap nx-hero__ui">
                    <ol class="nx-cap" id="caps" aria-hidden="true">
                        <li>Somewhere in the city, someone is looking for a place to begin.</li>
                        <li>Then, a door.</li>
                        <li>Inside, the room comes together around them.</li>
                        <li>And ideas find the space to grow.</li>
                    </ol>

                    <div class="nx-final" id="final">
                        <h1 id="hero-title"><span>Find your space.</span> <em>Unlock your potential.</em></h1>
                        <p>A workspace designed to bring people, ideas and possibilities together.</p>
                        <div class="nx-final__cta">
                            <a class="nx-btn nx-btn--glow" href="{{ $nxExplore }}" data-magnet>Explore Our Spaces</a>
                            <a class="nx-btn nx-btn--line" href="{{ $nxJoinUrl }}" data-magnet>{{ $nxHeroSecondary }}</a>
                        </div>
                    </div>
                </div>

                <a href="#philosophy" class="nx-cue" id="cue"><span>Scroll to begin</span><i aria-hidden="true"></i></a>
                <div class="nx-hero__bar" aria-hidden="true"><i id="heroBar"></i></div>
            </div>
        </section>

        {{-- ================= A. PHILOSOPHY ================= --}}
        <section class="nx-sec nx-light nx-phil" id="philosophy" aria-labelledby="phil-title">
            <div class="nx-wrap">
                <div class="nx-phil__head">
                    <h2 class="nx-h2" id="phil-title" data-split>Not just a place to work. A place to become.</h2>
                    <p class="nx-lede">Desks and Wi-Fi are the easy part. We build rooms where focus comes naturally, where a conversation at the coffee machine turns into a plan, and where a Monday idea has company by Friday.</p>
                </div>

                <div class="nx-phil__stage">
                    <div class="nx-phil__big" data-speed=".05"><div class="nx-mask"><img src="{{ $img('workspace-lounge-01.jpg') }}" alt="Bright coworking lounge with warm timber details and city views" loading="lazy" decoding="async" width="1600" height="1067"></div></div>
                    <div class="nx-phil__small" data-speed=".14"><div class="nx-mask"><img src="{{ $img('workspace-shmeisani-office.jpg') }}" alt="Two people shaking hands across a desk in soft window light" loading="lazy" decoding="async" width="900" height="600"></div></div>
                    <dl class="nx-pillars">
                        <div><dt>Creativity</dt><dd>Light, material and quiet that make it easier to start.</dd></div>
                        <div><dt>Productivity</dt><dd>Everything you need within reach, nothing you do not.</dd></div>
                        <div><dt>Collaboration</dt><dd>Tables, rooms and neighbours built for working together.</dd></div>
                        <div><dt>Growth</dt><dd>Move from a desk to a team to an office, without moving out.</dd></div>
                    </dl>
                </div>
            </div>
        </section>

        {{-- ================= B. SPACES ================= --}}
        <section class="nx-sec nx-dark nx-spaces" id="spaces" aria-labelledby="spaces-title">
            <div class="nx-wrap">
                <div class="nx-sechead">
                    <h2 class="nx-h2" id="spaces-title" data-split>Explore our spaces.</h2>
                    <p class="nx-lede">From a door that closes to a table that seats the whole team. Choose the room your work needs today.</p>
                </div>
                <div class="nx-spgrid">
                    @foreach ([
                        ['Private Offices', 'A door that closes and a desk that stays yours.', 'workspace-abdali-lounge.jpg', 'Private office corner with a timber desk and mesh chair', 'a'],
                        ['Dedicated Desks', 'Your own desk in a room full of people who get things done.', 'workspace-shmeisani-office.jpg', 'Desk with a laptop and coffee cups in soft window light', 'b'],
                        ['Hot Desks', 'Bring a laptop. Sit anywhere the day takes you.', 'workspace-open-office-03.jpg', 'Sunlit shared workspace with long wooden tables and plants', 'c'],
                        ['Meeting Rooms', 'For pitches, workshops and conversations that deserve a proper table.', 'room-boardroom.jpg', 'Meeting room with a round table and dark shelving wall', 'd'],
                        ['Creative Collaboration Areas', 'Room to sketch, rearrange and think out loud together.', 'room-collaboration.jpg', 'Collaborative room with a shared table and relaxed seating', 'e'],
                    ] as [$t, $d, $f, $alt, $k])
                        <a class="nx-sp nx-sp--{{ $k }}" href="{{ $nxExplore }}">
                            <span class="nx-sp__img nx-mask"><img src="{{ $img($f) }}" alt="{{ $alt }}" loading="lazy" decoding="async"></span>
                            <span class="nx-sp__txt"><strong>{{ $t }}</strong><span>{{ $d }}</span></span>
                            <span class="nx-sp__go" aria-hidden="true">&nearr;</span>
                        </a>
                    @endforeach
                </div>
                <p class="nx-note">Photography is illustrative. Current listings and availability are on Explore.</p>
            </div>
        </section>

        {{-- ================= C. WAYS OF WORKING ================= --}}
        <section class="nx-sec nx-light nx-ways" id="ways" aria-labelledby="ways-title">
            <div class="nx-wrap">
                <h2 class="nx-h2" id="ways-title" data-split>Designed for every way of working.</h2>
                <div class="nx-ways__grid">
                    <ul class="nx-personas" id="personas">
                        @foreach ($nxPersonas as $i => [$name, $line, $f, $alt])
                            <li class="nx-persona {{ $i === 0 ? 'is-open' : '' }}">
                                <button type="button" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="persona-{{ $i }}" data-i="{{ $i }}"><span>{{ $name }}</span><i aria-hidden="true"></i></button>
                                <div class="nx-persona__panel" id="persona-{{ $i }}">
                                    <div>
                                        <p>{{ $line }}</p>
                                        <img class="nx-persona__inline" src="{{ $img($f) }}" alt="{{ $alt }}" loading="lazy" decoding="async">
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="nx-ways__stack" aria-hidden="true">
                        @foreach ($nxPersonas as $i => [$name, $line, $f, $alt])
                            <img class="{{ $i === 0 ? 'is-on' : '' }}" src="{{ $img($f) }}" alt="" loading="lazy" decoding="async">
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= D. MEMBERSHIP PLANS ================= --}}
        <section class="nx-sec nx-dark nx-plans" id="memberships" aria-labelledby="plans-title">
            <div class="nx-wrap">
                <div class="nx-sechead">
                    <h2 class="nx-h2" id="plans-title" data-split>Membership, made simple.</h2>
                    <p class="nx-lede">Three ways to belong. Pick the one that fits how you work, and change it when your work changes.</p>
                </div>
                <div class="nx-plangrid">
                    @foreach ($nxPlans as $p)
                        <article class="nx-plan {{ $p['featured'] ? 'is-featured' : '' }}">
                            @if ($p['featured'])<span class="nx-plan__flag">Best for regulars</span>@endif
                            <h3>{{ $p['name'] }}</h3>
                            <p class="nx-plan__tag">{{ $p['tag'] }}</p>
                            <p class="nx-plan__price"><span>JOD</span> {{ $p['price'] }} <small>{{ $p['per'] }}</small></p>
                            <ul>
                                @foreach ($p['items'] as $it)<li>{{ $it }}</li>@endforeach
                            </ul>
                            <a class="nx-btn {{ $p['featured'] ? 'nx-btn--glow' : 'nx-btn--dark' }}" href="{{ $nxJoinUrl }}" data-magnet>{{ $nxJoinLabel }}</a>
                        </article>
                    @endforeach
                </div>
                <p class="nx-note">Prices shown are examples. Final rates are confirmed at checkout.</p>
            </div>
        </section>

        {{-- ================= E. AMENITIES ================= --}}
        <section class="nx-sec nx-light nx-amen" id="amenities" aria-labelledby="amen-title">
            <div class="nx-wrap">
                <div class="nx-sechead">
                    <h2 class="nx-h2" id="amen-title" data-split>Everything the day asks for.</h2>
                    <p class="nx-lede">The small things that make a good day of work, handled before you arrive.</p>
                </div>
                <ul class="nx-amen__grid">
                    @foreach ($nxAmenities as [$t, $d, $path])
                        <li class="nx-am">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path pathLength="1" d="{{ $path }}" /></svg>
                            <h3>{{ $t }}</h3>
                            <p>{{ $d }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- ================= F. COMMUNITY ================= --}}
        <section class="nx-sec nx-dark nx-comm" id="community" aria-labelledby="comm-title">
            <div class="nx-wrap nx-comm__grid">
                <div class="nx-comm__copy">
                    <h2 class="nx-h2" id="comm-title" data-split>A building is not a community. People are.</h2>
                    <p class="nx-lede">The best part of a good space is who is in it: the designer who unblocks your pitch, the founder who has been where you are, the neighbour who becomes a client.</p>
                    <ul class="nx-comm__list">
                        <li>Member evenings, talks and workshops through the year.</li>
                        <li>Shared tables where introductions happen on their own.</li>
                        <li>A team on site who know your name and what you are building.</li>
                    </ul>
                    <a class="nx-btn nx-btn--line" href="{{ $nxJoinUrl }}" data-magnet>{{ $nxJoinLabel }}</a>
                </div>
                {{-- Replace these with real member portraits when you have them. --}}
                <div class="nx-comm__collage" aria-hidden="true">
                    <figure class="nx-pf nx-pf--1" data-speed=".06"><img src="{{ $img('workspace-abdali-lounge.jpg') }}" alt="" loading="lazy" decoding="async"></figure>
                    <figure class="nx-pf nx-pf--2" data-speed=".16"><img src="{{ $img('workspace-meeting-02.jpg') }}" alt="" loading="lazy" decoding="async"></figure>
                    <figure class="nx-pf nx-pf--3" data-speed=".1"><img src="{{ $img('room-collaboration.jpg') }}" alt="" loading="lazy" decoding="async"></figure>
                    <figure class="nx-pf nx-pf--4" data-speed=".2"><img src="{{ $img('workspace-khalda-lounge.jpg') }}" alt="" loading="lazy" decoding="async"></figure>
                </div>
            </div>
        </section>

        {{-- ================= G. FINAL CTA ================= --}}
        <section class="nx-final-cta" id="join" aria-labelledby="join-title">
            <img class="nx-final-cta__bg" src="{{ $img('workspace-lounge-04.jpg') }}" alt="" loading="lazy" decoding="async">
            <div class="nx-final-cta__shade"></div>
            <div class="nx-wrap nx-final-cta__in">
                <div class="nx-final-cta__copy">
                    <h2 class="nx-h2" id="join-title" data-split>Your next chapter starts here.</h2>
                    <p class="nx-lede">Find your space. Meet your people. Make things happen.</p>
                    <a class="nx-btn nx-btn--glow nx-btn--lg" href="{{ $nxUser ? $nxJoinUrl : $nxRegister }}" data-magnet>Join Our Community</a>
                </div>
                <div class="nx-arch" aria-hidden="true"><div class="nx-mask"><img src="{{ $img('workspace-lounge-01.jpg') }}" alt="" loading="lazy" decoding="async"></div></div>
            </div>
        </section>
    </main>

    {{-- ================= FOOTER ================= --}}
    <footer class="nx-footer" id="about" aria-labelledby="footer-title">
        <h2 id="footer-title" class="nx-sr">About NexOra</h2>
        <div class="nx-wrap">
            <div class="nx-footer__top">
                <div>
                    <a href="#top" class="nx-logo" aria-label="NexOra, back to top">
                        <svg viewBox="0 0 40 40" aria-hidden="true"><rect x="1.5" y="1.5" width="37" height="37" rx="10" /><path d="M12 29V11l16 18V11" /></svg>
                        NexOra
                    </a>
                    <p>A coworking marketplace in Amman, connecting people with the spaces where they do their best work.</p>
                </div>
                <nav class="nx-footer__cols" aria-label="Footer">
                    <div>
                        <h3>Spaces</h3>
                        <a href="{{ $nxExplore }}">Explore workspaces</a>
                        <a href="#memberships">Memberships</a>
                        <a href="#amenities">Amenities</a>
                    </div>
                    <div>
                        <h3>Community</h3>
                        <a href="#community">Events and people</a>
                        <a href="#contact">List your workspace</a>
                    </div>
                    <div id="contact">
                        <h3>Contact</h3>
                        {{-- TODO: replace with NexOra's real contact address. --}}
                        <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
                        <a href="#join">Join our community</a>
                    </div>
                    <div>
                        <h3>Account</h3>
                        @if ($nxIsMember)
                            @if (Route::has('member.bookings.index'))<a href="{{ route('member.bookings.index') }}">My Bookings</a>@endif
                            @if (Route::has('member.profile.index'))<a href="{{ route('member.profile.index') }}">My Profile</a>@endif
                        @elseif ($nxIsStaff)
                            <a href="{{ $nxStaffUrl }}">Dashboard</a>
                        @else
                            <a href="{{ $nxLogin }}">Sign in</a>
                            <a href="{{ $nxRegister }}">Create an account</a>
                        @endif
                    </div>
                </nav>
            </div>
            <div class="nx-footer__bottom">
                <p>&copy; {{ date('Y') }} NexOra. All rights reserved.</p>
                <p>Photography is illustrative and does not depict specific listings.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
    <script src="{{ asset('js/pages/nexora-cinematic.js') }}?v=1" defer></script>
</body>

</html>
