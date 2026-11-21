{{--
Manager Bookings Routes
use App\Http\Controllers\BookingController;

Route::get('/bookings', [BookingController::class, 'index'])
    ->name('bookings.index');

Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancelManagerBooking'])
    ->name('bookings.cancel');

--}}

@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Manager Bookings | NexOra @endsection
@section('page-title') Manager Bookings @endsection

@push('styles')
    <style>
        .nx-page-content *{ box-sizing: border-box; }

        .nx-page-content{
            margin: 0;
            font-family: 'Inter', 'Noto Sans Arabic', system-ui, sans-serif;
            background: #F5F7F3;
            color: #1D2924;
        }

        .nx-page-content .container{
            max-width: 1250px;
            margin: 40px auto;
            padding: 0 24px;
        }

        .nx-page-content .page-header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .nx-page-content h1{ margin: 0 0 8px; font-size: 28px; }
        .nx-page-content .subtitle{ color: #53615A; margin: 0; }

        .nx-page-content .card{
            background: white;
            border: 1px solid #E9EEE9;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 18px rgba(21, 42, 35,.04);
        }

        .nx-page-content .filters{
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .nx-page-content label{
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .nx-page-content select, .nx-page-content input, .nx-page-content textarea{
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #C9D4CC;
            border-radius: 8px;
            font: inherit;
            background: white;
        }

        .nx-page-content .filter-actions{
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .nx-page-content .btn{
            display: inline-block;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
        }

        .nx-page-content .btn-primary{ background: #315C4D; color: white; }
        .nx-page-content .btn-secondary{ background: #F5F7F3; color: #1D2924; }
        .nx-page-content .btn-outline{
            background: white;
            border: 1px solid #C9D4CC;
            color: #315C4D;
        }
        .nx-page-content .btn-danger{ background: #dc2626; color: white; }

        .nx-page-content .table-header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .nx-page-content .table-header h2{ margin: 0; font-size: 19px; }
        .nx-page-content .table-wrapper{ overflow-x: auto; }

        .nx-page-content table{
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            white-space: nowrap;
        }

        .nx-page-content th{
            background: #F5F7F3;
            color: #53615A;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .nx-page-content th, .nx-page-content td{
            padding: 15px 12px;
            border-bottom: 1px solid #F5F7F3;
        }

        .nx-page-content td{ font-size: 13px; }
        .nx-page-content tr:last-child td{ border-bottom: none; }

        .nx-page-content .member-name{ font-weight: 600; }
        .nx-page-content .muted{ color: #53615A; font-size: 12px; margin-top: 4px; }

        .nx-page-content .badge{
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .nx-page-content .pending{ background: #fef3c7; color: #C58A62; }
        .nx-page-content .confirmed, .nx-page-content .paid{ background: #dcfce7; color: #166534; }
        .nx-page-content .cancelled, .nx-page-content .failed{ background: #fee2e2; color: #991b1b; }
        .nx-page-content .completed{ background: #E7EEE7; color: #315C4D; }
        .nx-page-content .expired{ background: #E9EEE9; color: #53615A; }

        .nx-page-content .actions{ display: flex; gap: 8px; }

        .nx-page-content .modal{
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(21, 42, 35,.55);
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 100;
        }

        .nx-page-content .modal:target{ display: flex; }

        .nx-page-content .modal-content{
            background: white;
            border-radius: 14px;
            width: 100%;
            max-width: 520px;
            padding: 26px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .nx-page-content .modal-header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .nx-page-content .modal-header h2{ margin: 0; font-size: 21px; }

        .nx-page-content .close{
            text-decoration: none;
            font-size: 25px;
            color: #53615A;
        }

        .nx-page-content .detail-row{
            display: flex;
            justify-content: space-between;
            padding: 13px 0;
            border-bottom: 1px solid #F5F7F3;
            gap: 16px;
        }

        .nx-page-content .detail-row span:first-child{ color: #53615A; }
        .nx-page-content .detail-row strong{ text-align: right; overflow-wrap: anywhere; }

        .nx-page-content .modal-actions{
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .nx-page-content .btn-link{
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }

        @media (max-width: 850px) {
            .nx-page-content .filters{ grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 520px) {
            .nx-page-content .filters{ grid-template-columns: 1fr; }
            .nx-page-content .page-header{ align-items: flex-start; }
            .nx-page-content .container{ padding: 0 12px; margin-top: 24px; }
            .nx-page-content .card{ padding: 16px; }
        }

/* =====================================================
   NexOra — Manager Bookings Premium UI
   Append at the end of the page styles
===================================================== */

.nx-page-content {
    min-height: 100vh;
    padding: 1px 0 45px;
    background:
        radial-gradient(ellipse at 8% 4%, rgba(197,138,98,.10), transparent 28%),
        radial-gradient(ellipse at 92% 18%, rgba(49,92,77,.09), transparent 30%),
        #F5F7F3;
}

/* Balanced distance from dashboard navbar */
.nx-page-content > .container {
    max-width: 1320px;
    margin: 22px auto 0;
    padding: 0 28px;
}

/* Page heading */
.nx-page-content .page-header {
    position: relative;
    margin-bottom: 25px;
    padding: 22px 26px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,.65);
    border-radius: 20px;
    background: linear-gradient(115deg, #203D34 0%, #315C4D 65%, #557565 100%);
    box-shadow: 0 12px 30px rgba(32,61,52,.13);
    animation: bookingsEnter .55s ease both;
}

.nx-page-content .page-header::before,
.nx-page-content .page-header::after {
    content: "";
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}

.nx-page-content .page-header::before {
    width: 230px;
    height: 230px;
    right: 5%;
    top: -145px;
    background: rgba(255,255,255,.07);
}

.nx-page-content .page-header::after {
    width: 150px;
    height: 150px;
    right: 22%;
    bottom: -115px;
    background: rgba(197,138,98,.20);
}

.nx-page-content .page-header > div {
    position: relative;
    z-index: 1;
}

.nx-page-content .page-header h1 {
    color: #fff;
    font-family: var(--nx-font-heading);
    font-size: clamp(23px, 3vw, 30px);
    font-weight: 800;
    letter-spacing: -.7px;
    margin: 0 0 7px;
}

.nx-page-content .page-header .subtitle {
    color: rgba(255,255,255,.76);
    font-size: 13px;
    margin: 0;
}

/* Main cards */
.nx-page-content .card {
    position: relative;
    padding: 25px;
    margin-bottom: 22px;
    border: 1px solid #E4EAE3;
    border-radius: 19px;
    background: rgba(255,255,255,.94);
    box-shadow: 0 5px 22px rgba(29,41,36,.045);
    transition: box-shadow .25s ease, transform .25s ease;
    animation: bookingsEnter .55s ease both;
}

.nx-page-content .card:hover {
    box-shadow: 0 10px 28px rgba(29,41,36,.075);
}

/* Success and error messages */
.nx-page-content .card[style*="color: green"],
.nx-page-content .card[style*="color: red"] {
    padding: 15px 19px;
    border-radius: 13px;
    font-size: 13px;
    font-weight: 600;
}

.nx-page-content .card[style*="color: green"] {
    background: #ECF7F0;
    border-color: #C7E8D2;
}

.nx-page-content .card[style*="color: red"] {
    background: #FFF0EE;
    border-color: #F3D0CB;
}

/* Filter section */
.nx-page-content .filters {
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
}

.nx-page-content label {
    color: #34483E;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .1px;
    margin-bottom: 9px;
}

.nx-page-content select,
.nx-page-content input {
    min-height: 46px;
    padding: 11px 14px;
    border: 1px solid #DCE5DC;
    border-radius: 11px;
    background-color: #FBFCFA;
    color: #263B31;
    font-size: 13px;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.nx-page-content select:hover,
.nx-page-content input:hover {
    border-color: #9DB4A3;
    background: #fff;
}

.nx-page-content select:focus,
.nx-page-content input:focus {
    border-color: #557D68;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(49,92,77,.10);
}

.nx-page-content .filter-actions {
    margin-top: 22px;
    padding-top: 18px;
    border-top: 1px solid #EEF1ED;
}

.nx-page-content .btn {
    min-height: 42px;
    padding: 10px 19px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}

.nx-page-content .btn:hover {
    transform: translateY(-2px);
}

.nx-page-content .btn-primary {
    background: linear-gradient(135deg, #315C4D, #42745E);
    color: #fff;
    box-shadow: 0 5px 12px rgba(49,92,77,.16);
}

.nx-page-content .btn-primary:hover {
    background: linear-gradient(135deg, #274B3F, #315C4D);
    box-shadow: 0 7px 16px rgba(49,92,77,.22);
}

.nx-page-content .btn-secondary {
    background: #F0F3EF;
    color: #43564B;
    border: 1px solid #E1E8E0;
}

.nx-page-content .btn-outline {
    background: #F7FAF7;
    color: #315C4D;
    border: 1px solid #D8E4D9;
}

.nx-page-content .btn-outline:hover {
    background: #EAF2EB;
    border-color: #AFC7B3;
}

.nx-page-content .btn-danger {
    background: #B9433B;
    color: #fff;
    box-shadow: 0 4px 10px rgba(185,67,59,.12);
}

.nx-page-content .btn-danger:hover {
    background: #9F342E;
    box-shadow: 0 6px 14px rgba(185,67,59,.2);
}

/* Table heading */
.nx-page-content .table-header {
    margin-bottom: 18px;
    padding-bottom: 16px;
    border-bottom: 1px solid #EDF1EC;
}

.nx-page-content .table-header h2 {
    color: #203D34;
    font-size: 19px;
    font-weight: 800;
}

.nx-page-content .table-header .muted {
    margin: 0;
    padding: 7px 12px;
    border-radius: 30px;
    background: #EDF3ED;
    color: #315C4D;
    font-size: 11px;
    font-weight: 700;
}

/* Bookings table */
.nx-page-content .table-wrapper {
    overflow-x: auto;
    border: 1px solid #E9EEE8;
    border-radius: 14px;
    scrollbar-width: thin;
    scrollbar-color: #B9C9BB #F3F6F2;
}

.nx-page-content table {
    min-width: 1000px;
    border-collapse: separate;
    border-spacing: 0;
}

.nx-page-content th {
    padding: 16px 14px;
    background: #F1F5F0;
    color: #64736A;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .65px;
    border-bottom: 1px solid #E4EBE3;
}

.nx-page-content td {
    padding: 16px 14px;
    color: #34443B;
    font-size: 12px;
    vertical-align: middle;
    border-bottom: 1px solid #EEF2ED;
}

.nx-page-content tbody tr {
    transition: background .2s ease;
}

.nx-page-content tbody tr:hover {
    background: #F8FBF7;
}

.nx-page-content tbody tr:last-child td {
    border-bottom: none;
}

.nx-page-content td strong {
    color: #315C4D;
    font-weight: 800;
}

.nx-page-content .member-name {
    color: #263B31;
    font-size: 12px;
    font-weight: 750;
}

.nx-page-content .muted {
    color: #87938B;
    font-size: 11px;
    line-height: 1.6;
}

/* Status badges */
.nx-page-content .badge {
    padding: 6px 11px;
    border: 1px solid transparent;
    border-radius: 30px;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .15px;
}

.nx-page-content .pending {
    color: #93620C;
    background: #FFF5DF;
    border-color: #F2E1B9;
}

.nx-page-content .confirmed,
.nx-page-content .paid {
    color: #247247;
    background: #EAF6EE;
    border-color: #CDE8D5;
}

.nx-page-content .cancelled,
.nx-page-content .failed {
    color: #A33B35;
    background: #FCEFED;
    border-color: #F1D2CE;
}

.nx-page-content .completed {
    color: #315C4D;
    background: #EAF1EB;
    border-color: #D3E1D4;
}

.nx-page-content .expired {
    color: #68756D;
    background: #F0F2EF;
    border-color: #E1E5E0;
}

/* Row actions */
.nx-page-content .actions {
    align-items: center;
    gap: 7px;
}

.nx-page-content .actions .btn {
    min-height: 33px;
    padding: 7px 12px;
    font-size: 10px;
    border-radius: 8px;
}

/* Details and cancellation modal */
.nx-page-content .modal {
    z-index: 2000;
    background: rgba(17,35,28,.62);
    backdrop-filter: blur(5px);
    animation: modalFade .2s ease;
}

.nx-page-content .modal-content {
    max-width: 540px;
    padding: 27px;
    border: 1px solid rgba(255,255,255,.7);
    border-radius: 20px;
    box-shadow: 0 24px 70px rgba(13,30,22,.25);
    animation: modalUp .25s ease;
}

.nx-page-content .modal-header {
    padding-bottom: 17px;
    border-bottom: 1px solid #EDF1EC;
}

.nx-page-content .modal-header h2 {
    color: #203D34;
    font-size: 20px;
    font-weight: 800;
}

.nx-page-content .close {
    display: grid;
    place-items: center;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #F0F3EF;
    color: #53615A;
    font-size: 22px;
    transition: .2s ease;
}

.nx-page-content .close:hover {
    background: #E5ECE5;
    color: #203D34;
    transform: rotate(90deg);
}

.nx-page-content .detail-row {
    padding: 14px 0;
    border-bottom: 1px solid #EEF2ED;
    font-size: 12px;
}

.nx-page-content .detail-row span:first-child {
    color: #7A887F;
    font-weight: 600;
}

.nx-page-content .detail-row strong {
    color: #263B31;
    font-weight: 750;
}

.nx-page-content .modal-actions {
    padding-top: 17px;
    border-top: 1px solid #EEF2ED;
}

.nx-page-content .modal .subtitle {
    color: #718078;
    font-size: 13px;
    line-height: 1.7;
}

/* Pagination */
.nx-page-content .bookings-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 22px;
    padding: 16px 18px;
    border: 1px solid #E7EDE6;
    border-radius: 14px;
    background: #FBFCFA;
}

.nx-page-content .bookings-pagination .pagination {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    margin: 0;
}

.nx-page-content .bookings-pagination .page-item {
    list-style: none;
}

.nx-page-content .bookings-pagination .page-link {
    display: grid;
    place-items: center;
    min-width: 36px;
    min-height: 36px;
    padding: 7px 11px;
    border: 1px solid #E1E9E0;
    border-radius: 9px !important;
    background: #fff;
    color: #53665A;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: .2s ease;
}

.nx-page-content .bookings-pagination .page-link:hover {
    border-color: #AFC7B3;
    background: #EDF4ED;
    color: #315C4D;
}

.nx-page-content .bookings-pagination .page-item.active .page-link {
    border-color: #315C4D;
    background: #315C4D;
    color: #fff;
    box-shadow: 0 4px 10px rgba(49,92,77,.17);
}

.nx-page-content .bookings-pagination .page-item.disabled .page-link {
    opacity: .4;
    pointer-events: none;
}

.nx-page-content .bookings-pagination .pagination-summary {
    color: #7B8980;
    font-size: 11px;
    font-weight: 600;
}

/* Animations */
@keyframes bookingsEnter {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes modalFade {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalUp {
    from { opacity: 0; transform: translateY(12px) scale(.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* Responsive */
@media (max-width: 900px) {
    .nx-page-content .filters {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 600px) {
    .nx-page-content > .container {
        margin-top: 16px;
        padding: 0 13px;
    }

    .nx-page-content .page-header {
        padding: 20px;
        margin-bottom: 17px;
        border-radius: 16px;
    }

    .nx-page-content .card {
        padding: 17px;
        border-radius: 15px;
    }

    .nx-page-content .filters {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .nx-page-content .filter-actions {
        flex-direction: column-reverse;
    }

    .nx-page-content .filter-actions .btn {
        width: 100%;
    }

    .nx-page-content .table-header {
        align-items: flex-start;
        gap: 10px;
    }

    .nx-page-content .bookings-pagination {
        justify-content: center;
    }

    .nx-page-content .modal-content {
        padding: 19px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .nx-page-content *,
    .nx-page-content *::before,
    .nx-page-content *::after {
        animation-duration: .01ms !important;
        transition-duration: .01ms !important;
    }

}

/* =========================================
   Bookings Table - No Horizontal Scroll
========================================= */

/* إلغاء السكرول الأفقي */
.table-wrapper {
    width: 100%;
    max-width: 100%;
    overflow-x: hidden !important;
    overflow-y: visible;
}

/* عرض الجدول بكامل المساحة */
.table-wrapper table {
    width: 100%;
    min-width: 0 !important;
    table-layout: fixed;
    border-collapse: collapse;
}

/* توزيع الأعمدة */
.table-wrapper th,
.table-wrapper td {
    padding: 13px 8px;
    white-space: normal !important;
    overflow-wrap: anywhere;
    word-break: normal;
    text-align: center;
    vertical-align: middle;
    font-size: 12px;
}

/* عناوين الأعمدة */
.table-wrapper th {
    font-size: 11px;
    font-weight: 800;
    line-height: 1.5;
}

/* إخفاء أي أسهم أو أزرار خاصة بالسكرول */
.table-scroll-arrow,
.scroll-arrow,
.table-scroll-btn {
    display: none !important;
}

/* تحسين العرض على الشاشات المتوسطة */
@media (max-width: 1200px) {
    .table-wrapper th,
    .table-wrapper td {
        padding: 10px 5px;
        font-size: 11px;
    }
}

/* تحسين العرض على الموبايل */
@media (max-width: 768px) {
    .table-wrapper {
        overflow-x: hidden !important;
    }

    .table-wrapper table {
        table-layout: auto;
    }

    .table-wrapper th,
    .table-wrapper td {
        padding: 8px 4px;
        font-size: 10px;
    }
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
@endpush

@section('content')
<div class="nx-page-content">
<div class="container">
    <header class="page-header">
        <div>
            <h1>Manager Bookings</h1>
            <p class="subtitle">View and manage bookings across your workspaces.</p>
        </div>
    </header>
@if(session('success'))
    <div class="card" style="color: green;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="card" style="color: red;">
        {{ $errors->first() }}
    </div>
@endif
    <!-- Filters -->
    <section class="card">
<form action="{{ route('manager.bookings.index') }}" method="GET">
            <div class="filters">
                  <div>
    <label for="status">Booking Status</label>

    <select id="status" name="status">
        <option value="">All Statuses</option>

        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
            Pending
        </option>

        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
            Confirmed
        </option>

        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
            Cancelled
        </option>

        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
            Completed
        </option>

        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
            Expired
        </option>
    </select>
</div>

               <div>
    <label for="payment_status">Payment Status</label>

    <select id="payment_status" name="payment_status">
        <option value="">All Payments</option>

        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
            Pending
        </option>

        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
            Paid
        </option>

        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
            Failed
        </option>
    </select>
</div>

         <div>
    <label for="workspace_id">Workspace</label>

    <select id="workspace_id" name="workspace_id">
        <option value="">All Workspaces</option>

        @foreach($workspaces as $workspace)
            <option value="{{ $workspace->id }}"
                {{ request('workspace_id') == $workspace->id ? 'selected' : '' }}>
                {{ $workspace->name }}
            </option>
        @endforeach

    </select>
</div>
                <div>
                    <label for="date">Booking Date</label>
                    <input type="date" id="date" name="date" value="{{ request('date') }}">
                </div>
            </div>

            <div class="filter-actions">
                <a href="{{ route('manager.bookings.index') }}" class="btn btn-secondary btn-link">Reset</a>
                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </div>
        </form>
    </section>

    <!-- Bookings Table -->
    <section class="card">
        <div class="table-header">
            <h2>All Bookings</h2>
<span class="muted">{{ $bookings->count() }} bookings</span>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Workspace / Room</th>
                        <th>Time Slot</th>
                        <th>Amount</th>
                        <th>Booking Status</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
               
<tbody>
    @forelse($bookings as $booking)
        <tr>
        
            <!-- Member -->
            <td>
                <div class="member-name">
                    {{ $booking->user?->name ?? 'N/A' }}
                </div>
                
            </td>

            <!-- Workspace / Room -->
            <td>
                <div>{{ $booking->room?->workspace?->name ?? 'N/A' }}</div>
                <div class="muted">{{ $booking->room?->name ?? 'N/A' }}</div>
            </td>

            <!-- Time Slot -->
            <td>
                <div>
                    {{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y') }}
                </div>
                <div class="muted">
                    {{ \Carbon\Carbon::parse($booking->start_date_time)->format('h:i A') }}
                    -
                    {{ \Carbon\Carbon::parse($booking->end_date_time)->format('h:i A') }}
                </div>
            </td>

            <!-- Amount -->
            <td>
                {{ number_format($booking->total_price, 2) }} JOD
            </td>

            <!-- Booking Status -->
            <td>
                <span class="badge {{ $booking->status }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </td>

            <!-- Payment Status -->
            @php
                $paymentStatus = $booking->payments->sortByDesc('id')->first()?->status ?? 'pending';
            @endphp

            <td>
                <span class="badge {{ $paymentStatus }}">
                    {{ ucfirst($paymentStatus) }}
                </span>
            </td>

            <!-- Actions -->
           

<td>
    <div class="actions">
        <a href="#details-{{ $booking->id }}"
           class="btn btn-outline btn-link icon-action view-icon"
           title="View Details"
           aria-label="View Details">
            <svg viewBox="0 0 24 24" width="18" height="18"
                 fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </a>

        @if(in_array($booking->status, ['pending', 'confirmed']))
            <a href="#cancel-{{ $booking->id }}"
               class="btn btn-danger btn-link icon-action cancel-icon"
               title="Cancel Booking"
               aria-label="Cancel Booking">
                <svg viewBox="0 0 24 24" width="18" height="18"
                     fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </a>
        @endif
    </div>
</td>


        </tr>
    @empty
        <tr><td colspan="8" style="text-align:center;padding:30px;color:#53615A;">No bookings found.</td></tr>
    @endforelse
</tbody>


               
            </table>
        </div>

<div class="bookings-pagination">
    <span class="pagination-summary">
        Showing {{ $bookings->firstItem() ?? 0 }}–{{ $bookings->lastItem() ?? 0 }}
        of {{ $bookings->total() }} bookings
    </span>

    {{ $bookings->onEachSide(1)->links() }}
</div>

    </section>
</div>

<!-- Details and cancellation modals for each booking -->
@foreach($bookings as $booking)
    @php
        $latestPaymentStatus = $booking->payments->sortByDesc('id')->first()?->status ?? 'pending';
    @endphp

    <div id="details-{{ $booking->id }}" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Booking Details</h2>
                <a href="#" class="close" aria-label="Close">&times;</a>
            </div>
           
            <div class="detail-row"><span>Member</span><strong>{{ $booking->user?->name ?? 'N/A' }}</strong></div>
            
            <div class="detail-row"><span>Workspace</span><strong>{{ $booking->room?->workspace?->name ?? 'N/A' }}</strong></div>
            <div class="detail-row"><span>Room</span><strong>{{ $booking->room?->name ?? 'N/A' }}</strong></div>
            <div class="detail-row"><span>Start</span><strong>{{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y, h:i A') }}</strong></div>
            <div class="detail-row"><span>End</span><strong>{{ \Carbon\Carbon::parse($booking->end_date_time)->format('d M Y, h:i A') }}</strong></div>
            <div class="detail-row"><span>Amount</span><strong>{{ number_format($booking->total_price, 2) }} JOD</strong></div>
            <div class="detail-row"><span>Booking Status</span><span class="badge {{ $booking->status }}">{{ ucfirst($booking->status) }}</span></div>
            <div class="detail-row"><span>Payment Status</span><span class="badge {{ $latestPaymentStatus }}">{{ ucfirst($latestPaymentStatus) }}</span></div>
            @if($booking->cancellation_reason ?? null)
                <div class="detail-row"><span>Cancellation Reason</span><strong>{{ $booking->cancellation_reason }}</strong></div>
            @endif
            <div class="modal-actions"><a href="#" class="btn btn-secondary btn-link">Close</a></div>
        </div>
    </div>

    @if(in_array($booking->status, ['pending', 'confirmed']))
        <div id="cancel-{{ $booking->id }}" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Cancel Booking #BK-{{ $booking->id }}</h2>
                    <a href="#" class="close" aria-label="Close">&times;</a>
                </div>
                <p class="subtitle">Please provide a reason for cancelling this booking.</p>
                {{-- This form requires the manager.bookings.cancel route and controller cancellation method. --}}
               <form action="{{ route('manager.bookings.cancel', $booking->id) }}" method="POST">
    @csrf
    @method('PATCH')

    <p>Are you sure you want to cancel this booking?</p>

    <div class="modal-actions">
        <a href="#" class="btn btn-secondary btn-link">
            Keep Booking
        </a>

        <button type="submit" class="btn btn-danger">
            Confirm Cancellation
        </button>
    </div>
</form>
            </div>
        </div>
    @endif
@endforeach
</div>
@endsection
