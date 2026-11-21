{{--
    NexOra landing page — single self-contained Blade view.
    Public destinations are connected to the routes currently defined in web.php.
    Workspace-manager onboarding and public membership browsing routes were not
    provided, so those links stay on this page rather than pointing to private dashboards.

    NOTES FOR THE NEXT DEVELOPER (header refactor):
    - Member (customer) links are shown ONLY when role === 'member'. Admin / spaceManager
      users see a "Dashboard" button instead (plain @auth would show member links to them).
    - The header is transparent over the hero and becomes `.is-solid` on scroll. On any other
      page (My Bookings, etc.) it must be solid all the time: add `is-solid is-forced` on the
      <header> and skip updateHeader() when `is-forced` is present.
    - When the header is extracted into a shared partial, the logo must point to url('/') and
      every "#anchor" link must become url('/') . '#anchor'.
    - Header class names (.btn, .nav-link, .logo, .wrap) collide with Bootstrap. Prefix them
      with `nx-` before using the header inside pages that load Bootstrap.
--}}
@php
    $nxUrls = [
        'explore' => route('explore'),
        'login' => route('login'),
        'register' => route('register'),
    ];

    $nxCta = function (string $key, string $label, string $class, string $testId) use ($nxUrls) {
        $arrow = '<span class="arrow" aria-hidden="true">&rarr;</span>';
        $url = $nxUrls[$key] ?? '#';
        $isTextual = in_array($class, ['f-link', 'signin'], true);

        return '<a class="' .
            e($class) .
            '" href="' .
            e($url) .
            '" data-testid="' .
            e($testId) .
            '">' .
            e($label) .
            ($isTextual ? '' : ' ' . $arrow) .
            '</a>';
    };

    // ---- Auth / role state -------------------------------------------------
    $nxUser = auth()->user();
    $nxIsMember = $nxUser && $nxUser->role === 'member';
    $nxIsStaff = $nxUser && !$nxIsMember; // admin / spaceManager
    $nxStaffUrl = null;
    if ($nxIsStaff) {
        $nxStaffRoute = $nxUser->role === 'admin' ? 'admin.AdminDashboard' : 'manager.dashboard';
        $nxStaffUrl = Route::has($nxStaffRoute) ? route($nxStaffRoute) : url('/');
    }

    // Member links shown directly in the header: [route name, label, active patterns]
    $nxMemberMain = [
        ['member.bookings.index', 'My Bookings', ['member.bookings.*', 'member.checkout.*']],
        ['member.memberships.index', 'My Memberships', ['member.memberships.*']],
    ];
    // Secondary member links inside the account dropdown: [route name, label]
    $nxMemberMore = [
        ['member.workspace-ai.index', 'Workspace Finder'],
        ['member.reviews.index', 'My Reviews'],
        ['member.profile.index', 'My Profile'],
    ];
    // Flat list used by the mobile menu
    $nxMemberMobile = [];
    foreach ($nxMemberMain as $nxL) {
        $nxMemberMobile[] = [$nxL[0], $nxL[1]];
    }
    foreach ($nxMemberMore as $nxL) {
        $nxMemberMobile[] = [$nxL[0], $nxL[1]];
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(substr(app()->getLocale(), 0, 2), ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr' }}" class="no-js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NexOra — Find your space. Make your next move.</title>
    <meta name="description"
        content="NexOra is a coworking marketplace. Discover workspaces, book rooms and explore memberships — or bring your own workspace to people looking for one.">
    <meta name="theme-color" content="#203D34">
    <meta property="og:type" content="website">
    <meta property="og:title" content="NexOra — Find your space. Make your next move.">
    <meta property="og:description"
        content="A coworking marketplace connecting professionals with workspaces, and workspace managers with the people looking for them.">
    <meta property="og:image"
        content="https://images.pexels.com/photos/1170412/pexels-photo-1170412.jpeg?auto=compress&cs=tinysrgb&w=1200">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.pexels.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap"
        rel="stylesheet">
    <script>
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
    </script>

    <style>
        /* ============ Tokens ============ */
        :root {
            --nx-primary: #315C4D;
            --nx-primary-hover: #274B3F;
            --nx-primary-active: #203D34;
            --nx-secondary: #6F806E;
            --nx-accent: #C58A62;

            --nx-bg: #F5F7F3;
            --nx-surface: #F8FAF7;
            --nx-card: #FFFFFF;
            --nx-sidebar: #203D34;

            --nx-text: #1D2924;
            --nx-text-secondary: #53615A;
            --nx-text-muted: #7F8B84;

            --nx-border: #E2E8E2;
            --nx-divider: #E9EEE9;
            --nx-border-strong: #C9D4CC;

            --nx-deep: #152A23;
            --nx-sand: #EEE7DE;
            --nx-accent-soft: #E6C9B3;

            --font-display: 'Manrope', 'Noto Sans Arabic', system-ui, sans-serif;
            --font-body: 'Inter', 'Noto Sans Arabic', system-ui, sans-serif;

            --max: 1200px;
            --pad: 40px;
            --header: 76px;
            --section: 88px;
            --r-sm: 8px;
            --r-md: 12px;
            --r-lg: 16px;
            --r-xl: 24px;
            --ease: cubic-bezier(.2, .7, .2, 1);
        }

        @media (max-width: 1023px) {
            :root {
                --pad: 28px;
                --section: 76px;
            }
        }

        @media (max-width: 767px) {
            :root {
                --pad: 20px;
                --section: 64px;
                --header: 64px;
            }
        }

        /* ============ Base ============ */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
            background: var(--nx-bg);
            color: var(--nx-text);
            font-family: var(--font-body);
            font-size: 16px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        body::after {
            /* paper grain */
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 100;
            opacity: .05;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence baseFrequency='.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        }

        img {
            display: block;
            max-width: 100%;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: var(--font-display);
            margin: 0;
            letter-spacing: -0.025em;
            line-height: 1.08;
            font-weight: 700;
        }

        p {
            margin: 0;
        }

        ul,
        ol {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        a {
            color: inherit;
        }

        button {
            font: inherit;
            color: inherit;
        }

        :focus-visible {
            outline: 2px solid var(--nx-accent);
            outline-offset: 3px;
            border-radius: 4px;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0 0 0 0);
            white-space: nowrap;
            border: 0;
        }

        .skip {
            position: absolute;
            left: 16px;
            top: -60px;
            z-index: 200;
            background: var(--nx-primary);
            color: #fff;
            padding: 10px 16px;
            border-radius: var(--r-sm);
            text-decoration: none;
        }

        .skip:focus {
            top: 12px;
        }

        .wrap {
            width: 100%;
            max-width: calc(var(--max) + var(--pad) * 2);
            margin: 0 auto;
            padding: 0 var(--pad);
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-family: var(--font-display);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--nx-accent);
        }

        .eyebrow .idx {
            color: var(--nx-text-muted);
            font-weight: 600;
        }

        .eyebrow::before {
            content: "";
            width: 28px;
            height: 1px;
            background: currentColor;
        }

        .h2 {
            font-size: clamp(32px, 4.4vw, 52px);
        }

        .lede {
            font-size: 17px;
            color: var(--nx-text-secondary);
            max-width: 46ch;
        }

        .muted-note {
            font-size: 13px;
            color: var(--nx-text-muted);
        }

        /* ============ Buttons ============ */
        .btn {
            --h: 52px;
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            min-height: var(--h);
            padding: 0 24px;
            border-radius: 999px;
            border: 1px solid transparent;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 15px;
            letter-spacing: -.005em;
            text-decoration: none;
            cursor: pointer;
            overflow: hidden;
            isolation: isolate;
            transition: background-color .3s var(--ease), color .3s var(--ease), border-color .3s var(--ease), transform .3s var(--ease);
        }

        .btn-sm {
            --h: 44px;
            padding: 0 18px;
            font-size: 14px;
        }

        .btn .arrow {
            display: inline-block;
            transition: transform .35s var(--ease);
        }

        .btn:hover .arrow {
            transform: translateX(4px);
        }

        [dir="rtl"] .btn .arrow {
            transform: scaleX(-1);
        }

        [dir="rtl"] .btn:hover .arrow {
            transform: scaleX(-1) translateX(4px);
        }

        .btn:active {
            transform: scale(.98);
        }

        .btn-primary {
            background: var(--nx-primary);
            color: var(--nx-bg);
        }

        .btn-primary:hover {
            background: var(--nx-primary-hover);
        }

        .btn-primary:active {
            background: var(--nx-primary-active);
        }

        .btn-accent {
            background: var(--nx-accent);
            color: var(--nx-deep);
        }

        .btn-accent:hover {
            background: #D49A72;
        }

        .btn-outline {
            border-color: var(--nx-border-strong);
            color: var(--nx-text);
            background: transparent;
        }

        .btn-outline:hover {
            border-color: var(--nx-primary);
            background: var(--nx-primary);
            color: var(--nx-bg);
        }

        .btn-ghost-light {
            border-color: rgba(255, 255, 255, .45);
            color: #fff;
            background: rgba(21, 42, 35, .2);
        }

        .btn-ghost-light:hover {
            background: #fff;
            color: var(--nx-deep);
            border-color: #fff;
        }

        .btn[disabled] {
            opacity: .55;
            cursor: not-allowed;
        }

        .btn-text {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            padding: 0;
            background: none;
            border: 0;
            cursor: pointer;
            text-decoration: none;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 15px;
            color: var(--nx-primary);
            background-image: linear-gradient(currentColor, currentColor);
            background-size: 0 1px;
            background-repeat: no-repeat;
            background-position: 0 85%;
            transition: background-size .4s var(--ease);
        }

        .btn-text:hover {
            background-size: 100% 1px;
        }

        /* ============ Header ============ */
        .header {
            position: fixed;
            inset: 0 0 auto 0;
            z-index: 50;
            transition: background-color .4s var(--ease), box-shadow .4s var(--ease), backdrop-filter .4s;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: var(--header);
            gap: 24px;
            transition: height .4s var(--ease);
        }

        .header.is-solid {
            background: rgba(245, 247, 243, .92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 1px 0 var(--nx-border);
        }

        .header.is-compact .header-inner {
            height: 64px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #fff;
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 21px;
            letter-spacing: -.03em;
            transition: color .4s;
        }

        .logo-mark {
            width: 30px;
            height: 30px;
            flex: none;
        }

        .logo-mark rect {
            fill: none;
            stroke: currentColor;
            stroke-width: 1.6;
        }

        .logo-mark .door {
            fill: var(--nx-accent);
            stroke: none;
        }

        .header.is-solid .logo {
            color: var(--nx-text);
        }

        .nav-list {
            display: flex;
            gap: 32px;
        }

        .nav-link {
            position: relative;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, .82);
            padding: 8px 0;
            transition: color .3s;
        }

        .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 2px;
            height: 1px;
            background: var(--nx-accent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .35s var(--ease);
        }

        .nav-link:hover {
            color: #fff;
        }

        .nav-link:hover::after {
            transform: scaleX(1);
        }

        .header.is-solid .nav-link {
            color: var(--nx-text-secondary);
        }

        .header.is-solid .nav-link:hover {
            color: var(--nx-text);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .signin {
            background: none;
            border: 0;
            cursor: pointer;
            min-height: 44px;
            padding: 0 14px;
            font-weight: 600;
            font-size: 14px;
            color: #fff;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .header.is-solid .signin {
            color: var(--nx-text);
        }

        .header .btn-header {
            background: var(--nx-accent);
            color: var(--nx-deep);
        }

        .header.is-solid .btn-header {
            background: var(--nx-primary);
            color: var(--nx-bg);
        }

        .menu-toggle {
            display: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .35);
            background: transparent;
            color: #fff;
            cursor: pointer;
            place-items: center;
        }

        .header.is-solid .menu-toggle {
            color: var(--nx-text);
            border-color: var(--nx-border-strong);
        }

        .menu-toggle span {
            display: block;
            width: 16px;
            height: 1.5px;
            background: currentColor;
            transition: transform .3s var(--ease), opacity .3s;
        }

        .menu-toggle span+span {
            margin-top: 4px;
        }

        .menu-toggle[aria-expanded="true"] span:nth-child(1) {
            transform: translateY(5.5px) rotate(45deg);
        }

        .menu-toggle[aria-expanded="true"] span:nth-child(2) {
            opacity: 0;
        }

        .menu-toggle[aria-expanded="true"] span:nth-child(3) {
            transform: translateY(-5.5px) rotate(-45deg);
        }

        .mobile-nav {
            display: none;
            background: var(--nx-bg);
            border-top: 1px solid var(--nx-border);
            max-height: calc(100dvh - var(--header));
            overflow-y: auto;
        }

        .mobile-nav.is-open {
            display: block;
            animation: drop .35s var(--ease);
        }

        .mobile-nav a.m-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: 56px;
            border-bottom: 1px solid var(--nx-divider);
            text-decoration: none;
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 18px;
        }

        .mobile-nav a.m-link span {
            color: var(--nx-accent);
        }

        .mobile-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 0 28px;
        }

        .mobile-actions .btn {
            flex: 1 1 160px;
        }

        @keyframes drop {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        @media (max-width: 1023px) {
            .nav-primary {
                display: none;
            }

            .menu-toggle {
                display: grid;
            }
        }

        @media (max-width: 639px) {

            .header-actions .signin,
            .header-actions .btn-header {
                display: none;
            }
        }

        .no-js .menu-toggle {
            display: none;
        }

        /* ============ Hero ============ */
        .hero {
            position: relative;
            height: 100dvh;
            min-height: 640px;
            max-height: 1080px;
            background: var(--nx-bg);
        }

        .hero-frame {
            position: absolute;
            inset: 0;
            padding: calc(var(--hero-p, 0) * 1px);
        }

        .hero-media {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: var(--nx-sidebar);
            border-radius: calc(var(--hero-p, 0) * 1px);
        }

        .hero-media img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(var(--hero-s, 1.05));
            transform-origin: 50% 40%;
            will-change: transform;
        }

        .hero-media::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(to top, rgba(21, 42, 35, .94) 0%, rgba(32, 61, 52, .45) 45%, rgba(32, 61, 52, .35) 100%);
        }

        .hero-media::after {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(100deg, rgba(21, 42, 35, .7) 0%, transparent 60%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding-bottom: 96px;
            transform: translateY(calc(var(--hero-y, 0) * 1px));
            opacity: var(--hero-o, 1);
        }

        .hero-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 40px;
            align-items: end;
        }

        .hero-title-col {
            grid-column: span 8;
        }

        .hero-copy-col {
            grid-column: span 4;
            padding-bottom: 10px;
        }

        .hero h1 {
            color: #fff;
            font-size: clamp(44px, 7vw, 88px);
            line-height: 1;
            letter-spacing: -.04em;
            margin-top: 24px;
            max-width: 13ch;
            font-weight: 800;
        }

        .hero h1 em {
            font-style: normal;
            font-weight: 300;
            color: var(--nx-accent-soft);
            display: block;
        }

        .hero-copy {
            color: rgba(255, 255, 255, .82);
            font-size: 16px;
            max-width: 40ch;
        }

        .hero-ctas {
            margin-top: 28px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-meta {
            position: absolute;
            z-index: 2;
            inset: auto var(--pad) 28px auto;
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, .55);
            font-size: 12px;
        }

        .scroll-cue {
            position: absolute;
            z-index: 2;
            left: 50%;
            bottom: 24px;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, .7);
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .2em;
            text-transform: uppercase;
            min-height: 44px;
        }

        .scroll-cue:hover {
            color: #fff;
        }

        .scroll-cue .line {
            position: relative;
            width: 1px;
            height: 40px;
            background: rgba(255, 255, 255, .25);
            overflow: hidden;
        }

        .scroll-cue .line::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 50%;
            background: #fff;
            animation: cue 2.2s var(--ease) infinite;
        }

        @keyframes cue {
            0% {
                transform: translateY(-100%);
            }

            100% {
                transform: translateY(220%);
            }
        }

        .js .rise {
            opacity: 0;
            transform: translateY(24px);
            animation: rise 1.1s var(--ease) forwards;
        }

        @keyframes rise {
            to {
                opacity: 1;
                transform: none;
            }
        }

        @media (max-width: 1023px) {
            .hero-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .hero-title-col,
            .hero-copy-col {
                grid-column: auto;
            }

            .hero-meta {
                display: none;
            }
        }

        @media (max-width: 767px) {
            .hero {
                min-height: 600px;
            }

            .hero-content {
                padding-bottom: 48px;
            }

            .scroll-cue {
                display: none;
            }

            .hero-ctas .btn {
                flex: 1 1 100%;
            }
        }

        /* ============ Reveal ============ */
        .js .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .9s var(--ease), transform .9s var(--ease);
            transition-delay: var(--d, 0s);
        }

        .js .reveal.is-in {
            opacity: 1;
            transform: none;
        }

        .js .reveal-img {
            clip-path: inset(10% 6% 10% 6% round var(--r-xl));
            transition: clip-path 1.3s var(--ease);
            transition-delay: var(--d, 0s);
        }

        .js .reveal-img.is-in {
            clip-path: inset(0 0 0 0 round var(--r-xl));
        }

        .parallax {
            overflow: hidden;
            position: relative;
        }

        .parallax img {
            position: absolute;
            inset: -8% 0;
            width: 100%;
            height: 116%;
            object-fit: cover;
            transform: translateY(calc(var(--py, 0) * 1px));
            will-change: transform;
        }

        /* ============ Discover ============ */
        .section {
            padding: var(--section) 0;
            position: relative;
        }

        .discover {
            margin-top: -1px;
        }

        .discover-head {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 32px;
            align-items: end;
        }

        .discover-head .t {
            grid-column: span 7;
        }

        .discover-head .c {
            grid-column: 9 / span 4;
        }

        .discover-head .h2 span {
            color: var(--nx-secondary);
        }

        .discover-head h2 {
            margin-top: 24px;
        }

        .experiences {
            margin-top: 56px;
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: 300px 300px;
            gap: 24px;
        }

        .exp {
            position: relative;
            border-radius: var(--r-xl);
            overflow: hidden;
            color: #fff;
            background: var(--nx-sidebar);
        }

        .exp:nth-child(1) {
            grid-column: span 7;
            grid-row: span 2;
        }

        .exp:nth-child(2),
        .exp:nth-child(3) {
            grid-column: span 5;
        }

        .exp img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 1.4s var(--ease);
        }

        .exp:hover img {
            transform: scale(1.06);
        }

        .exp::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(21, 42, 35, .9) 0%, rgba(21, 42, 35, .15) 55%, transparent 100%);
        }

        .exp-body {
            position: absolute;
            z-index: 1;
            inset: auto 0 0 0;
            padding: 28px 32px;
        }

        .exp-num {
            font-family: var(--font-display);
            font-size: 12px;
            font-weight: 700;
            color: var(--nx-accent-soft);
            letter-spacing: .1em;
        }

        .exp h3 {
            font-size: clamp(24px, 2.4vw, 32px);
            margin-top: 6px;
        }

        .exp p {
            color: rgba(255, 255, 255, .82);
            font-size: 15px;
            max-width: 38ch;
            margin-top: 8px;
            transition: opacity .5s var(--ease), transform .5s var(--ease);
        }

        @media (hover: hover) and (min-width: 768px) {
            .exp p {
                opacity: 0;
                transform: translateY(10px);
            }

            .exp:hover p,
            .exp:focus-within p {
                opacity: 1;
                transform: none;
            }
        }

        @media (max-width: 1023px) {

            .discover-head .t,
            .discover-head .c {
                grid-column: span 12;
            }
        }

        @media (max-width: 767px) {
            .experiences {
                grid-template-columns: 1fr;
                grid-template-rows: none;
                gap: 16px;
            }

            .exp:nth-child(n) {
                grid-column: auto;
                grid-row: auto;
                aspect-ratio: 4 / 4.4;
            }

            .exp:nth-child(2),
            .exp:nth-child(3) {
                aspect-ratio: 16 / 12;
            }

            .exp-body {
                padding: 22px;
            }
        }

        /* ============ Inspiration (editorial) ============ */
        .inspire {
            background: var(--nx-sand);
        }

        .inspire-head {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 32px;
            flex-wrap: wrap;
        }

        .inspire-head h2 {
            margin-top: 24px;
            max-width: 16ch;
        }

        .inspire-disclaimer {
            max-width: 36ch;
            font-size: 14px;
            color: var(--nx-text-secondary);
            border-left: 2px solid var(--nx-accent);
            padding-left: 16px;
        }

        [dir="rtl"] .inspire-disclaimer {
            border-left: 0;
            border-right: 2px solid var(--nx-accent);
            padding-left: 0;
            padding-right: 16px;
        }

        .plates {
            margin-top: 56px;
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 40px 32px;
        }

        .plate figure {
            margin: 0;
        }

        .plate .ph {
            border-radius: var(--r-xl);
        }

        .plate-a {
            grid-column: 1 / span 7;
        }

        .plate-a .ph {
            aspect-ratio: 4 / 3;
        }

        .plate-b {
            grid-column: 9 / span 4;
            align-self: center;
        }

        .plate-b .ph {
            aspect-ratio: 3 / 4;
        }

        .plate-c {
            grid-column: 3 / span 8;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            padding-top: 40px;
            border-top: 1px solid #DCD1C4;
        }

        .plate-c h3 {
            font-size: clamp(24px, 2.6vw, 32px);
        }

        .plate figcaption {
            margin-top: 20px;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 6px 20px;
        }

        .plate .pl-no {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 13px;
            color: var(--nx-accent);
            grid-row: span 2;
            padding-top: 4px;
        }

        .plate .pl-t {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 20px;
            letter-spacing: -.02em;
        }

        .plate .pl-d {
            font-size: 15px;
            color: var(--nx-text-secondary);
            max-width: 46ch;
        }

        .principles li {
            display: flex;
            gap: 16px;
            padding: 14px 0;
            border-bottom: 1px solid #DCD1C4;
            font-size: 15px;
            color: var(--nx-text-secondary);
        }

        .principles li strong {
            font-family: var(--font-display);
            color: var(--nx-text);
            min-width: 88px;
        }

        @media (max-width: 1023px) {

            .plate-a,
            .plate-b,
            .plate-c {
                grid-column: span 12;
            }

            .plate-b {
                max-width: 420px;
                justify-self: end;
                width: 80%;
            }
        }

        @media (max-width: 767px) {
            .plate-b {
                width: 88%;
            }

            .plate-c {
                grid-template-columns: 1fr;
            }
        }

        /* ============ Story (sticky) ============ */
        .story {
            background: var(--nx-sidebar);
            color: #fff;
            position: relative;
        }

        .story-track {
            height: 300vh;
            position: relative;
        }

        .story-sticky {
            position: sticky;
            top: 0;
            height: 100dvh;
            overflow: hidden;
        }

        .story-stage {
            position: absolute;
            inset: 0;
            clip-path: inset(calc(var(--clip, 0) * 9%) calc(var(--clip, 0) * 8%) round calc(var(--clip, 0) * 24px));
        }

        .story-stage img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transform: scale(1.08);
            transition: opacity 1.1s var(--ease), transform 1.6s var(--ease);
        }

        .story-stage img.is-active {
            opacity: 1;
            transform: scale(1);
        }

        .story-stage::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(95deg, rgba(21, 42, 35, .92) 0%, rgba(21, 42, 35, .55) 50%, rgba(21, 42, 35, .1) 100%);
        }

        .story-ui {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .story-ui h2 {
            font-size: 14px;
            font-family: var(--font-body);
            font-weight: 600;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--nx-accent-soft);
        }

        .story-steps {
            position: relative;
            margin-top: 32px;
            min-height: 300px;
            max-width: 680px;
        }

        .story-step {
            position: absolute;
            inset: 0;
            opacity: 0;
            transform: translateY(30px);
            transition: opacity .7s var(--ease), transform .7s var(--ease);
        }

        .story-step.is-active {
            opacity: 1;
            transform: none;
        }

        .story-step .k {
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 14px;
            color: rgba(255, 255, 255, .6);
        }

        .story-step h3 {
            font-size: clamp(40px, 5.6vw, 76px);
            line-height: 1.02;
            letter-spacing: -.035em;
            margin-top: 14px;
            font-weight: 800;
        }

        .story-step p {
            margin-top: 20px;
            font-size: 18px;
            color: rgba(255, 255, 255, .78);
            max-width: 40ch;
        }

        .story-progress {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 48px;
        }

        .story-progress .wrap {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .story-progress span.lbl {
            font-family: var(--font-display);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .35);
            transition: color .4s;
        }

        .story-progress span.lbl.is-active {
            color: #fff;
        }

        .story-bar {
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, .2);
            position: relative;
        }

        .story-bar i {
            position: absolute;
            inset: 0;
            background: var(--nx-accent);
            transform: scaleX(var(--prog, 0));
            transform-origin: left;
        }

        [dir="rtl"] .story-bar i {
            transform-origin: right;
        }

        /* Stacked fallback: no-JS, mobile, reduced motion */
        .story-stack {
            display: none;
            padding: var(--section) 0;
        }

        .story-stack h2 {
            font-size: 13px;
            font-family: var(--font-body);
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--nx-accent-soft);
        }

        .story-stack article {
            margin-top: 48px;
        }

        .story-stack .ph {
            aspect-ratio: 4 / 4.6;
            border-radius: var(--r-xl);
        }

        .story-stack .k {
            margin-top: 24px;
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 13px;
            color: rgba(255, 255, 255, .6);
        }

        .story-stack h3 {
            font-size: 32px;
            margin-top: 8px;
        }

        .story-stack p {
            margin-top: 12px;
            color: rgba(255, 255, 255, .78);
        }

        .no-js .story-track,
        .story.is-stacked .story-track {
            display: none;
        }

        .no-js .story-stack,
        .story.is-stacked .story-stack {
            display: block;
        }

        @media (max-width: 767px) {
            .story-track {
                display: none;
            }

            .story-stack {
                display: block;
            }
        }

        @media (min-width: 768px) {
            .story-stack .grid3 {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 32px;
            }
        }

        /* ============ Managers ============ */
        .managers {
            background: var(--nx-bg);
            overflow: hidden;
        }

        .managers-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 40px 56px;
        }

        .managers-media {
            grid-column: 7 / span 6;
            grid-row: 1;
            position: relative;
        }

        .managers-media .ph {
            aspect-ratio: 5 / 6;
            border-radius: var(--r-xl);
        }

        .managers-copy {
            grid-column: 1 / span 6;
            grid-row: 1;
            padding-top: 16px;
        }

        .managers-copy h2 {
            margin-top: 24px;
        }

        .managers-copy h2 span {
            color: var(--nx-primary);
        }

        .managers-copy .lede {
            margin-top: 20px;
        }

        .anatomy {
            position: absolute;
            inset: auto auto -32px -40px;
            width: min(340px, 80%);
            background: var(--nx-sidebar);
            color: #fff;
            border-radius: var(--r-lg);
            padding: 24px;
            box-shadow: 0 30px 60px -30px rgba(21, 42, 35, .6);
        }

        [dir="rtl"] .anatomy {
            inset: auto -40px -32px auto;
        }

        .anatomy p {
            font-family: var(--font-display);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--nx-accent-soft);
        }

        .anatomy ol {
            margin-top: 16px;
            counter-reset: a;
        }

        .anatomy li {
            counter-increment: a;
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
            font-size: 14px;
            color: rgba(255, 255, 255, .86);
        }

        .anatomy li:last-child {
            border-bottom: 0;
        }

        .anatomy li::before {
            content: counter(a);
            display: grid;
            place-items: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .28);
            font-family: var(--font-display);
            font-size: 11px;
            font-weight: 700;
            flex: none;
        }

        .capabilities {
            margin-top: 40px;
        }

        .capabilities li {
            display: flex;
            gap: 20px;
            padding: 20px 0;
            border-top: 1px solid var(--nx-border);
        }

        .capabilities li:last-child {
            border-bottom: 1px solid var(--nx-border);
        }

        .cap-ico {
            flex: none;
            width: 44px;
            height: 44px;
            border-radius: var(--r-md);
            display: grid;
            place-items: center;
            background: var(--nx-sand);
            color: var(--nx-primary);
            transition: background-color .35s var(--ease), color .35s var(--ease);
        }

        .cap-ico svg {
            width: 20px;
            height: 20px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .capabilities li:hover .cap-ico {
            background: var(--nx-primary);
            color: #fff;
        }

        .capabilities h3 {
            font-size: 17px;
            letter-spacing: -.01em;
        }

        .capabilities p {
            font-size: 15px;
            color: var(--nx-text-secondary);
            margin-top: 2px;
        }

        .managers-cta {
            margin-top: 32px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }

        @media (max-width: 1023px) {

            .managers-media,
            .managers-copy {
                grid-column: span 12;
                grid-row: auto;
            }

            .managers-media {
                margin-bottom: 40px;
            }

            .managers-media .ph {
                aspect-ratio: 4 / 3;
            }

            .anatomy {
                left: auto;
                right: 20px;
                bottom: -40px;
            }
        }

        @media (max-width: 767px) {
            .managers-media .ph {
                aspect-ratio: 4 / 4.4;
            }

            .anatomy {
                position: relative;
                inset: auto;
                margin: -64px 16px 0 auto;
                width: 86%;
            }

            [dir="rtl"] .anatomy {
                inset: auto;
                margin: -64px auto 0 16px;
            }
        }

        /* ============ How it works ============ */
        .how {
            background: var(--nx-surface);
            border-top: 1px solid var(--nx-divider);
        }

        .how-head {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 24px;
            flex-wrap: wrap;
        }

        .how-head h2 {
            margin-top: 24px;
        }

        .steps {
            margin-top: 56px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 32px;
            position: relative;
            counter-reset: s;
        }

        .steps::before {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            top: 22px;
            height: 1px;
            background: var(--nx-border-strong);
        }

        .step {
            position: relative;
        }

        .step-dot {
            position: relative;
            z-index: 1;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            border: 1px solid var(--nx-border-strong);
            background: var(--nx-surface);
            color: var(--nx-primary);
            transition: all .45s var(--ease);
        }

        .step-dot svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.7;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .step:hover .step-dot {
            background: var(--nx-primary);
            border-color: var(--nx-primary);
            color: #fff;
        }

        .step .n {
            margin-top: 32px;
            font-family: var(--font-display);
            font-size: 13px;
            font-weight: 800;
            color: var(--nx-accent);
        }

        .step h3 {
            font-size: clamp(30px, 3.2vw, 40px);
            margin-top: 4px;
            letter-spacing: -.035em;
        }

        .step p {
            margin-top: 10px;
            color: var(--nx-text-secondary);
            max-width: 28ch;
        }

        @media (max-width: 767px) {
            .steps {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .steps::before {
                left: 22px;
                right: auto;
                top: 0;
                bottom: 0;
                width: 1px;
                height: auto;
            }

            [dir="rtl"] .steps::before {
                left: auto;
                right: 22px;
            }

            .step {
                display: flex;
                gap: 20px;
                padding-bottom: 36px;
            }

            .step .n {
                margin-top: 0;
            }

            .step:last-child {
                padding-bottom: 0;
            }
        }

        /* ============ Membership ============ */
        .membership {
            background: var(--nx-surface);
            padding-top: 0;
        }

        .member-card {
            display: grid;
            grid-template-columns: 5fr 7fr;
            border-radius: var(--r-xl);
            overflow: hidden;
            background: var(--nx-card);
            box-shadow: 0 1px 0 var(--nx-border), 0 40px 80px -50px rgba(49, 92, 77, .4);
        }

        .member-visual {
            position: relative;
            background: var(--nx-primary);
            color: var(--nx-bg);
            padding: 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 380px;
            overflow: hidden;
        }

        .member-visual .big {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(64px, 8vw, 112px);
            line-height: .9;
            letter-spacing: -.05em;
            color: rgba(245, 247, 243, .12);
        }

        .member-visual .plan {
            position: absolute;
            inset: 0;
            opacity: .22;
        }

        .member-visual .plan line,
        .member-visual .plan rect,
        .member-visual .plan path {
            stroke: var(--nx-bg);
            fill: none;
            stroke-width: 1;
        }

        .member-visual .tag {
            position: relative;
            font-size: 13px;
            max-width: 26ch;
            color: rgba(245, 247, 243, .8);
        }

        .member-body {
            padding: 56px;
        }

        .member-body h2 {
            margin-top: 24px;
            font-size: clamp(30px, 3.6vw, 44px);
        }

        .member-body .lede {
            margin-top: 20px;
        }

        .member-points {
            margin-top: 32px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .member-points li {
            border: 1px solid var(--nx-divider);
            border-radius: var(--r-md);
            padding: 18px;
        }

        .member-points strong {
            display: block;
            font-family: var(--font-display);
            font-size: 16px;
        }

        .member-points span {
            font-size: 14px;
            color: var(--nx-text-secondary);
        }

        .member-cta {
            margin-top: 32px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }

        @media (max-width: 1023px) {
            .member-card {
                grid-template-columns: 1fr;
            }

            .member-visual {
                min-height: 240px;
                padding: 32px;
            }

            .member-body {
                padding: 40px 32px;
            }
        }

        @media (max-width: 767px) {
            .member-body {
                padding: 32px 22px;
            }

            .member-points {
                grid-template-columns: 1fr;
            }
        }

        /* ============ Final CTA ============ */
        .final {
            background: var(--nx-deep);
            color: #fff;
            overflow: hidden;
            position: relative;
        }

        .final-grid {
            display: grid;
            grid-template-columns: 7fr 5fr;
            gap: 56px;
            align-items: center;
        }

        .final h2 {
            font-size: clamp(40px, 6vw, 76px);
            letter-spacing: -.04em;
            line-height: 1;
            font-weight: 800;
        }

        .final h2 span {
            color: var(--nx-accent);
        }

        .final p {
            margin-top: 24px;
            color: rgba(255, 255, 255, .72);
            max-width: 42ch;
            font-size: 17px;
        }

        .final-ctas {
            margin-top: 40px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .final-art {
            position: relative;
            aspect-ratio: 4 / 5;
            border-radius: var(--r-xl);
            border: 1px solid rgba(255, 255, 255, .12);
            background: linear-gradient(160deg, var(--nx-primary) 0%, var(--nx-sidebar) 100%);
            overflow: hidden;
        }

        .final-art svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        .final-art svg * {
            fill: none;
            stroke: rgba(245, 247, 243, .35);
            stroke-width: 1;
        }

        .final-art svg .warm {
            fill: var(--nx-accent);
            stroke: none;
            opacity: .9;
        }

        .final-art svg .door {
            transform-origin: 196px 300px;
            transform: scaleX(calc(1 - var(--door, 0) * .7));
            transition: transform .2s linear;
        }

        .final-art .cap {
            position: absolute;
            left: 24px;
            bottom: 20px;
            font-size: 12px;
            color: rgba(255, 255, 255, .55);
            letter-spacing: .08em;
        }

        @media (max-width: 1023px) {
            .final-grid {
                grid-template-columns: 1fr;
            }

            .final-art {
                aspect-ratio: 16 / 10;
            }
        }

        @media (max-width: 767px) {
            .final-ctas .btn {
                flex: 1 1 100%;
            }
        }

        /* ============ Footer ============ */
        .footer {
            background: #18302A;
            color: rgba(255, 255, 255, .7);
            padding-top: 64px;
        }

        .footer .logo {
            color: #fff;
        }

        .footer-top {
            display: grid;
            grid-template-columns: 4fr 8fr;
            gap: 48px;
            padding-bottom: 56px;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .footer-desc {
            margin-top: 20px;
            font-size: 14px;
            max-width: 34ch;
        }

        .footer-cols {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
        }

        .footer-cols h3 {
            font-family: var(--font-body);
            font-size: 11px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .45);
            font-weight: 600;
        }

        .footer-cols ul {
            margin-top: 16px;
        }

        .footer-cols li {
            margin-top: 2px;
        }

        .f-link {
            display: inline-flex;
            align-items: center;
            min-height: 40px;
            padding: 0;
            background: none;
            border: 0;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            color: rgba(255, 255, 255, .72);
            transition: color .25s;
            text-align: start;
        }

        .f-link:hover {
            color: #fff;
        }

        .f-link[disabled] {
            cursor: not-allowed;
            opacity: .6;
        }

        .f-pending {
            font-size: 10px;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--nx-accent-soft);
            margin-inline-start: 8px;
        }

        .footer-bottom {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 8px;
            padding: 28px 0;
            font-size: 12px;
            color: rgba(255, 255, 255, .45);
        }

        @media (max-width: 1023px) {
            .footer-top {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 639px) {
            .footer-cols {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* ============ Noscript notice ============ */
        .noscript-note {
            position: relative;
            z-index: 60;
            background: var(--nx-sand);
            color: var(--nx-text);
            font-size: 14px;
            padding: 12px var(--pad);
            text-align: center;
            border-bottom: 1px solid #DCD1C4;
        }

        /* ============ Dialog ============ */
        .nx-dialog {
            border: 0;
            padding: 0;
            border-radius: var(--r-lg);
            width: min(520px, calc(100vw - 32px));
            background: var(--nx-surface);
            color: var(--nx-text);
            box-shadow: 0 40px 100px -30px rgba(21, 42, 35, .55);
        }

        .nx-dialog::backdrop {
            background: rgba(21, 42, 35, .6);
            backdrop-filter: blur(4px);
        }

        .nx-dialog[open] {
            animation: dlg .4s var(--ease);
        }

        @keyframes dlg {
            from {
                opacity: 0;
                transform: translateY(16px) scale(.98);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .dlg-head {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 16px;
            padding: 28px 28px 0;
        }

        .dlg-head h2 {
            font-size: 24px;
        }

        .dlg-close {
            flex: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid var(--nx-border-strong);
            background: transparent;
            cursor: pointer;
            display: grid;
            place-items: center;
            transition: background-color .25s, color .25s;
        }

        .dlg-close:hover {
            background: var(--nx-primary);
            color: #fff;
            border-color: var(--nx-primary);
        }

        .dlg-close svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            stroke-width: 1.8;
        }

        .dlg-body {
            padding: 16px 28px 28px;
        }

        .dlg-body p {
            color: var(--nx-text-secondary);
            font-size: 15px;
        }

        .dlg-body p+p {
            margin-top: 12px;
        }

        .dlg-status {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 16px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--nx-accent);
        }

        .dlg-status::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            border: 1.5px solid currentColor;
        }

        .dlg-foot {
            padding: 0 28px 28px;
            display: flex;
            justify-content: flex-end;
        }

        /* ---------- Member Account Dropdown ---------- */
        .nx-account-menu {
            position: relative;
        }

        .nx-account-menu summary {
            list-style: none;
            cursor: pointer;
        }

        .nx-account-menu summary::-webkit-details-marker {
            display: none;
        }

        .nx-account-menu summary::after {
            content: "⌄";
            display: inline-block;
            margin-inline-start: 7px;
            transition: transform .25s ease;
        }

        .nx-account-menu details[open] summary::after {
            transform: rotate(180deg);
        }

        /* FIX: the summary also has .nav-link, whose ::after is the 1px underline.
           Neutralise that underline so only the chevron shows. */
        .nx-account-menu summary.nav-link {
            display: inline-flex;
            align-items: center;
        }

        .nx-account-menu summary.nav-link::after {
            position: static;
            height: auto;
            background: none;
            transform: none;
            transform-origin: unset;
            margin-inline-start: 7px;
        }

        .nx-account-menu details[open] summary.nav-link::after {
            transform: rotate(180deg);
        }

        .nx-account-dropdown {
            position: absolute;
            inset-block-start: calc(100% + 14px);
            inset-inline-end: 0;
            width: 220px;
            padding: 8px;
            background: var(--nx-card);
            border: 1px solid var(--nx-border);
            border-radius: var(--r-md);
            box-shadow: 0 16px 40px rgba(21, 42, 35, .16);
            z-index: 60;
        }

        .nx-account-dropdown a {
            display: block;
            padding: 10px 12px;
            border-radius: 7px;
            color: var(--nx-text-secondary);
            text-decoration: none;
            font-size: 13px;
            transition: background .2s ease, color .2s ease;
        }

        .nx-account-dropdown a:hover {
            background: var(--nx-bg);
            color: var(--nx-primary);
        }

        /* Log out button inside the dropdown */
        .nx-account-dropdown form {
            margin: 6px 0 0;
            padding-top: 6px;
            border-top: 1px solid var(--nx-divider);
        }

        .nx-account-dropdown button {
            width: 100%;
            text-align: start;
            background: none;
            border: 0;
            cursor: pointer;
            padding: 10px 12px;
            border-radius: 7px;
            font-size: 13px;
            color: #912018;
            transition: background .2s ease;
        }

        .nx-account-dropdown button:hover {
            background: #FDEDEC;
        }

        .nx-mobile-account {
            padding-top: 20px;
        }

        .nx-mobile-account>p {
            padding: 12px 0 6px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .14em;
            color: var(--nx-text-muted);
        }

        .nx-mobile-logout {
            margin: 0;
            padding: 20px 0 28px;
        }

        .nx-mobile-logout .btn {
            width: 100%;
        }

        /* Current page indicator in the header nav */
        .nav-link.is-active {
            color: #fff;
        }

        .header.is-solid .nav-link.is-active {
            color: var(--nx-text);
        }

        .nav-link.is-active::after {
            transform: scaleX(1);
        }

        /* Anchors must not hide under the fixed header */
        section[id] {
            scroll-margin-top: calc(var(--header) + 8px);
        }

        /* ============ Reduced motion ============ */
        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                animation-duration: .001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .001ms !important;
            }

            .js .rise,
            .js .reveal {
                opacity: 1;
                transform: none;
            }

            .js .reveal-img {
                clip-path: none;
            }

            .hero-media img {
                transform: none;
            }

            .parallax img {
                transform: none;
            }

            .story-track {
                display: none;
            }

            .story-stack {
                display: block;
            }
        }
    </style>
</head>

<body>
    <a class="skip" href="#main">Skip to content</a>

    <noscript>
        <div class="noscript-note">JavaScript is off. All page content and navigation links remain available.</div>
    </noscript>



    {{-- ================= HEADER ================= --}}
    <header class="header" id="siteHeader">
        <div class="wrap header-inner">
            <a href="#top" class="logo" aria-label="NexOra, back to top" data-testid="link-home">
                <svg class="logo-mark" viewBox="0 0 30 30" aria-hidden="true">
                    <rect x="1" y="1" width="28" height="28" rx="7" />
                    <rect class="door" x="11" y="11" width="8" height="18" rx="1" />
                </svg>
                NexOra
            </a>

            <nav class="nav-primary" aria-label="Primary">
                <ul class="nav-list">
                    @if ($nxIsMember)
                        {{-- Signed-in customer: their own links only --}}
                        <li><a class="nav-link {{ request()->routeIs('explore') ? 'is-active' : '' }}"
                                href="{{ route('explore') }}">Explore</a></li>

                        @foreach ($nxMemberMain as [$nxName, $nxLabel, $nxPatterns])
                            @if (Route::has($nxName))
                                <li><a class="nav-link {{ request()->routeIs(...$nxPatterns) ? 'is-active' : '' }}"
                                        href="{{ route($nxName) }}">{{ $nxLabel }}</a></li>
                            @endif
                        @endforeach

                        <li class="nx-account-menu">
                            <details>
                                <summary class="nav-link">{{ \Illuminate\Support\Str::limit($nxUser->name, 14) }}
                                </summary>

                                <div class="nx-account-dropdown">
                                    @foreach ($nxMemberMore as [$nxName, $nxLabel])
                                        @if (Route::has($nxName))
                                            <a href="{{ route($nxName) }}">{{ $nxLabel }}</a>
                                        @endif
                                    @endforeach

                                    @if (Route::has('logout'))
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit">Log out</button>
                                        </form>
                                    @endif
                                </div>
                            </details>
                        </li>
                    @else
                        {{-- Guests and admin / manager: marketing anchors --}}
                        <li><a class="nav-link" href="#discover">Discover</a></li>
                        <li><a class="nav-link" href="#inspiration">Inspiration</a></li>
                        <li><a class="nav-link" href="#managers">For managers</a></li>
                        <li><a class="nav-link" href="#how">How it works</a></li>
                        <li><a class="nav-link" href="#membership">Membership</a></li>
                    @endif
                </ul>
            </nav>

            <div class="header-actions">
                @guest
                    {!! $nxCta('login', 'Sign in', 'signin', 'button-header-login') !!}
                    <a class="btn btn-sm btn-header" href="#managers" data-testid="button-header-list">For workspace
                        managers <span class="arrow" aria-hidden="true">&rarr;</span></a>
                @endguest

                @if ($nxIsStaff)
                    <a class="btn btn-sm btn-header" href="{{ $nxStaffUrl }}"
                        data-testid="button-header-dashboard">Dashboard
                        <span class="arrow" aria-hidden="true">&rarr;</span></a>
                @endif

                <button type="button" class="menu-toggle" id="menuToggle" aria-expanded="false"
                    aria-controls="mobileNav" aria-label="Open menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
        <nav class="mobile-nav" id="mobileNav" aria-label="Mobile">
            <div class="wrap">
                @if ($nxIsMember)
                    <a class="m-link" href="{{ route('explore') }}">Explore <span
                            aria-hidden="true">&rarr;</span></a>

                    @foreach ($nxMemberMobile as [$nxName, $nxLabel])
                        @if (Route::has($nxName))
                            <a class="m-link" href="{{ route($nxName) }}">{{ $nxLabel }} <span
                                    aria-hidden="true">&rarr;</span></a>
                        @endif
                    @endforeach

                    @if (Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}" class="nx-mobile-logout">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline">Log out</button>
                        </form>
                    @endif
                @else
                    <a class="m-link" href="#discover">Discover <span aria-hidden="true">&rarr;</span></a>
                    <a class="m-link" href="#inspiration">Inspiration <span aria-hidden="true">&rarr;</span></a>
                    <a class="m-link" href="#managers">For managers <span aria-hidden="true">&rarr;</span></a>
                    <a class="m-link" href="#how">How it works <span aria-hidden="true">&rarr;</span></a>
                    <a class="m-link" href="#membership">Membership <span aria-hidden="true">&rarr;</span></a>

                    <div class="mobile-actions">
                        @if ($nxIsStaff)
                            <a class="btn btn-sm btn-primary" href="{{ $nxStaffUrl }}"
                                data-testid="button-mobile-dashboard">Dashboard</a>
                        @else
                            <a class="btn btn-sm btn-primary" href="#managers" data-testid="button-mobile-list">For
                                workspace managers</a>
                            {!! $nxCta('login', 'Sign in', 'btn btn-sm btn-outline', 'button-mobile-login') !!}
                        @endif
                    </div>
                @endif
            </div>
        </nav>
    </header>

    <main id="main">

        {{-- ================= 1. HERO ================= --}}
        <section class="hero" id="top" aria-labelledby="hero-title">
            <div class="hero-frame" id="heroFrame">
                <div class="hero-media">
                    <img src="https://images.pexels.com/photos/1170412/pexels-photo-1170412.jpeg?auto=compress&cs=tinysrgb&w=1920"
                        srcset="https://images.pexels.com/photos/1170412/pexels-photo-1170412.jpeg?auto=compress&cs=tinysrgb&w=960 960w,
                         https://images.pexels.com/photos/1170412/pexels-photo-1170412.jpeg?auto=compress&cs=tinysrgb&w=1600 1600w,
                         https://images.pexels.com/photos/1170412/pexels-photo-1170412.jpeg?auto=compress&cs=tinysrgb&w=2400 2400w"
                        sizes="100vw"
                        alt="Open-plan loft workspace with a dark timber ceiling, white desks and tall windows letting in daylight"
                        fetchpriority="high" width="1920" height="1280" id="heroImg">
                </div>
            </div>

            <div class="wrap hero-content" id="heroContent">
                <div class="hero-grid">
                    <div class="hero-title-col">
                        <p class="eyebrow rise" style="animation-delay:.1s; color: var(--nx-accent-soft)">NexOra
                            &middot; A workspace marketplace</p>
                        <h1 id="hero-title" class="rise" style="animation-delay:.25s">Find your space. <em>Make
                                your next move.</em></h1>
                    </div>
                    <div class="hero-copy-col rise" style="animation-delay:.45s">
                        <p class="hero-copy">Discover inspiring workspaces, book rooms, and find the flexibility to
                            work your way. Or bring your workspace to a community ready to use it.</p>
                        <div class="hero-ctas">
                            {!! $nxCta('explore', 'Explore Workspaces', 'btn btn-accent', 'button-hero-explore') !!}
                            <a class="btn btn-ghost-light" href="#managers" data-testid="button-hero-list">For
                                Workspace Managers <span class="arrow" aria-hidden="true">&rarr;</span></a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="#discover" class="scroll-cue" data-testid="link-scroll-cue">Scroll <span class="line"
                    aria-hidden="true"></span></a>
            <p class="hero-meta">Photography for illustration</p>
        </section>

        {{-- ================= 2. DISCOVER ================= --}}
        <section class="section discover" id="discover" aria-labelledby="discover-title">
            <div class="wrap">
                <div class="discover-head">
                    <div class="t reveal">
                        <p class="eyebrow"><span class="idx">01</span> For professionals</p>
                        <h2 id="discover-title" class="h2">Not every great idea starts at a desk. <span>But it
                                deserves the right space.</span></h2>
                    </div>
                    <div class="c reveal" style="--d:.12s">
                        <p class="lede">A quiet morning alone, an afternoon with a client, or a home base for the
                            year. Start with the kind of room your work needs.</p>
                        <div style="margin-top:24px">{!! $nxCta('explore', 'Explore Spaces', 'btn btn-sm btn-outline', 'button-discover-explore') !!}</div>
                    </div>
                </div>

                <div class="experiences">
                    <article class="exp reveal">
                        <img src="https://images.pexels.com/photos/1957477/pexels-photo-1957477.jpeg?auto=compress&cs=tinysrgb&w=1200"
                            alt="Private office corner with a timber desk, mesh chair and laptop beside softly lit vertical blinds"
                            loading="lazy" decoding="async" width="1200" height="1700">
                        <div class="exp-body">
                            <span class="exp-num">01</span>
                            <h3>Private Offices</h3>
                            <p>A door that closes and a desk that stays yours. Quiet rooms for focused people and small
                                teams.</p>
                        </div>
                    </article>
                    <article class="exp reveal" style="--d:.1s">
                        <img src="https://images.pexels.com/photos/260689/pexels-photo-260689.jpeg?auto=compress&cs=tinysrgb&w=1000"
                            alt="Bright meeting room with a long white table, black chairs and a frosted glass wall"
                            loading="lazy" decoding="async" width="1000" height="667">
                        <div class="exp-body">
                            <span class="exp-num">02</span>
                            <h3>Meeting Rooms</h3>
                            <p>For pitches, workshops and conversations that deserve a proper table.</p>
                        </div>
                    </article>
                    <article class="exp reveal" style="--d:.2s">
                        <img src="https://images.pexels.com/photos/380769/pexels-photo-380769.jpeg?auto=compress&cs=tinysrgb&w=1000"
                            alt="Sunlit shared workspace with long wooden tables, plants and a person working by the window"
                            loading="lazy" decoding="async" width="1000" height="667">
                        <div class="exp-body">
                            <span class="exp-num">03</span>
                            <h3>Shared Workspaces</h3>
                            <p>Open, sociable floors where you can settle in and work among other makers.</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        {{-- ================= 3. INSPIRATION (replaces featured listings: editorial, not businesses) ================= --}}
        <section class="section inspire" id="inspiration" aria-labelledby="inspire-title">
            <div class="wrap">
                <div class="inspire-head">
                    <div class="reveal">
                        <p class="eyebrow"><span class="idx">02</span> Spaces worth noticing</p>
                        <h2 id="inspire-title" class="h2">What makes a room good to work in.</h2>
                    </div>
                    <p class="inspire-disclaimer reveal" style="--d:.1s">An editorial study of light, material and
                        proportion. These photographs are architectural inspiration, not NexOra listings. Live spaces
                        are published by workspace managers.</p>
                </div>

                <div class="plates">
                    <figure class="plate plate-a reveal">
                        <div class="ph parallax reveal-img">
                            <img src="https://images.pexels.com/photos/245240/pexels-photo-245240.jpeg?auto=compress&cs=tinysrgb&w=1400"
                                alt="Calm workroom with a round table, mixed chairs, a tall dark shelving wall, a potted tree and oak floors"
                                loading="lazy" decoding="async" width="1400" height="933">
                        </div>
                        <figcaption>
                            <span class="pl-no">Plate I</span>
                            <span class="pl-t">Proportion</span>
                            <span class="pl-d">A round table invites conversation without hierarchy. A dark wall of
                                shelves holds the room together and lets the daylight do the rest.</span>
                        </figcaption>
                    </figure>

                    <figure class="plate plate-b reveal" style="--d:.15s">
                        <div class="ph parallax reveal-img">
                            <img src="https://images.pexels.com/photos/1743555/pexels-photo-1743555.jpeg?auto=compress&cs=tinysrgb&w=900"
                                alt="Long timber corridor with warm afternoon light falling across polished floors toward a garden"
                                loading="lazy" decoding="async" width="900" height="600">
                        </div>
                        <figcaption>
                            <span class="pl-no">Plate II</span>
                            <span class="pl-t">Threshold</span>
                            <span class="pl-d">The walk in matters. A slow approach tells you to change pace before
                                you sit down.</span>
                        </figcaption>
                    </figure>

                    <div class="plate plate-c reveal">
                        <h3>Three things we look for when we walk into a room.</h3>
                        <ul class="principles">
                            <li><strong>Light</strong> Daylight you can work by, without glare on the screen.</li>
                            <li><strong>Quiet</strong> Sound that settles, so focus is not a fight.</li>
                            <li><strong>Material</strong> Surfaces that age well and feel good under the hand.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= 4. STORY (sticky scrollytelling) ================= --}}
        <section class="story" id="story" aria-labelledby="story-title">
            <h2 id="story-title" class="sr-only">Work looks different for everyone</h2>
            <div class="story-track" id="storyTrack" aria-hidden="true">
                <div class="story-sticky">
                    <div class="story-stage" id="storyStage">
                        <img class="is-active"
                            src="https://images.pexels.com/photos/245240/pexels-photo-245240.jpeg?auto=compress&cs=tinysrgb&w=1920"
                            alt="" loading="lazy" decoding="async">
                        <img src="https://images.pexels.com/photos/1595385/pexels-photo-1595385.jpeg?auto=compress&cs=tinysrgb&w=1920"
                            alt="" loading="lazy" decoding="async">
                        <img src="https://images.pexels.com/photos/2041627/pexels-photo-2041627.jpeg?auto=compress&cs=tinysrgb&w=1920"
                            alt="" loading="lazy" decoding="async">
                    </div>
                    <div class="wrap story-ui">
                        <p class="eyebrow" style="color:var(--nx-accent-soft)">03 &middot; Work looks different for
                            everyone</p>
                        <div class="story-steps">
                            <div class="story-step is-active">
                                <p class="k">01 / 03 &middot; Focus</p>
                                <h3>Find a place that inspires you.</h3>
                                <p>Daylight, quiet and a chair that fits. Some days, the room does half the work.</p>
                            </div>
                            <div class="story-step">
                                <p class="k">02 / 03 &middot; Together</p>
                                <h3>Bring your team together.</h3>
                                <p>A table big enough for everyone, and a wall to think out loud on.</p>
                            </div>
                            <div class="story-step">
                                <p class="k">03 / 03 &middot; Ambition</p>
                                <h3>Make room for your next big idea.</h3>
                                <p>When the plan outgrows the kitchen table, there is space waiting for it.</p>
                            </div>
                        </div>
                    </div>
                    <div class="story-progress">
                        <div class="wrap">
                            <span class="lbl is-active">Focus</span><span class="lbl">Together</span><span
                                class="lbl">Ambition</span>
                            <span class="story-bar" id="storyBar"><i></i></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Accessible, stacked version (used for screen readers, mobile, reduced motion and no-JS) --}}
            <div class="story-stack" id="storyStack">
                <div class="wrap">
                    <p class="eyebrow" style="color:var(--nx-accent-soft)" aria-hidden="true">03 &middot; Work looks
                        different for everyone</p>
                    <div class="grid3">
                        <article class="reveal">
                            <div class="ph parallax"><img
                                    src="https://images.pexels.com/photos/245240/pexels-photo-245240.jpeg?auto=compress&cs=tinysrgb&w=900"
                                    alt="Quiet workroom with a round table, shelving wall and potted tree in daylight"
                                    loading="lazy" decoding="async"></div>
                            <p class="k">01 / 03 &middot; Focus</p>
                            <h3>Find a place that inspires you.</h3>
                            <p>Daylight, quiet and a chair that fits. Some days, the room does half the work.</p>
                        </article>
                        <article class="reveal" style="--d:.1s">
                            <div class="ph parallax"><img
                                    src="https://images.pexels.com/photos/1595385/pexels-photo-1595385.jpeg?auto=compress&cs=tinysrgb&w=900"
                                    alt="Small team gathered around laptops at a wooden table in a bright, white-beamed room"
                                    loading="lazy" decoding="async"></div>
                            <p class="k">02 / 03 &middot; Together</p>
                            <h3>Bring your team together.</h3>
                            <p>A table big enough for everyone, and a wall to think out loud on.</p>
                        </article>
                        <article class="reveal" style="--d:.2s">
                            <div class="ph parallax"><img
                                    src="https://images.pexels.com/photos/2041627/pexels-photo-2041627.jpeg?auto=compress&cs=tinysrgb&w=900"
                                    alt="Overhead view of people working on laptops along a long timber table with plants"
                                    loading="lazy" decoding="async"></div>
                            <p class="k">03 / 03 &middot; Ambition</p>
                            <h3>Make room for your next big idea.</h3>
                            <p>When the plan outgrows the kitchen table, there is space waiting for it.</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= 5. MANAGERS ================= --}}
        <section class="section managers" id="managers" aria-labelledby="managers-title">
            <div class="wrap managers-grid">
                <div class="managers-media reveal">
                    <div class="ph parallax reveal-img">
                        <img src="https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=1200"
                            alt="Two people shaking hands across a desk with a laptop and coffee cups in soft window light"
                            loading="lazy" decoding="async" width="1200" height="800">
                    </div>
                    <div class="anatomy" aria-label="Parts of a workspace listing">
                        <p>Anatomy of a listing</p>
                        <ol>
                            <li>Workspace profile</li>
                            <li>Rooms and facilities</li>
                            <li>Plans and pricing you set</li>
                            <li>Availability and bookings</li>
                        </ol>
                    </div>
                </div>

                <div class="managers-copy">
                    <div class="reveal">
                        <p class="eyebrow"><span class="idx">04</span> For workspace managers</p>
                        <h2 id="managers-title" class="h2">Your workspace has a story. <span>Let more people
                                discover it.</span></h2>
                        <p class="lede">NexOra gives managers their own tools to list and run their spaces. Listing
                            is part of being a partner, not a customer membership.</p>
                    </div>
                    <ul class="capabilities">
                        <li class="reveal"><span class="cap-ico" aria-hidden="true"><svg viewBox="0 0 24 24">
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <path d="M3 15l5-5 4 4 3-3 6 6" />
                                </svg></span>
                            <div>
                                <h3>Showcase your spaces</h3>
                                <p>Present your workspace with the photography and detail it deserves.</p>
                            </div>
                        </li>
                        <li class="reveal" style="--d:.05s"><span class="cap-ico" aria-hidden="true"><svg
                                    viewBox="0 0 24 24">
                                    <rect x="3" y="3" width="8" height="8" rx="1" />
                                    <rect x="13" y="3" width="8" height="8" rx="1" />
                                    <rect x="3" y="13" width="8" height="8" rx="1" />
                                    <rect x="13" y="13" width="8" height="8" rx="1" />
                                </svg></span>
                            <div>
                                <h3>Publish rooms and plans</h3>
                                <p>List individual rooms and create workspace plans in one place.</p>
                            </div>
                        </li>
                        <li class="reveal" style="--d:.1s"><span class="cap-ico" aria-hidden="true"><svg
                                    viewBox="0 0 24 24">
                                    <path d="M4 6h10M18 6h2M4 12h4M12 12h8M4 18h12M20 18h0" />
                                    <circle cx="16" cy="6" r="2" />
                                    <circle cx="10" cy="12" r="2" />
                                    <circle cx="18" cy="18" r="2" />
                                </svg></span>
                            <div>
                                <h3>Your prices, your availability</h3>
                                <p>Set rates, availability and facilities on your own terms.</p>
                            </div>
                        </li>
                        <li class="reveal" style="--d:.15s"><span class="cap-ico" aria-hidden="true"><svg
                                    viewBox="0 0 24 24">
                                    <rect x="4" y="5" width="16" height="16" rx="2" />
                                    <path d="M8 3v4M16 3v4M4 10h16M9 15l2 2 4-4" />
                                </svg></span>
                            <div>
                                <h3>Manage incoming bookings</h3>
                                <p>Receive bookings and keep every reservation organised.</p>
                            </div>
                        </li>
                        <li class="reveal" style="--d:.2s"><span class="cap-ico" aria-hidden="true"><svg
                                    viewBox="0 0 24 24">
                                    <path d="M3 20h18M6 16l4-5 3 3 5-7" />
                                    <path d="M15 7h3v3" />
                                </svg></span>
                            <div>
                                <h3>Grow your workspace business</h3>
                                <p>Be found by professionals who are looking for a place to work.</p>
                            </div>
                        </li>
                    </ul>
                    <div class="managers-cta reveal">
                        <a class="btn btn-primary" href="#begin" data-testid="button-managers-cta">Get in touch
                            about listing <span class="arrow" aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= 6. HOW IT WORKS ================= --}}
        <section class="section how" id="how" aria-labelledby="how-title">
            <div class="wrap">
                <div class="how-head reveal">
                    <div>
                        <p class="eyebrow"><span class="idx">05</span> How NexOra works</p>
                        <h2 id="how-title" class="h2">Three steps to the right room.</h2>
                    </div>
                    <p class="lede" style="max-width:32ch">For professionals and teams looking for a place to work.
                    </p>
                </div>
                <ol class="steps">
                    <li class="step reveal">
                        <span class="step-dot" aria-hidden="true"><svg viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="6" />
                                <path d="M20 20l-4.5-4.5" />
                            </svg></span>
                        <div>
                            <p class="n">01</p>
                            <h3>Discover</h3>
                            <p>Explore workspaces that match your needs.</p>
                        </div>
                    </li>
                    <li class="step reveal" style="--d:.12s">
                        <span class="step-dot" aria-hidden="true"><svg viewBox="0 0 24 24">
                                <rect x="3" y="4" width="7" height="16" rx="1" />
                                <rect x="14" y="4" width="7" height="16" rx="1" />
                            </svg></span>
                        <div>
                            <p class="n">02</p>
                            <h3>Choose</h3>
                            <p>Compare rooms, facilities, and available plans.</p>
                        </div>
                    </li>
                    <li class="step reveal" style="--d:.24s">
                        <span class="step-dot" aria-hidden="true"><svg viewBox="0 0 24 24">
                                <rect x="4" y="5" width="16" height="16" rx="2" />
                                <path d="M8 3v4M16 3v4M4 10h16M9 15l2 2 4-4" />
                            </svg></span>
                        <div>
                            <p class="n">03</p>
                            <h3>Book</h3>
                            <p>Reserve your space or choose a membership.</p>
                        </div>
                    </li>
                </ol>
            </div>
        </section>

        {{-- ================= 7. MEMBERSHIP ================= --}}
        <section class="section membership" id="membership" aria-labelledby="membership-title">
            <div class="wrap">
                <div class="member-card reveal">
                    <div class="member-visual" aria-hidden="true">
                        <svg class="plan" viewBox="0 0 400 400" preserveAspectRatio="xMidYMid slice">
                            <rect x="40" y="40" width="320" height="320" />
                            <line x1="40" y1="160" x2="220" y2="160" />
                            <line x1="220" y1="40" x2="220" y2="260" />
                            <line x1="220" y1="260" x2="360" y2="260" />
                            <path d="M160 160 A40 40 0 0 1 200 120" />
                            <path d="M220 300 A40 40 0 0 0 260 260" />
                            <rect x="80" y="220" width="90" height="50" />
                        </svg>
                        <span class="big">Your<br>way.</span>
                        <span class="tag">Membership is for customers. Workspace managers list their spaces as
                            partners.</span>
                    </div>
                    <div class="member-body">
                        <p class="eyebrow"><span class="idx">06</span> Membership</p>
                        <h2 id="membership-title">More flexibility. More ways to work.</h2>
                        <p class="lede">Explore the membership options available on NexOra and choose the one that
                            suits your working style, then manage it alongside your bookings.</p>
                        <ul class="member-points">
                            <li><strong>See what is offered</strong><span>Look through available options before you
                                    commit.</span></li>
                            <li><strong>Match your rhythm</strong><span>Choose what fits how often and how you
                                    work.</span></li>
                        </ul>
                        <div class="member-cta">
                            @if ($nxIsMember && Route::has('member.memberships.index'))
                                <a class="btn btn-primary" href="{{ route('member.memberships.index') }}"
                                    data-testid="button-membership-explore">My Memberships <span class="arrow"
                                        aria-hidden="true">&rarr;</span></a>
                            @elseif (!$nxUser)
                                {!! $nxCta('register', 'Create an Account', 'btn btn-primary', 'button-membership-explore') !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= 8. FINAL CTA ================= --}}
        <section class="section final" id="begin" aria-labelledby="final-title">
            <div class="wrap final-grid">
                <div class="reveal">
                    <p class="eyebrow" style="color:var(--nx-accent-soft)">Begin here</p>
                    <h2 id="final-title" style="margin-top:24px">Your next great idea needs <span>the right
                            place.</span></h2>
                    <p>Find a room to work in, or open your doors to the people looking for one.</p>
                    <div class="final-ctas">
                        {!! $nxCta('explore', 'Explore Workspaces', 'btn btn-accent', 'button-final-explore') !!}
                        <a class="btn btn-ghost-light" href="#managers" data-testid="button-final-partner">For
                            Workspace Managers <span class="arrow" aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
                <div class="final-art reveal" style="--d:.15s" aria-hidden="true" id="finalArt">
                    <svg viewBox="0 0 400 500" preserveAspectRatio="xMidYMid slice">
                        <line x1="0" y1="420" x2="400" y2="420" />
                        <path d="M60 420 V120 A140 140 0 0 1 340 120 V420" />
                        <path d="M90 420 V130 A110 110 0 0 1 310 130 V420" />
                        <line x1="200" y1="20" x2="200" y2="420" />
                        <line x1="90" y1="230" x2="310" y2="230" />
                        <rect class="warm door" x="160" y="300" width="72" height="120" />
                        <line x1="40" y1="460" x2="360" y2="460" />
                    </svg>
                    <span class="cap">An open door</span>
                </div>
            </div>
        </section>

    </main>

    {{-- ================= 9. FOOTER ================= --}}
    <footer class="footer" id="about" aria-labelledby="footer-title">
        <h2 id="footer-title" class="sr-only">About NexOra</h2>
        <div class="wrap">
            <div class="footer-top">
                <div>
                    <a href="#top" class="logo" aria-label="NexOra, back to top">
                        <svg class="logo-mark" viewBox="0 0 30 30" aria-hidden="true">
                            <rect x="1" y="1" width="28" height="28" rx="7" />
                            <rect class="door" x="11" y="11" width="8" height="18" rx="1" />
                        </svg>
                        NexOra
                    </a>
                    <p class="footer-desc">A coworking marketplace connecting professionals with workspaces, and the
                        managers who run them with the people looking for a place to work.</p>
                </div>
                <nav class="footer-cols" aria-label="Footer">
                    <div>
                        <h3>Professionals</h3>
                        <ul>
                            <li>{!! $nxCta('explore', 'Explore Workspaces', 'f-link', 'button-footer-explore') !!}</li>
                            <li><a class="f-link" href="#membership"
                                    data-testid="button-footer-memberships">Memberships</a></li>
                            <li><a class="f-link" href="#how">How it works</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3>Managers</h3>
                        <ul>
                            <li><a class="f-link" href="#managers" data-testid="button-footer-list">For workspace
                                    managers</a></li>
                            <li><a class="f-link" href="#managers">Why NexOra</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3>Company</h3>
                        <ul>
                            <li><a class="f-link" href="#about">About</a></li>
                            <li><a class="f-link" href="#begin" data-testid="button-footer-contact">Get in touch</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3>Account</h3>
                        <ul>
                            @if ($nxIsMember)
                                @if (Route::has('member.bookings.index'))
                                    <li><a class="f-link" href="{{ route('member.bookings.index') }}">My Bookings</a>
                                    </li>
                                @endif
                                @if (Route::has('member.profile.index'))
                                    <li><a class="f-link" href="{{ route('member.profile.index') }}">My Profile</a>
                                    </li>
                                @endif
                            @elseif ($nxIsStaff)
                                <li><a class="f-link" href="{{ $nxStaffUrl }}">Dashboard</a></li>
                            @else
                                <li>{!! $nxCta('login', 'Sign in', 'f-link', 'button-footer-login') !!}</li>
                                <li>{!! $nxCta('register', 'Create an account', 'f-link', 'button-footer-register') !!}</li>
                            @endif
                        </ul>
                    </div>
                </nav>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} NexOra. All rights reserved.</p>
                <p>Photography on this page is illustrative and does not depict NexOra listings.</p>
            </div>
        </div>
    </footer>

    <script>
        (function() {
            'use strict';
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)');
            var isSmall = window.matchMedia('(max-width: 767px)');

            /* ---------- Header + mobile menu ---------- */
            var header = document.getElementById('siteHeader');
            var toggle = document.getElementById('menuToggle');
            var mobileNav = document.getElementById('mobileNav');
            var menuOpen = false;

            function setMenu(open) {
                menuOpen = open;
                toggle.setAttribute('aria-expanded', String(open));
                toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
                mobileNav.classList.toggle('is-open', open);
                updateHeader();
            }

            function closeMenu() {
                if (menuOpen) setMenu(false);
            }
            toggle.addEventListener('click', function() {
                setMenu(!menuOpen);
            });
            mobileNav.querySelectorAll('a').forEach(function(a) {
                a.addEventListener('click', closeMenu);
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && menuOpen) {
                    setMenu(false);
                    toggle.focus();
                }
            });
            window.matchMedia('(min-width: 1024px)').addEventListener('change', function(m) {
                if (m.matches) closeMenu();
            });

            function updateHeader() {
                // On non-landing pages the header carries `is-forced` and stays solid.
                if (header.classList.contains('is-forced')) return;
                var y = window.scrollY;
                header.classList.toggle('is-solid', y > 40 || menuOpen);
                header.classList.toggle('is-compact', y > 120);
            }

            /* ---------- Account dropdown: close on outside click / Escape ---------- */
            document.addEventListener('click', function(e) {
                document.querySelectorAll('.nx-account-menu details[open]').forEach(function(d) {
                    if (!d.contains(e.target)) d.removeAttribute('open');
                });
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.nx-account-menu details[open]').forEach(function(d) {
                        d.removeAttribute('open');
                    });
                }
            });

            /* ---------- Reveal on scroll ---------- */
            var revealEls = document.querySelectorAll('.reveal, .reveal-img');
            if ('IntersectionObserver' in window && !reduce.matches) {
                var io = new IntersectionObserver(function(entries) {
                    entries.forEach(function(en) {
                        if (en.isIntersecting) {
                            en.target.classList.add('is-in');
                            io.unobserve(en.target);
                        }
                    });
                }, {
                    rootMargin: '0px 0px -10% 0px',
                    threshold: 0.08
                });
                revealEls.forEach(function(el) {
                    io.observe(el);
                });
            } else {
                revealEls.forEach(function(el) {
                    el.classList.add('is-in');
                });
            }

            /* ---------- Scroll-driven effects ---------- */
            var root = document.documentElement;
            var heroFrame = document.getElementById('heroFrame');
            var heroContent = document.getElementById('heroContent');
            var heroImg = document.getElementById('heroImg');
            var story = document.getElementById('story');
            var track = document.getElementById('storyTrack');
            var stage = document.getElementById('storyStage');
            var bar = document.getElementById('storyBar');
            var storyImgs = stage.querySelectorAll('img');
            var storySteps = track.querySelectorAll('.story-step');
            var storyLbls = track.querySelectorAll('.lbl');
            var finalArt = document.getElementById('finalArt');
            var parallaxImgs = document.querySelectorAll('.parallax img');
            var currentStep = 0;

            function clamp(v, a, b) {
                return Math.min(b, Math.max(a, v));
            }

            function useStacked() {
                return reduce.matches || isSmall.matches;
            }

            function applyMode() {
                story.classList.toggle('is-stacked', useStacked());
            }
            applyMode();
            reduce.addEventListener('change', applyMode);
            isSmall.addEventListener('change', applyMode);

            function frame() {
                ticking = false;
                updateHeader();
                if (reduce.matches) return;
                var vh = window.innerHeight;
                var y = window.scrollY;

                // Hero: image scales gently, frame insets, text drifts and fades.
                var hp = clamp(y / vh, 0, 1);
                heroImg.style.setProperty('--hero-s', (1.05 + hp * 0.1).toFixed(4));
                heroFrame.style.setProperty('--hero-p', isSmall.matches ? 0 : (hp * 20).toFixed(2));
                heroContent.style.setProperty('--hero-y', (hp * -80).toFixed(1));
                heroContent.style.setProperty('--hero-o', clamp(1 - hp * 1.4, 0, 1).toFixed(3));

                // Sticky story.
                if (!useStacked()) {
                    var r = track.getBoundingClientRect();
                    var total = r.height - vh;
                    var p = clamp(-r.top / total, 0, 1);
                    stage.style.setProperty('--clip', clamp(1 - p / 0.15, 0, 1).toFixed(3));
                    bar.style.setProperty('--prog', p.toFixed(4));
                    var s = Math.min(2, Math.floor(p * 3));
                    if (s !== currentStep) {
                        storyImgs[currentStep].classList.remove('is-active');
                        storySteps[currentStep].classList.remove('is-active');
                        storyLbls[currentStep].classList.remove('is-active');
                        storyImgs[s].classList.add('is-active');
                        storySteps[s].classList.add('is-active');
                        storyLbls[s].classList.add('is-active');
                        currentStep = s;
                    }
                }

                // Restrained parallax (in-view only).
                var strength = isSmall.matches ? 18 : 36;
                parallaxImgs.forEach(function(img) {
                    var b = img.parentElement.getBoundingClientRect();
                    if (b.bottom < 0 || b.top > vh) return;
                    var c = (b.top + b.height / 2 - vh / 2) / vh;
                    img.style.setProperty('--py', (c * -strength).toFixed(1));
                });

                // Final CTA: the door opens as it enters view.
                var fr = finalArt.getBoundingClientRect();
                finalArt.style.setProperty('--door', clamp(1 - (fr.top + fr.height / 2) / vh, 0, 1).toFixed(3));
            }

            var ticking = false;

            function onScroll() {
                if (!ticking) {
                    ticking = true;
                    window.requestAnimationFrame(frame);
                }
            }
            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            window.addEventListener('resize', onScroll, {
                passive: true
            });
            frame();
        })();
    </script>
</body>

</html>
