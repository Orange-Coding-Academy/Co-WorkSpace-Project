@extends('layouts.dashboard')

@section('dashboard-role', 'admin')

@php
$workspaceId = request()->query('workspace');
$selectedWorkspace = is_string($workspaceId)
? $workspaces->first(fn ($workspace) => (string) $workspace->getKey() === $workspaceId)
: null;
@endphp

@if ($selectedWorkspace)
@include('Admin.partials.workspace-details', ['workspace' => $selectedWorkspace])
@else
@section('title') Manage workspaces @endsection
@section('page-title') Manage workspaces @endsection

@push('styles')
    <style>
        /* This directory needs the complete dashboard canvas, not the default centred content width. */
        .nx-dashboard__main:has(> .nx-page-content){
            max-width: none;
            margin: 0;
            padding: clamp(12px, 1.25vw, 20px);
        }

        .nx-page-content{
            color-scheme: light;
            --ink: #1D2924;
            --muted: #53615A;
            --line: #E9EEE9;
            --purple: #315C4D;
            --amber: #C58A62;
            --grey: #7F8B84;
        }

        .nx-page-content *{
            box-sizing: border-box;
        }

        .nx-page-content{
            margin: 0;
            background: #F5F7F3;
            color: var(--ink);
            font: 1rem/1.5 system-ui, sans-serif;
        }

        .nx-page-content [data-nx-page-main]{
            max-width: 1800px;
            margin: auto;
            padding: 0;
        }

        .nx-page-content .summary,
        .nx-page-content .dashboard{
            display: grid;
            gap: 16px;
        }

        .nx-page-content .summary{
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 16px;
        }

        .nx-page-content .dashboard{
            grid-template-columns: minmax(0, 1fr);
            align-items: start;
        }

        .nx-page-content .card{
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: 0 8px 24px #211a3505;
        }

        .nx-page-content .summary .card{
            padding: 14px 16px;
        }

        .nx-page-content .metric-title,
        .nx-page-content .workspace{
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nx-page-content .icon,
        .nx-page-content .initial{
            display: inline-grid;
            flex: none;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #F5F7F3;
            color: #315C4D;
        }

        .nx-page-content .icon svg{
            width: 23px;
            height: 23px;
        }

        .nx-page-content .metric-number{
            display: block;
            margin-top: 2px;
            font-size: 1.9rem;
            line-height: 1.15;
        }

        .nx-page-content .muted{
            color: var(--muted);
        }

        .nx-page-content h2,
        .nx-page-content p{
            margin: 0;
        }

        .nx-page-content h2{
            font-size: 1.5rem;
            letter-spacing: -.025em;
        }

        .nx-page-content .directory-header,
        .nx-page-content .status-panel{
            padding: 28px;
        }

        /* Keep controls and results as two focused surfaces. */
        .nx-page-content .dashboard > .card{
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .nx-page-content .directory-header{
            position: relative;
            isolation: isolate;
            overflow: hidden;
            margin-bottom: 16px;
            border: 1px solid #315C4D;
            border-radius: 22px;
            background: linear-gradient(112deg, #203D34 0%, #315C4D 100%);
            color: #fff;
            box-shadow: 0 14px 32px rgba(40, 91, 75, .18);
            transition: transform .28s ease, box-shadow .28s ease;
        }

        .nx-page-content .directory-header::before,
        .nx-page-content .directory-header::after{
            position: absolute;
            z-index: -1;
            content: '';
            border-radius: 50%;
            pointer-events: none;
            transition: transform .42s ease;
        }

        .nx-page-content .directory-header::before{
            top: -155px;
            right: 8%;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, .11);
        }

        .nx-page-content .directory-header::after{
            right: 26%;
            bottom: -126px;
            width: 172px;
            height: 172px;
            background: rgba(197, 138, 98, .27);
        }

        .nx-page-content .directory-header:hover{
            transform: translateY(-2px);
            box-shadow: 0 20px 42px rgba(39, 75, 63, .24);
        }

        .nx-page-content .directory-header:hover::before{ transform: translate(-10px, 10px) scale(1.05); }
        .nx-page-content .directory-header:hover::after{ transform: translate(8px, -8px) scale(1.08); }

        .nx-page-content .directory-header > *{ position: relative; z-index: 1; }
        .nx-page-content .directory-header h2{ color: #fff; }

        .nx-page-content .directory-title-row{
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 18px;
        }

        .nx-page-content .directory-eyebrow{
            margin-bottom: 6px;
            color: #CFE1D8;
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .nx-page-content .directory-copy{
            margin-top: 7px;
            color: rgba(255, 255, 255, .78);
        }

        .nx-page-content .directory-count{
            flex: none;
            padding: 8px 12px;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 999px;
            background: rgba(255, 255, 255, .12);
            color: #fff;
            font-size: .88rem;
            font-weight: 750;
        }

        .nx-page-content .filters{
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(190px, 250px) auto;
            align-items: end;
            gap: 16px 20px;
            margin: 0 0 16px;
            padding: 20px;
            border: 1px solid #E7EEE7;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 5px 18px rgba(29, 41, 36, .035);
        }

        .nx-page-content .filters input,
        .nx-page-content .filters select{
            width: 100%;
            min-height: 50px;
            border: 1px solid #E7EEE7;
            border-radius: 12px;
            background: #fff;
            color: inherit;
            font: inherit;
            padding: 10px 12px;
        }

        .nx-page-content .filters input{
            min-width: 0;
        }

        .nx-page-content .filters select{
            cursor: pointer;
        }

        .nx-page-content .filter-field label{
            display: block;
            margin-bottom: 8px;
            color: var(--ink);
            font-size: .9rem;
            font-weight: 700;
        }

        .nx-page-content .filter-actions{
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .nx-page-content .filter-button{
            min-height: 46px;
            padding: 10px 18px;
            border: 1px solid #315C4D;
            border-radius: 999px;
            background: #315C4D;
            color: #fff;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        .nx-page-content .filter-button:hover{ background: #274B3F; }

        .nx-page-content .filter-button--reset{
            border-color: #C9D4CC;
            background: #fff;
            color: #315C4D;
        }

        .nx-page-content .filter-button--reset:hover{ background: #E9EEE9; }

        .nx-page-content .filters input:focus,
        .nx-page-content .filters select:focus{
            border-color: #315C4D;
            box-shadow: 0 0 0 3px rgba(49, 92, 77, .14);
            outline: 0;
        }

        .nx-page-content .table-scroll{
            overflow-x: auto;
            border: 1px solid var(--line);
            border-radius: 22px 22px 0 0;
            background: #fff;
        }

        .nx-page-content .directory-pagination{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px;
            border: 1px solid var(--line);
            border-top: 0;
            border-radius: 0 0 22px 22px;
            background: #fff;
        }

        .nx-page-content .directory-pagination p{ color: var(--muted); }

        .nx-page-content .pagination-controls{ display: inline-flex; gap: 6px; }

        .nx-page-content .page-button{
            display: inline-grid;
            place-items: center;
            min-width: 40px;
            min-height: 40px;
            padding: 6px 10px;
            border: 1px solid #DCE6DC;
            border-radius: 10px;
            background: #F5F7F3;
            color: #315C4D;
            font: inherit;
            cursor: pointer;
        }

        .nx-page-content .page-button:hover:not(:disabled){ border-color: #315C4D; }
        .nx-page-content .page-button.is-current{ border-color: #315C4D; background: #315C4D; color: #fff; }
        .nx-page-content .page-button:disabled{ opacity: .45; cursor: not-allowed; }

        .nx-page-content table{
            width: 100%;
            border-collapse: collapse;
        }

        @media (min-width: 861px) {
            .nx-page-content .table-scroll{ overflow-x: hidden; }

            .nx-page-content table{
                min-width: 0;
                table-layout: fixed;
            }

            .nx-page-content th:nth-child(1){ width: 22%; }
            .nx-page-content th:nth-child(2){ width: 19%; }
            .nx-page-content th:nth-child(3){ width: 9%; }
            .nx-page-content th:nth-child(4){ width: 13%; }
            .nx-page-content th:nth-child(5){ width: 15%; }
            .nx-page-content th:nth-child(6){ width: 22%; }
        }

        .nx-page-content th,
        .nx-page-content td{
            padding: 17px 16px;
            text-align: left;
            vertical-align: middle;
            border-top: 1px solid var(--line);
        }

        .nx-page-content th{
            background: #F5F7F3;
            color: #53615A;
            font-size: .9rem;
            font-weight: 800;
            letter-spacing: .015em;
            white-space: nowrap;
        }

        .nx-page-content th:first-child,
        .nx-page-content td:first-child{
            padding-left: 20px;
        }

        .nx-page-content td{
            color: var(--muted);
            overflow-wrap: anywhere;
        }

        .nx-page-content td:nth-child(2),
        .nx-page-content td:nth-child(4){ line-height: 1.45; }

        .nx-page-content tbody tr{
            transition: background-color .15s ease;
        }

        .nx-page-content tbody tr:nth-child(even){ background: #F8FAF7; }

        .nx-page-content .workspace{
            color: var(--ink);
            font-weight: 700;
        }

        .nx-page-content .initial{
            width: 36px;
            height: 36px;
        }

        .nx-page-content tr:nth-child(3n + 2) .initial{
            background: #e0f5ec;
            color: #16634d;
        }

        .nx-page-content tr:nth-child(3n) .initial{
            background: #EEE7DE;
            color: #C58A62;
        }

        .nx-page-content .badge{
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 3px 9px;
            font-size: .85rem;
            font-weight: 700;
        }

        .nx-page-content .active{
            background: #def5eb;
            color: #16634d;
        }

        .nx-page-content .pending{
            background: #fff0d5;
            color: #814c00;
        }

        .nx-page-content .inactive{
            background: #E9EEE9;
            color: #53615A;
        }

        .nx-page-content .badge::before,
        .nx-page-content .legend-label::before{
            content: "";
            width: 8px;
            height: 8px;
            flex: none;
            border-radius: 50%;
            background: currentColor;
        }

        .nx-page-content .status-panel>p{
            margin-top: 4px;
        }

        .nx-page-content .donut{
            position: relative;
            display: grid;
            place-items: center;
            width: min(100%, 190px);
            aspect-ratio: 1;
            margin: 26px auto;
            border-radius: 50%;
            background: conic-gradient(var(--purple) 0 var(--active-share),
                    var(--amber) var(--active-share) var(--review-share),
                    var(--grey) var(--review-share) 100%);
        }

        .nx-page-content .donut::before{
            content: "";
            position: absolute;
            inset: 9%;
            background: white;
            border-radius: 50%;
        }

        .nx-page-content .donut-label{
            position: relative;
            text-align: center;
        }

        .nx-page-content .donut-label strong{
            display: block;
            font-size: 2.3rem;
            line-height: 1.15;
        }

        .nx-page-content .legend{
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nx-page-content .legend li{
            display: flex;
            justify-content: space-between;
            border-top: 1px solid var(--line);
            padding: 10px 0;
        }

        .nx-page-content .legend-label{
            display: inline-flex;
            align-items: center;
            gap: 9px;
        }

        .nx-page-content .legend-active::before{
            background: var(--purple);
        }

        .nx-page-content .legend-pending::before{
            background: var(--amber);
        }

        .nx-page-content .legend-inactive::before{
            background: var(--grey);
        }

        .nx-page-content .empty{
            padding: 26px;
            text-align: center;
        }

        @media (max-width: 980px) {
            .nx-page-content .summary{
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .nx-page-content .dashboard{
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (max-width: 480px) {
            .nx-page-content .directory-header{ padding: 20px; }
            .nx-page-content .directory-title-row{ align-items: flex-start; flex-direction: column; }

            .nx-page-content .table-scroll{ overflow: visible; }
            .nx-page-content table{ min-width: 0; }
            .nx-page-content thead{ display: none; }
            .nx-page-content tbody, .nx-page-content tr, .nx-page-content td{ display: block; width: 100%; }
            .nx-page-content tbody tr[data-workspace-status]{ padding: 14px 18px; border-top: 1px solid var(--line); }
            .nx-page-content tbody tr[data-workspace-status] td{ display: flex; align-items: center; justify-content: space-between; gap: 14px; padding: 8px 0; border: 0; text-align: right; }
            .nx-page-content tbody tr[data-workspace-status] td::before{ content: attr(data-label); flex: none; color: var(--muted); font-size: .78rem; font-weight: 750; text-align: left; text-transform: uppercase; }
            .nx-page-content tbody tr[data-workspace-status] td:first-child{ display: block; padding-left: 0; text-align: left; }
            .nx-page-content tbody tr[data-workspace-status] td:first-child::before{ display: none; }
            .nx-page-content .workspace-actions-group{ width: 100%; justify-content: flex-end; }

            .nx-page-content .filters{
                grid-template-columns: 1fr;
                padding: 16px;
            }

            .nx-page-content .summary{
                gap: 12px;
            }

            .nx-page-content .summary .card,
            .nx-page-content .directory-header,
            .nx-page-content .status-panel{
                padding: 16px;
            }

            .nx-page-content .metric-title{
                gap: 8px;
            }

            .nx-page-content .icon{
                width: 32px;
                height: 32px;
            }
        }

        .nx-page-content .workspace-actions{
            text-align: right;
            white-space: nowrap;
        }

        .nx-page-content .workspace-actions-group{
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .nx-page-content .workspace-actions-group .workspace-action{
            min-width: 0;
            width: clamp(72px, 8vw, 92px);
        }

        .nx-page-content .workspace-actions-group form{ margin: 0; }

        .nx-page-content .badge{
            white-space: nowrap;
        }

        .nx-page-content .workspace-action{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 92px;
            padding: 6px 12px;
            border: 1px solid var(--action-border);
            border-radius: 9px;
            background: var(--action-background);
            color: var(--action-ink);
            font: inherit;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .nx-page-content .workspace-action--disable{
            --action-background: #F5F7F3;
            --action-border: #f3c6cd;
            --action-ink: #9f2336;
            --action-hover: #ffe1e6;
        }

        .nx-page-content .workspace-action--review{
            --action-background: #fff6df;
            --action-border: #efd49a;
            --action-ink: #805000;
            --action-hover: #ffebbd;
        }

        .nx-page-content .workspace-action--enable{
            --action-background: #e7f7ef;
            --action-border: #C9D4CC;
            --action-ink: #176248;
            --action-hover: #d2f0e2;
        }

        .nx-page-content .workspace-action--view{
            --action-background: #fff;
            --action-border: #C9D4CC;
            --action-ink: #315C4D;
            --action-hover: #E9EEE9;
        }

        .nx-page-content .workspace-action:hover:not(:disabled){
            border-color: var(--action-ink);
            background: var(--action-hover);
        }

        .nx-page-content .workspace-action:focus-visible{
            outline: 2px solid var(--purple);
            outline-offset: 2px;
        }

        .nx-page-content .workspace-action:disabled{
            border-color: var(--line);
            background: #F5F7F3;
            color: var(--muted);
            cursor: default;
        }

        .nx-page-content .metric-title>div{
            min-width: 0;
        }

        .nx-page-content .summary .card,
        .nx-page-content .workspace-action,
        .nx-page-content .filters input,
        .nx-page-content .filters select{
            transition: background-color .15s ease, border-color .15s ease, box-shadow .15s ease;
        }

        .nx-page-content th.rooms-column,
        .nx-page-content td.rooms-column{
            text-align: center;
        }

        .nx-page-content .rooms-column{
            width: 1%;
            font-variant-numeric: tabular-nums;
        }

        .nx-page-content table{
            min-width: 720px;
        }

        .nx-page-content .filters :focus-visible,
        .nx-page-content .table-scroll:focus-visible{
            outline: 2px solid var(--purple);
            outline-offset: 2px;
        }

        .nx-page-content tbody tr[data-workspace-status]:focus-within{
            background: #F5F7F3;
        }

        @media (hover: hover) {
            .nx-page-content .summary .card:hover{
                border-color: #E7EEE7;
                box-shadow: 0 6px 18px #44258212;
            }

            .nx-page-content tbody tr[data-workspace-status]:hover{
                background: #F5F7F3;
            }

            .nx-page-content .filters input:hover,
            .nx-page-content .filters select:hover{
                border-color: #C9D4CC;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .nx-page-content .summary .card,
            .nx-page-content .workspace-action,
            .nx-page-content .filters input,
            .nx-page-content .filters select{
                transition: none;
            }
        }

        .nx-page-content .workspace-details-link{
            border: 0;
            padding: 0;
            background: transparent;
            font: inherit;
            font-weight: 700;
            text-align: left;
            text-decoration: none;
        }

        .nx-page-content .workspace-details-label{
            display: block;
            margin-top: 2px;
            color: var(--purple);
            font-size: .75rem;
            font-weight: 600;
        }

        .nx-page-content .workspace-details-link:hover .workspace-details-label,
        .nx-page-content .workspace-details-link:focus-visible .workspace-details-label{
            text-decoration: underline;
            text-underline-offset: 3px;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* ===== NexOra visual skin (tokens mirror welcome.blade.php) ===== */
        .nx-page-content{
            --nx-primary: #315C4D; --nx-primary-hover: #274B3F; --nx-primary-active: #203D34;
            --nx-secondary: #6F806E; --nx-accent: #C58A62; --nx-accent-soft: #E6C9B3; --nx-deep: #152A23; --nx-sand: #EEE7DE;
            --nx-bg: #F5F7F3; --nx-surface: #F8FAF7; --nx-card: #FFFFFF; --nx-sidebar: #203D34;
            --nx-text: #1D2924; --nx-text-secondary: #53615A; --nx-text-muted: #7F8B84;
            --nx-border: #E2E8E2; --nx-divider: #E9EEE9; --nx-border-strong: #C9D4CC;
            --nx-font-heading: 'Manrope', 'Noto Sans Arabic', system-ui, sans-serif;
            --nx-font-body: 'Inter', 'Noto Sans Arabic', system-ui, sans-serif;
            --nx-ease: cubic-bezier(.2, .7, .2, 1);
            --bs-body-font-family: var(--nx-font-body);
            --bs-body-color: #1D2924; --bs-body-color-rgb: 29, 41, 36; --bs-body-bg: #F5F7F3; --bs-body-bg-rgb: 245, 247, 243;
            --bs-primary: #315C4D; --bs-primary-rgb: 49, 92, 77; --bs-secondary: #6F806E; --bs-secondary-rgb: 111, 128, 110;
            --bs-info: #315C4D; --bs-info-rgb: 49, 92, 77; --bs-light: #F8FAF7; --bs-light-rgb: 248, 250, 247; --bs-dark: #1D2924; --bs-dark-rgb: 29, 41, 36;
            --bs-secondary-color: #53615A; --bs-secondary-bg: #EEF2ED; --bs-tertiary-bg: #F8FAF7;
            --bs-link-color: #315C4D; --bs-link-color-rgb: 49, 92, 77; --bs-link-hover-color: #203D34; --bs-link-hover-color-rgb: 32, 61, 52;
            --bs-border-color: #E2E8E2; --bs-border-radius: 12px; --bs-border-radius-sm: 8px; --bs-border-radius-lg: 16px; --bs-border-radius-xl: 24px;
            --bs-focus-ring-color: rgba(49, 92, 77, .22);
        }
        :where(.nx-page-content){ background-color: var(--nx-bg); color: var(--nx-text); }
        .nx-page-content{ font-family: var(--nx-font-body); -webkit-font-smoothing: antialiased; overflow-wrap: break-word; }
        .nx-page-content :where(h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .navbar-brand, .card-title, .modal-title){ font-family: var(--nx-font-heading); letter-spacing: -0.02em; color: inherit; }
        .nx-page-content :where(a){ color: var(--nx-primary); }
        .nx-page-content :where(a:hover){ color: var(--nx-primary-active); }
        .nx-page-content :focus-visible{ outline: 2px solid var(--nx-accent); outline-offset: 2px; }
        .nx-page-content .text-muted, .nx-page-content .text-secondary{ color: var(--nx-text-secondary) !important; }
        .nx-page-content .bg-light{ background-color: var(--nx-surface) !important; }
        .nx-page-content .bg-white{ background-color: var(--nx-card) !important; }
        .nx-page-content .bg-dark{ background-color: var(--nx-sidebar) !important; }
        .nx-page-content .shadow-sm{ box-shadow: 0 1px 2px rgba(29, 41, 36, .05) !important; }
        .nx-page-content .shadow{ box-shadow: 0 4px 12px rgba(29, 41, 36, .07) !important; }
        .nx-page-content .shadow-lg{ box-shadow: 0 12px 32px rgba(29, 41, 36, .12) !important; }

        /* Navbar */
        .nx-page-content .navbar{ min-height: 72px; border-bottom: 1px solid var(--nx-border); }
        .nx-page-content .navbar .navbar-brand{ font-weight: 800; font-size: 21px; letter-spacing: -.03em; color: var(--nx-text); }
        .nx-page-content .navbar .nav-link{ font-size: 14px; font-weight: 500; color: var(--nx-text-secondary); min-height: 44px; display: flex; align-items: center; }
        .nx-page-content .navbar .nav-link:hover, .nx-page-content .navbar .nav-link.active{ color: var(--nx-text); }
        .nx-page-content .navbar-toggler{ border-color: var(--nx-border-strong); border-radius: 999px; min-height: 44px; }
        .nx-page-content .navbar-toggler:focus{ box-shadow: 0 0 0 3px rgba(49, 92, 77, .22); }
        .nx-page-content .dropdown-menu{ border: 1px solid var(--nx-border); border-radius: 12px; padding: 6px; box-shadow: 0 4px 12px rgba(29, 41, 36, .07); font-size: 14px; }
        .nx-page-content .dropdown-item{ border-radius: 8px; min-height: 40px; display: flex; align-items: center; color: var(--nx-text); }
        .nx-page-content .dropdown-item:hover, .nx-page-content .dropdown-item:focus{ background: var(--nx-surface); color: var(--nx-text); }
        .nx-page-content .dropdown-item.active, .nx-page-content .dropdown-item:active{ background: var(--nx-primary); color: #fff; }

        /* Buttons — landing pill language, 44px professional controls */
        .nx-page-content .btn{
            --bs-btn-padding-x: 20px; --bs-btn-padding-y: 9px; --bs-btn-font-size: 14px; --bs-btn-font-weight: 700;
            --bs-btn-border-radius: 999px; --bs-btn-focus-box-shadow: 0 0 0 3px rgba(49, 92, 77, .22);
            min-height: 44px; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            font-family: var(--nx-font-heading); letter-spacing: -.005em;
            transition: background-color .3s var(--nx-ease), color .3s var(--nx-ease), border-color .3s var(--nx-ease), transform .3s var(--nx-ease);
        }
        .nx-page-content .btn:active{ transform: scale(.98); }
        .nx-page-content .btn-sm{ --bs-btn-padding-x: 14px; --bs-btn-padding-y: 6px; --bs-btn-font-size: 13px; min-height: 36px; }
        .nx-page-content .btn-lg{ --bs-btn-padding-x: 24px; --bs-btn-font-size: 15px; min-height: 52px; }
        .nx-page-content .btn-close{ min-height: 0; border-radius: 8px; }
        .nx-page-content .btn-link{ min-height: auto; }
        .nx-page-content .btn-primary, .nx-page-content .btn-info, .nx-page-content .btn-dark{ --bs-btn-color: #F5F7F3; --bs-btn-bg: #315C4D; --bs-btn-border-color: #315C4D; --bs-btn-hover-color: #F5F7F3; --bs-btn-hover-bg: #274B3F; --bs-btn-hover-border-color: #274B3F; --bs-btn-active-color: #F5F7F3; --bs-btn-active-bg: #203D34; --bs-btn-active-border-color: #203D34; --bs-btn-disabled-bg: #315C4D; --bs-btn-disabled-border-color: #315C4D; --bs-btn-disabled-color: #F5F7F3; }
        .nx-page-content .btn-secondary, .nx-page-content .btn-light{ --bs-btn-color: #203D34; --bs-btn-bg: #EDF2EC; --bs-btn-border-color: #DCE6DC; --bs-btn-hover-color: #203D34; --bs-btn-hover-bg: #E3EBE2; --bs-btn-hover-border-color: #C9D4CC; --bs-btn-active-color: #203D34; --bs-btn-active-bg: #DCE6DC; --bs-btn-active-border-color: #C9D4CC; }
        .nx-page-content .btn-warning{ --bs-btn-color: #152A23; --bs-btn-bg: #C58A62; --bs-btn-border-color: #C58A62; --bs-btn-hover-color: #152A23; --bs-btn-hover-bg: #D49A72; --bs-btn-hover-border-color: #D49A72; --bs-btn-active-bg: #B97C55; --bs-btn-active-border-color: #B97C55; }
        .nx-page-content .btn-success{ --bs-btn-bg: #16794C; --bs-btn-border-color: #16794C; --bs-btn-hover-bg: #145C3A; --bs-btn-hover-border-color: #145C3A; --bs-btn-active-bg: #145C3A; }
        .nx-page-content .btn-danger{ --bs-btn-bg: #B42318; --bs-btn-border-color: #B42318; --bs-btn-hover-bg: #912018; --bs-btn-hover-border-color: #912018; --bs-btn-active-bg: #912018; }
        .nx-page-content .btn-outline-primary, .nx-page-content .btn-outline-secondary, .nx-page-content .btn-outline-dark, .nx-page-content .btn-outline-info, .nx-page-content .btn-outline-light{ --bs-btn-color: #1D2924; --bs-btn-bg: transparent; --bs-btn-border-color: #C9D4CC; --bs-btn-hover-color: #F5F7F3; --bs-btn-hover-bg: #315C4D; --bs-btn-hover-border-color: #315C4D; --bs-btn-active-color: #F5F7F3; --bs-btn-active-bg: #203D34; --bs-btn-active-border-color: #203D34; }
        .nx-page-content .btn-outline-warning{ --bs-btn-color: #754C05; --bs-btn-border-color: #F0D79E; --bs-btn-hover-color: #152A23; --bs-btn-hover-bg: #C58A62; --bs-btn-hover-border-color: #C58A62; }
        .nx-page-content .btn-outline-success{ --bs-btn-color: #145C3A; --bs-btn-border-color: #BCE2C9; --bs-btn-hover-bg: #16794C; --bs-btn-hover-border-color: #16794C; }
        .nx-page-content .btn-outline-danger{ --bs-btn-color: #912018; --bs-btn-border-color: #F5C2BE; --bs-btn-hover-bg: #B42318; --bs-btn-hover-border-color: #B42318; }

        /* Forms */
        .nx-page-content .form-control, .nx-page-content .form-select{ min-height: 44px; border: 1px solid var(--nx-border-strong); border-radius: 12px; background-color: var(--nx-card); color: var(--nx-text); font-size: 14px; transition: border-color .2s var(--nx-ease), box-shadow .2s var(--nx-ease); }
        .nx-page-content .form-control-sm, .nx-page-content .form-select-sm{ min-height: 36px; border-radius: 8px; }
        .nx-page-content textarea.form-control{ min-height: 104px; }
        .nx-page-content .form-control::placeholder{ color: var(--nx-text-muted); }
        .nx-page-content .form-control:hover:not(:disabled):not(.is-invalid), .nx-page-content .form-select:hover:not(:disabled):not(.is-invalid){ border-color: var(--nx-secondary); }
        .nx-page-content .form-control:focus, .nx-page-content .form-select:focus, .nx-page-content .form-check-input:focus{ border-color: var(--nx-primary); box-shadow: 0 0 0 3px rgba(49, 92, 77, .22); }
        .nx-page-content .form-control:disabled, .nx-page-content .form-select:disabled, .nx-page-content .form-control[readonly]{ background-color: #F0F2EF; color: #657169; }
        .nx-page-content .form-control.is-invalid, .nx-page-content .form-select.is-invalid{ border-color: #B42318; }
        .nx-page-content .invalid-feedback, .nx-page-content .text-danger{ color: #912018 !important; }
        .nx-page-content .form-label, .nx-page-content .col-form-label{ font-size: 13px; font-weight: 600; color: var(--nx-text); }
        .nx-page-content .form-text{ color: var(--nx-text-muted); font-size: 12px; }
        .nx-page-content .form-check-input:checked{ background-color: var(--nx-primary); border-color: var(--nx-primary); }
        .nx-page-content .input-group-text{ border-color: var(--nx-border-strong); background: var(--nx-surface); color: var(--nx-text-secondary); border-radius: 12px; }

        /* Cards */
        .nx-page-content .card{ --bs-card-border-color: var(--nx-border); --bs-card-border-radius: 16px; --bs-card-inner-border-radius: 15px; --bs-card-cap-bg: var(--nx-surface); background-color: var(--nx-card); box-shadow: 0 1px 2px rgba(29, 41, 36, .05); color: var(--nx-text); }
        .nx-page-content .card-header{ font-family: var(--nx-font-heading); font-weight: 700; border-bottom-color: var(--nx-divider); padding: 16px 24px; }
        .nx-page-content .card-body{ padding: 24px; }
        .nx-page-content .card-footer{ border-top-color: var(--nx-divider); padding: 16px 24px; }
        .nx-page-content .list-group{ --bs-list-group-border-color: var(--nx-border); --bs-list-group-border-radius: 12px; --bs-list-group-active-bg: var(--nx-primary); --bs-list-group-active-border-color: var(--nx-primary); }

        /* Tables — safe horizontal containment */
        .nx-page-content .table-responsive{ border-radius: 16px; -webkit-overflow-scrolling: touch; }
        .nx-page-content .table{ --bs-table-color: var(--nx-text); --bs-table-bg: transparent; --bs-table-border-color: var(--nx-divider); --bs-table-striped-bg: rgba(49, 92, 77, .03); --bs-table-hover-bg: var(--nx-surface); font-size: 14px; vertical-align: middle; }
        .nx-page-content .table > :not(caption) > * > *{ padding: 14px 16px; }
        .nx-page-content .table thead th, .nx-page-content .table > thead > tr > th{ background: var(--nx-surface); color: var(--nx-text-secondary); font-size: 12px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; border-bottom-color: var(--nx-border); }
        .nx-page-content .table-light{ --bs-table-bg: var(--nx-surface); --bs-table-color: var(--nx-text-secondary); --bs-table-border-color: var(--nx-border); }
        .nx-page-content .table-dark{ --bs-table-bg: var(--nx-sidebar); --bs-table-color: #F5F7F3; --bs-table-border-color: rgba(255, 255, 255, .12); }
        .nx-page-content .table-dark th, .nx-page-content .table thead.table-dark th{ background: var(--nx-sidebar); color: #F5F7F3; }

        /* Badges & alerts — semantic distinctions preserved */
        .nx-page-content .badge{ border-radius: 999px; padding: 5px 10px; font-size: 12px; font-weight: 600; letter-spacing: 0; font-family: var(--nx-font-body); }
        .nx-page-content .badge.bg-success{ background-color: #EAF6EF !important; color: #145C3A !important; box-shadow: inset 0 0 0 1px #BCE2C9; }
        .nx-page-content .badge.bg-warning{ background-color: #FFF6E5 !important; color: #754C05 !important; box-shadow: inset 0 0 0 1px #F0D79E; }
        .nx-page-content .badge.bg-danger{ background-color: #FDEDEC !important; color: #912018 !important; box-shadow: inset 0 0 0 1px #F5C2BE; }
        .nx-page-content .badge.bg-info, .nx-page-content .badge.bg-primary{ background-color: #E7EEE7 !important; color: #203D34 !important; box-shadow: inset 0 0 0 1px #C9D4CC; }
        .nx-page-content .badge.bg-secondary, .nx-page-content .badge.bg-light, .nx-page-content .badge.bg-dark{ background-color: #F0F2EF !important; color: #53615A !important; box-shadow: inset 0 0 0 1px #D9DEDA; }
        .nx-page-content .alert{ border-radius: 12px; font-size: 14px; padding: 14px 16px; }
        .nx-page-content .alert-success{ --bs-alert-color: #145C3A; --bs-alert-bg: #EAF6EF; --bs-alert-border-color: #BCE2C9; }
        .nx-page-content .alert-warning{ --bs-alert-color: #754C05; --bs-alert-bg: #FFF6E5; --bs-alert-border-color: #F0D79E; }
        .nx-page-content .alert-danger{ --bs-alert-color: #912018; --bs-alert-bg: #FDEDEC; --bs-alert-border-color: #F5C2BE; }
        .nx-page-content .alert-info, .nx-page-content .alert-primary{ --bs-alert-color: #203D34; --bs-alert-bg: #EDF2EC; --bs-alert-border-color: #C9D4CC; }

        /* Modals, pagination, nav tabs */
        .nx-page-content .modal-content{ border: 1px solid var(--nx-border); border-radius: 16px; box-shadow: 0 12px 32px rgba(29, 41, 36, .12); }
        .nx-page-content .modal-header{ border-bottom-color: var(--nx-divider); padding: 20px 24px; }
        .nx-page-content .modal-footer{ border-top-color: var(--nx-divider); padding: 16px 24px; }
        .modal-backdrop { --bs-backdrop-bg: #101B16; }
        .nx-page-content .pagination{ --bs-pagination-color: var(--nx-text-secondary); --bs-pagination-border-color: var(--nx-border); --bs-pagination-hover-color: var(--nx-text); --bs-pagination-hover-bg: var(--nx-surface); --bs-pagination-active-bg: var(--nx-primary); --bs-pagination-active-border-color: var(--nx-primary); --bs-pagination-focus-box-shadow: 0 0 0 3px rgba(49, 92, 77, .22); flex-wrap: wrap; gap: 6px; }
        .nx-page-content .pagination .page-link{ border-radius: 8px !important; min-width: 36px; text-align: center; }
        .nx-page-content .nav-tabs{ --bs-nav-tabs-border-color: var(--nx-border); --bs-nav-tabs-link-active-color: var(--nx-primary); }
        .nx-page-content .nav-pills{ --bs-nav-pills-border-radius: 999px; --bs-nav-pills-link-active-bg: var(--nx-primary); }
        .nx-page-content .nav-link{ color: var(--nx-text-secondary); }
        .nx-page-content .nav-link:hover{ color: var(--nx-text); }
        .nx-page-content .progress{ background-color: #E8EDE8; border-radius: 999px; }
        .nx-page-content .progress-bar{ background-color: var(--nx-primary); }

        /* Responsive containment */
        .nx-page-content img, .nx-page-content video{ max-width: 100%; }
        @media (max-width: 767px) {
            .nx-page-content .card-body{ padding: 18px; }
            .nx-page-content .table-responsive > .table, .nx-page-content .table-responsive table{ min-width: 640px; }
            .nx-page-content .container, .nx-page-content .container-fluid{ padding-inline: 16px; }
            .nx-page-content .d-flex.justify-content-between{ flex-wrap: wrap; gap: 12px; }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/nexora/workspace-pages.css') }}">
@endpush

@section('content')
<div class="nx-page-content nx-workspace-ui nx-workspace-directory">
    <div data-nx-page-main aria-label="Manage workspaces">


        <section class="summary" aria-label="Workspace summary">
            @foreach ($statistics as $key => $count)
            <article class="card metric-title">
                <span class="icon {{ $key }}" aria-hidden="true">

                    <span class="icon {{ $key }}" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75" stroke-linecap="round"
                            stroke-linejoin="round" focusable="false">

                            @if ($key === 'active')
                            {{-- علامة صح --}}
                            <circle cx="12" cy="12" r="9" />
                            <path d="m8 12 3 3 5-6" />
                            @elseif ($key === 'pending')
                            {{-- ساعة --}}
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 7v5l3 2" />
                            @elseif ($key === 'inactive')
                            {{-- علامة ناقص --}}
                            <circle cx="12" cy="12" r="9" />
                            <path d="M8 12h8" />
                            @else
                            {{-- مبنى لبطاقة Total --}}
                            <rect x="6" y="3" width="12" height="18" rx="2" />
                            <path d="M10 7h4m-4 4h4m-4 4h4" />
                            @endif

                        </svg>
                    </span>
                </span>
                <div>
                    <strong>{{ ucfirst($key) }}</strong>
                    <strong class="metric-number">{{ number_format($count) }}</strong>
                </div>
            </article>
            @endforeach
        </section>

        <div class="dashboard">
            <section class="card" aria-labelledby="directory-title">
                <div class="directory-header">
                    <div class="directory-title-row">
                        <div>
                            <p class="directory-eyebrow">Administration</p>
                            <h2 id="directory-title">Workspace directory</h2>
                            <p class="directory-copy">Review workspace activity, approval status, and room availability.</p>
                        </div>
                        <span class="directory-count">Total workspaces: {{ $workspaces->count() }}</span>
                    </div>
                    <div class="filters" role="search">
                        <div class="filter-field">
                            <label for="workspace-search">Search workspaces</label>
                            <input type="search" id="workspace-search"
                                placeholder="Search name, location or manager">
                        </div>

                        <div class="filter-field">
                            <label for="workspace-status">Status</label>
                            <select id="workspace-status">
                                <option value="">All statuses</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="filter-actions">
                            <button type="button" class="filter-button filter-button--reset" id="reset-workspace-filters">Reset</button>
                        </div>
                    </div>
                </div>

                <div class="table-scroll" tabindex="0" role="region" aria-label="Workspace results">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">Workspace</th>

                                <th scope="col">Location</th>
                                <th scope="col" class="rooms-column">Rooms</th>
                                <th scope="col">Manager</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="workspace-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workspaces as $workspace)

                            <tr data-workspace-status="{{ ! $workspace->is_active ? 'inactive' : ($workspace->approval_status === 'pending' ? 'pending' : 'active') }}">
                                <td data-label="Workspace">

                                    <a class="workspace workspace-details-link"
                                        href="{{ route('admin.workspace.details',$workspace->id ) }}">
                                        <span class="initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($workspace->name, 0, 1)) }}</span>
                                        <span>
                                            <span>{{ $workspace->name }}</span>
                                            <span class="workspace-details-label">View details <span aria-hidden="true">&rarr;</span></span>
                                        </span>
                                    </a>
                                </td>
                                <td data-label="Location">{{ $workspace->location ?: 'Location unavailable' }}</td>
                                <td class="rooms-column" data-label="Rooms">{{ $workspace->rooms->count() }}</td>
                                <td data-label="Manager">{{ $workspace->manager?->full_name ?? $workspace->manager?->name ?? 'Unassigned' }}</td>
                                <td data-label="Status">
                                    <span @class([ 'badge' , 'active'=> $workspace->approval_status === 'approved',
                                        'pending' => $workspace->approval_status === 'pending',
                                        'inactive' => ! in_array($workspace->approval_status, ['approved', 'pending'], true),
                                        ])>
                                        {{ ucfirst($workspace->approval_status) }}
                                    </span>
                                </td>
                                <td class="workspace-actions" data-label="Actions">
                                    <div class="workspace-actions-group">
                                        <a href="{{ route('admin.workspace.details', $workspace->id) }}"
                                           class="workspace-action workspace-action--view">
                                            View
                                        </a>

                                        @if (! $workspace->is_active)
                                        <form method="POST" action="{{ route('admin.Enable', $workspace) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="workspace-action workspace-action--enable">Enable</button>
                                        </form>
                                        @elseif ($workspace->approval_status === 'pending')
                                        <form method="POST"
                                            action="{{Route('admin.destroy',$workspace)}}"
                                            onsubmit="return confirm('Disable this workspace?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="workspace-action workspace-action--disable">Disable</button>
                                        </form>
                                        @elseif ($workspace->approval_status === 'approved')
                                        <form method="POST"
                                            action="{{Route('admin.destroy',$workspace)}}"
                                            onsubmit="return confirm('Disable this workspace?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="workspace-action workspace-action--disable">Disable</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            <tr id="workspace-empty" @if ($workspaces->isNotEmpty()) hidden @endif>
                                <td class="empty" colspan="6" role="status">
                                    {{ $workspaces->isEmpty() ? 'No workspaces yet.' : 'No workspaces match your filters.' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="directory-pagination" id="workspace-pagination" hidden>
                    <p id="workspace-pagination-summary" aria-live="polite"></p>
                    <div class="pagination-controls" id="workspace-pagination-controls"></div>
                </div>
            </section>


        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const searchInput = document.querySelector('#workspace-search');
        const statusFilter = document.querySelector('#workspace-status');
        const resetFiltersButton = document.querySelector('#reset-workspace-filters');
        const emptyRow = document.querySelector('#workspace-empty');
        const workspaceRows = document.querySelectorAll('[data-workspace-status]');
        const pagination = document.querySelector('#workspace-pagination');
        const paginationSummary = document.querySelector('#workspace-pagination-summary');
        const paginationControls = document.querySelector('#workspace-pagination-controls');
        const pageSize = 5;
        let currentPage = 1;

        function filterWorkspaces(resetPage = true) {
            if (resetPage) currentPage = 1;

            const search = searchInput.value.trim().toLocaleLowerCase();
            const status = statusFilter.value;
            const matchingRows = [];

            for (const row of workspaceRows) {
                const workspaceSearchFields = [row.querySelector('.workspace-details-link span span').textContent, row.cells[1].textContent, row.cells[3].textContent];
                const matchesSearch = workspaceSearchFields.some(value =>
                    value.toLocaleLowerCase().includes(search)
                );
                const matchesStatus = !status || row.dataset.workspaceStatus === status;

                if (matchesSearch && matchesStatus) matchingRows.push(row);
            }

            const totalPages = Math.max(1, Math.ceil(matchingRows.length / pageSize));
            currentPage = Math.min(currentPage, totalPages);
            const firstIndex = (currentPage - 1) * pageSize;
            const visibleRows = matchingRows.slice(firstIndex, firstIndex + pageSize);

            for (const row of workspaceRows) {
                row.hidden = !visibleRows.includes(row);
            }

            emptyRow.hidden = matchingRows.length > 0;
            renderPagination(matchingRows.length, totalPages, firstIndex);
        }

        function renderPagination(totalResults, totalPages, firstIndex) {
            paginationControls.replaceChildren();
            pagination.hidden = totalResults === 0;

            if (totalResults === 0) return;

            const lastResult = Math.min(firstIndex + pageSize, totalResults);
            paginationSummary.textContent = `Showing ${firstIndex + 1} to ${lastResult} of ${totalResults} results`;

            const addButton = (label, page, disabled = false, current = false) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = `page-button${current ? ' is-current' : ''}`;
                button.textContent = label;
                button.disabled = disabled;
                button.setAttribute('aria-label', label === '‹' ? 'Previous page' : label === '›' ? 'Next page' : `Page ${label}`);
                button.addEventListener('click', () => {
                    currentPage = page;
                    filterWorkspaces(false);
                });
                paginationControls.append(button);
            };

            addButton('‹', currentPage - 1, currentPage === 1);

            for (let page = 1; page <= totalPages; page++) {
                addButton(String(page), page, false, page === currentPage);
            }

            addButton('›', currentPage + 1, currentPage === totalPages);
        }

        resetFiltersButton.addEventListener('click', () => {
            searchInput.value = '';
            statusFilter.value = '';
            filterWorkspaces();
        });
        searchInput.addEventListener('input', () => filterWorkspaces());
        statusFilter.addEventListener('change', () => filterWorkspaces());
        filterWorkspaces();
    </script>
@endpush
@endif
