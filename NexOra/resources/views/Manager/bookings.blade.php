
@extends('layouts.dashboard')

@section('dashboard-role', 'manager')
@section('title', 'Manager Bookings | NexOra')
@section('page-title', 'Manager Bookings')

@push('styles')
@include('layouts.dashboard.partials.app-compat-styles')

<style>
/* =========================================================
   NexOra — Manager Bookings
   Scoped page styles | Shared NexOra design system
========================================================= */

.nx-bookings-page,
.nx-bookings-page * {
    box-sizing: border-box;
}

.nx-bookings-page {
    --bk-primary: var(--nx-primary, #315c4d);
    --bk-dark: var(--nx-sidebar, #203d34);
    --bk-accent: var(--nx-accent, #c58a62);
    --bk-bg: var(--nx-bg, #f5f7f3);
    --bk-card: var(--nx-card, #fff);
    --bk-text: var(--nx-text, #1d2924);
    --bk-muted: var(--nx-text-muted, #7f8b84);
    --bk-border: var(--nx-border, #e2e8e2);
    --bk-soft: #f7f9f6;

    width: 100%;
    min-height: 100%;
    padding: 1px 0 38px;
    color: var(--bk-text);
    font-family: var(--nx-font-body, "Inter", sans-serif);
    background:
        radial-gradient(ellipse at 5% 0%, rgba(197,138,98,.07), transparent 28%),
        var(--bk-bg);
}

.nx-bookings-page .bk-container {
    width: 100%;
    max-width: 1440px;
    margin: 22px auto 0;
    padding: 0 28px;
}

/* Header */
.nx-bookings-page .bk-header {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 150px;
    margin-bottom: 22px;
    padding: 28px 32px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 20px;
    background: linear-gradient(115deg, #203d34 0%, #315c4d 65%, #557565 100%);
    box-shadow: 0 12px 28px rgba(32,61,52,.12);
}

.nx-bookings-page .bk-header::before,
.nx-bookings-page .bk-header::after {
    position: absolute;
    content: "";
    border-radius: 50%;
    pointer-events: none;
}

.nx-bookings-page .bk-header::before {
    width: 250px;
    height: 250px;
    top: -155px;
    right: 7%;
    background: rgba(255,255,255,.055);
}

.nx-bookings-page .bk-header::after {
    width: 170px;
    height: 170px;
    right: 25%;
    bottom: -125px;
    background: rgba(197,138,98,.17);
}

.nx-bookings-page .bk-header-content {
    position: relative;
    z-index: 1;
}

.nx-bookings-page .bk-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 11px;
    color: #d7e4d9;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

.nx-bookings-page .bk-eyebrow::before {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #c58a62;
    content: "";
}

.nx-bookings-page .bk-header h1 {
    margin: 0 0 8px;
    color: #fff;
    font-family: var(--nx-font-heading, "Manrope", sans-serif);
    font-size: clamp(25px, 3vw, 32px);
    font-weight: 800;
    letter-spacing: -.8px;
}

.nx-bookings-page .bk-header p {
    margin: 0;
    color: rgba(255,255,255,.76);
    font-size: 13px;
    line-height: 1.6;
}

.nx-bookings-page .bk-header-icon {
    position: relative;
    z-index: 1;
    display: grid;
    width: 62px;
    height: 62px;
    flex: 0 0 62px;
    place-items: center;
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 18px;
    background: rgba(255,255,255,.1);
    color: #fff;
}

.nx-bookings-page .bk-header-icon svg {
    width: 29px;
    height: 29px;
}

/* Alerts */
.nx-bookings-page .bk-alert {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 17px;
    padding: 14px 17px;
    border: 1px solid;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    animation: bkFadeUp .35s ease both;
}

.nx-bookings-page .bk-alert-success {
    color: #236b43;
    border-color: #cce7d4;
    background: #eff8f1;
}

.nx-bookings-page .bk-alert-error {
    color: #a33b35;
    border-color: #f0d1cd;
    background: #fff2f0;
}

.nx-bookings-page .bk-alert svg {
    width: 18px;
    height: 18px;
    flex: 0 0 18px;
}

/* General card */
.nx-bookings-page .bk-card {
    margin-bottom: 20px;
    padding: 24px;
    border: 1px solid var(--bk-border);
    border-radius: 17px;
    background: var(--bk-card);
    box-shadow: 0 3px 14px rgba(29,41,36,.035);
    animation: bkFadeUp .45s ease both;
}

.nx-bookings-page .bk-section-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 19px;
}

.nx-bookings-page .bk-section-icon {
    display: grid;
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    place-items: center;
    border-radius: 11px;
    background: #edf3ed;
    color: var(--bk-primary);
}

.nx-bookings-page .bk-section-icon svg {
    width: 19px;
    height: 19px;
}

.nx-bookings-page .bk-section-heading h2 {
    margin: 0 0 3px;
    color: var(--bk-dark);
    font-family: var(--nx-font-heading, "Manrope", sans-serif);
    font-size: 16px;
    font-weight: 800;
}

.nx-bookings-page .bk-section-heading p {
    margin: 0;
    color: var(--bk-muted);
    font-size: 11px;
}

/* Filters */
.nx-bookings-page .bk-filters {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
}

.nx-bookings-page .bk-field {
    min-width: 0;
}

.nx-bookings-page .bk-field label {
    display: block;
    margin-bottom: 8px;
    color: #405248;
    font-size: 11px;
    font-weight: 800;
}

.nx-bookings-page .bk-field select,
.nx-bookings-page .bk-field input {
    display: block;
    width: 100%;
    min-width: 0;
    height: 44px;
    padding: 0 13px;
    border: 1px solid #dfe6de;
    border-radius: 10px;
    outline: none;
    background: #fbfcfa;
    color: #304238;
    font-family: inherit;
    font-size: 12px;
    transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
}

.nx-bookings-page .bk-field select:hover,
.nx-bookings-page .bk-field input:hover {
    border-color: #aabdaf;
    background: #fff;
}

.nx-bookings-page .bk-field select:focus,
.nx-bookings-page .bk-field input:focus {
    border-color: var(--bk-primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(49,92,77,.11);
}

.nx-bookings-page .bk-filter-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-top: 18px;
    padding-top: 15px;
    border-top: 1px solid #edf1ec;
}

.nx-bookings-page .bk-filter-hint {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: var(--bk-muted);
    font-size: 11px;
}

.nx-bookings-page .bk-filter-hint svg {
    width: 14px;
    height: 14px;
    color: var(--bk-primary);
}

.nx-bookings-page .bk-reset {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 36px;
    padding: 8px 13px;
    border: 1px solid #dfe7df;
    border-radius: 9px;
    background: #fff;
    color: #53665a;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    transition: .2s ease;
}

.nx-bookings-page .bk-reset:hover {
    border-color: #b6c9b9;
    background: #f0f5ef;
    color: var(--bk-primary);
}

.nx-bookings-page .bk-reset svg {
    width: 14px;
    height: 14px;
}

/* Table heading */
.nx-bookings-page .bk-table-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 18px;
}

.nx-bookings-page .bk-table-title {
    display: flex;
    align-items: center;
    gap: 11px;
}

.nx-bookings-page .bk-table-title h2 {
    margin: 0 0 4px;
    color: var(--bk-dark);
    font-family: var(--nx-font-heading, "Manrope", sans-serif);
    font-size: 17px;
    font-weight: 800;
}

.nx-bookings-page .bk-table-title p {
    margin: 0;
    color: var(--bk-muted);
    font-size: 11px;
}

.nx-bookings-page .bk-count {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border: 1px solid #dce8dc;
    border-radius: 30px;
    background: #f0f5ef;
    color: var(--bk-primary);
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

/* Table */
.nx-bookings-page .bk-table-wrap {
    width: 100%;
    overflow-x: auto;
    border: 1px solid #e8ede7;
    border-radius: 13px;
    scrollbar-width: thin;
    scrollbar-color: #c5d2c6 #f4f6f3;
}

.nx-bookings-page .bk-table {
    width: 100%;
    min-width: 850px;
    border-collapse: separate;
    border-spacing: 0;
    text-align: left;
}

.nx-bookings-page .bk-table th {
    padding: 14px 13px;
    border-bottom: 1px solid #e6ece5;
    background: #f5f8f4;
    color: #718078;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: .55px;
    text-transform: uppercase;
    white-space: nowrap;
}

.nx-bookings-page .bk-table td {
    padding: 15px 13px;
    border-bottom: 1px solid #edf1ec;
    color: #35463c;
    font-size: 12px;
    vertical-align: middle;
}

.nx-bookings-page .bk-table tbody tr {
    transition: background .18s ease;
}

.nx-bookings-page .bk-table tbody tr:hover {
    background: #f9fbf8;
}

.nx-bookings-page .bk-table tbody tr:last-child td {
    border-bottom: 0;
}

.nx-bookings-page .bk-member {
    color: #263b31;
    font-size: 12px;
    font-weight: 800;
}

.nx-bookings-page .bk-subtext {
    margin-top: 5px;
    color: #87938b;
    font-size: 10px;
    line-height: 1.5;
}

.nx-bookings-page .bk-workspace {
    color: #304238;
    font-weight: 700;
}

.nx-bookings-page .bk-date {
    color: #304238;
    font-weight: 700;
    white-space: nowrap;
}

.nx-bookings-page .bk-time {
    margin-top: 5px;
    color: #87938b;
    font-size: 10px;
    white-space: nowrap;
}

.nx-bookings-page .bk-amount {
    color: var(--bk-dark);
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}

/* Statuses */
.nx-bookings-page .bk-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 25px;
    padding: 5px 10px;
    border: 1px solid transparent;
    border-radius: 30px;
    font-size: 10px;
    font-weight: 800;
    line-height: 1.2;
    white-space: nowrap;
}

.nx-bookings-page .bk-badge.pending {
    border-color: #f1e1bd;
    background: #fff6e5;
    color: #865c13;
}

.nx-bookings-page .bk-badge.confirmed,
.nx-bookings-page .bk-badge.paid {
    border-color: #cce7d4;
    background: #eaf6ee;
    color: #236b43;
}

.nx-bookings-page .bk-badge.cancelled,
.nx-bookings-page .bk-badge.failed {
    border-color: #f0d1cd;
    background: #fcefed;
    color: #a33b35;
}

.nx-bookings-page .bk-badge.completed {
    border-color: #d5e2d5;
    background: #eaf1eb;
    color: #315c4d;
}

.nx-bookings-page .bk-badge.expired {
    border-color: #e2e7e1;
    background: #f1f3f0;
    color: #68756d;
}

/* Actions */
.nx-bookings-page .bk-actions {
    display: flex;
    align-items: center;
    gap: 7px;
}

.nx-bookings-page .bk-action {
    display: inline-grid;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    place-items: center;
    border: 1px solid;
    border-radius: 9px;
    text-decoration: none;
    transition: transform .18s ease, background .18s ease, border-color .18s ease;
}

.nx-bookings-page .bk-action:hover {
    transform: translateY(-2px);
}

.nx-bookings-page .bk-action svg {
    width: 16px;
    height: 16px;
}

.nx-bookings-page .bk-view {
    border-color: #dce7dc;
    background: #f1f6f0;
    color: #315c4d;
}

.nx-bookings-page .bk-view:hover {
    border-color: #a9c4ac;
    background: #e5efe4;
}

.nx-bookings-page .bk-cancel {
    border-color: #f0d8d5;
    background: #fff4f2;
    color: #b3433b;
}

.nx-bookings-page .bk-cancel:hover {
    border-color: #e6bcb6;
    background: #fde9e6;
}

/* Empty state */
.nx-bookings-page .bk-empty {
    padding: 48px 20px !important;
    text-align: center;
}

.nx-bookings-page .bk-empty-icon {
    display: grid;
    width: 54px;
    height: 54px;
    margin: 0 auto 13px;
    place-items: center;
    border-radius: 16px;
    background: #f0f5ef;
    color: #63816c;
}

.nx-bookings-page .bk-empty-icon svg {
    width: 25px;
    height: 25px;
}

.nx-bookings-page .bk-empty strong {
    display: block;
    margin-bottom: 5px;
    color: #304238;
    font-size: 13px;
}

.nx-bookings-page .bk-empty span {
    color: var(--bk-muted);
    font-size: 11px;
}
/* =========================================================
   Pagination — Laravel Compatible
========================================================= */

.nx-bookings-page .bk-pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 18px;
    padding: 13px 15px;
    border: 1px solid #e8ede7;
    border-radius: 12px;
    background: #fbfcfa;
}

.nx-bookings-page .bk-pagination-summary {
    color: #7b8980;
    font-size: 11px;
    font-weight: 600;
}

/* Laravel pagination navigation */
.nx-bookings-page .bk-pagination nav {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 7px;
}

/* Main Laravel wrappers */
.nx-bookings-page .bk-pagination nav > div {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 7px;
}

/* Mobile previous / next wrapper */
.nx-bookings-page .bk-pagination nav > div:first-child {
    display: flex;
    align-items: center;
    gap: 7px;
}

/* Desktop pagination wrapper */
.nx-bookings-page .bk-pagination nav > div:last-child {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Hide Laravel's internal "Showing..." text
   because we already have our own summary */
.nx-bookings-page .bk-pagination nav p {
    display: none;
}

/* Number buttons wrapper */
.nx-bookings-page .bk-pagination nav > div:last-child > div:last-child,
.nx-bookings-page .bk-pagination nav > div:last-child > span {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
}

/* Pagination buttons */
.nx-bookings-page .bk-pagination nav a,
.nx-bookings-page .bk-pagination nav span[aria-current],
.nx-bookings-page .bk-pagination nav span[aria-disabled] {
    display: inline-flex;
    min-width: 35px;
    height: 35px;
    align-items: center;
    justify-content: center;
    padding: 0 10px;
    border: 1px solid #e1e9e0;
    border-radius: 8px;
    background: #fff;
    color: #53665a;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    line-height: 1;
    text-decoration: none;
    transition: all .2s ease;
}

/* Hover */
.nx-bookings-page .bk-pagination nav a:hover {
    border-color: #afc7b3;
    background: #edf4ed;
    color: var(--bk-primary);
    transform: translateY(-1px);
}

/* Current page */
.nx-bookings-page .bk-pagination nav span[aria-current="page"] {
    border-color: var(--bk-primary);
    background: var(--bk-primary);
    color: #fff;
    box-shadow: 0 3px 8px rgba(49,92,77,.16);
}

/* Disabled buttons */
.nx-bookings-page .bk-pagination nav span[aria-disabled="true"] {
    background: #f4f6f3;
    color: #a4aea6;
    border-color: #e8ece7;
    cursor: not-allowed;
    opacity: .7;
}

/* Pagination icons */
.nx-bookings-page .bk-pagination nav svg {
    width: 15px;
    height: 15px;
}

/* Responsive */
@media (max-width: 650px) {
    .nx-bookings-page .bk-pagination {
        justify-content: center;
        padding: 13px;
    }

    .nx-bookings-page .bk-pagination-summary {
        width: 100%;
        text-align: center;
    }

    .nx-bookings-page .bk-pagination nav {
        width: 100%;
        justify-content: center;
    }

    .nx-bookings-page .bk-pagination nav > div {
        justify-content: center;
    }
}

/* Modals */
.nx-bookings-page .bk-modal {
    position: fixed;
    z-index: 3000;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow-y: auto;
    background: rgba(17,35,28,.62);
    backdrop-filter: blur(5px);
}

.nx-bookings-page .bk-modal:target {
    display: flex;
    animation: bkModalFade .2s ease both;
}

.nx-bookings-page .bk-modal-content {
    width: 100%;
    max-width: 550px;
    max-height: 90vh;
    padding: 25px;
    overflow-y: auto;
    border: 1px solid rgba(255,255,255,.65);
    border-radius: 19px;
    background: #fff;
    box-shadow: 0 22px 65px rgba(13,30,22,.24);
    animation: bkModalUp .25s ease both;
}

.nx-bookings-page .bk-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 17px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e9eee9;
}

.nx-bookings-page .bk-modal-header h2 {
    margin: 0;
    color: var(--bk-dark);
    font-family: var(--nx-font-heading, "Manrope", sans-serif);
    font-size: 18px;
    font-weight: 800;
}

.nx-bookings-page .bk-close {
    display: grid;
    width: 35px;
    height: 35px;
    flex: 0 0 35px;
    place-items: center;
    border-radius: 10px;
    background: #f0f3ef;
    color: #53615a;
    font-size: 23px;
    line-height: 1;
    text-decoration: none;
    transition: .2s ease;
}

.nx-bookings-page .bk-close:hover {
    transform: rotate(90deg);
    background: #e5ece5;
    color: var(--bk-dark);
}

.nx-bookings-page .bk-detail-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 18px;
    padding: 13px 0;
    border-bottom: 1px solid #edf1ec;
    font-size: 12px;
}

.nx-bookings-page .bk-detail-row > span:first-child {
    flex: 0 0 40%;
    color: #7b8980;
    font-weight: 600;
}

.nx-bookings-page .bk-detail-row strong {
    color: #304238;
    font-weight: 750;
    text-align: right;
    overflow-wrap: anywhere;
}

.nx-bookings-page .bk-detail-row .bk-badge {
    margin-left: auto;
}

.nx-bookings-page .bk-modal-actions {
    display: flex;
    justify-content: flex-end;
    flex-wrap: wrap;
    gap: 9px;
    margin-top: 21px;
}

.nx-bookings-page .bk-modal-btn {
    display: inline-flex;
    min-height: 39px;
    align-items: center;
    justify-content: center;
    padding: 9px 16px;
    border: 1px solid transparent;
    border-radius: 9px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 800;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.nx-bookings-page .bk-modal-btn:hover {
    transform: translateY(-1px);
}

.nx-bookings-page .bk-modal-btn-secondary {
    border-color: #dfe7df;
    background: #f4f7f3;
    color: #53665a;
}

.nx-bookings-page .bk-modal-btn-danger {
    border-color: #b9433b;
    background: #b9433b;
    color: #fff;
}

.nx-bookings-page .bk-modal-btn-danger:hover {
    background: #9f342e;
}

.nx-bookings-page .bk-cancel-note {
    margin: 0 0 15px;
    padding: 13px 14px;
    border: 1px solid #f0dfc7;
    border-radius: 10px;
    background: #fff8ed;
    color: #805c2d;
    font-size: 12px;
    line-height: 1.7;
}

.nx-bookings-page .bk-cancel-question {
    margin: 0;
    color: #53615a;
    font-size: 12px;
    line-height: 1.7;
}

/* Animations */
@keyframes bkFadeUp {
    from { opacity: 0; transform: translateY(9px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes bkModalFade {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes bkModalUp {
    from { opacity: 0; transform: translateY(10px) scale(.985); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

/* Responsive */
@media (max-width: 1050px) {
    .nx-bookings-page .bk-filters {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 650px) {
    .nx-bookings-page .bk-container {
        margin-top: 15px;
        padding: 0 13px;
    }

    .nx-bookings-page .bk-header {
        min-height: 130px;
        margin-bottom: 16px;
        padding: 21px;
        border-radius: 16px;
    }

    .nx-bookings-page .bk-header-icon {
        width: 46px;
        height: 46px;
        flex-basis: 46px;
        border-radius: 13px;
    }

    .nx-bookings-page .bk-header-icon svg {
        width: 23px;
        height: 23px;
    }

    .nx-bookings-page .bk-card {
        margin-bottom: 15px;
        padding: 17px;
        border-radius: 14px;
    }

    .nx-bookings-page .bk-filters {
        grid-template-columns: 1fr;
        gap: 13px;
    }

    .nx-bookings-page .bk-filter-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .nx-bookings-page .bk-reset {
        width: 100%;
    }

    .nx-bookings-page .bk-table-heading {
        align-items: flex-start;
    }

    .nx-bookings-page .bk-pagination {
        justify-content: center;
    }

    .nx-bookings-page .bk-modal {
        padding: 12px;
    }

    .nx-bookings-page .bk-modal-content {
        padding: 19px;
        border-radius: 15px;
    }

    .nx-bookings-page .bk-modal-actions {
        flex-direction: column-reverse;
    }

    .nx-bookings-page .bk-modal-btn {
        width: 100%;
    }
}

@media (prefers-reduced-motion: reduce) {
    .nx-bookings-page *,
    .nx-bookings-page *::before,
    .nx-bookings-page *::after {
        animation-duration: .01ms !important;
        transition-duration: .01ms !important;
    }
}
</style>
@endpush

@section('content')
<div class="nx-bookings-page">
    <div class="bk-container">

        {{-- Page Header --}}
        <header class="bk-header">
            <div class="bk-header-content">
                <div class="bk-eyebrow">MANAGEMENT / BOOKINGS</div>
                <h1>Manager Bookings</h1>
                <p>View and manage bookings across your workspaces.</p>
            </div>

            <div class="bk-header-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path d="M16 3v4M8 3v4M3 10h18"/>
                    <path d="m9 15 2 2 4-4"/>
                </svg>
            </div>
        </header>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="bk-alert bk-alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <path d="m9 11 3 3L22 4"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bk-alert bk-alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 8v4M12 16h.01"/>
                </svg>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Filters --}}
        <section class="bk-card">
            <div class="bk-section-heading">
                <div class="bk-section-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 7h16M7 12h10M10 17h4"/>
                        <circle cx="8" cy="7" r="1.5" fill="currentColor"/>
                        <circle cx="15" cy="12" r="1.5" fill="currentColor"/>
                    </svg>
                </div>
                <div>
                    <h2>Filter Bookings</h2>
                    <p>Refine your bookings by status, workspace or date</p>
                </div>
            </div>

            <form action="{{ route('manager.bookings.index') }}"
                  method="GET"
                  id="bookingFiltersForm">

                <div class="bk-filters">

                    <div class="bk-field">
                        <label for="status">Booking Status</label>
                        <select id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>

                    <div class="bk-field">
                        <label for="payment_status">Payment Status</label>
                        <select id="payment_status" name="payment_status">
                            <option value="">All Payments</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <div class="bk-field">
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

                    <div class="bk-field">
                        <label for="date">Booking Date</label>
                        <input type="date"
                               id="date"
                               name="date"
                               value="{{ request('date') }}">
                    </div>

                </div>

                <div class="bk-filter-footer">
                    <span class="bk-filter-hint">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4M12 8h.01"/>
                        </svg>
                        Filters are applied automatically when changed.
                    </span>

                    <a href="{{ route('manager.bookings.index') }}" class="bk-reset">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>
                        Reset Filters
                    </a>
                </div>
            </form>
        </section>

        {{-- Bookings Table --}}
        <section class="bk-card">
            <div class="bk-table-heading">
                <div class="bk-table-title">
                    <div class="bk-section-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="17" rx="2"/>
                            <path d="M7 2v4M17 2v4M3 9h18"/>
                            <path d="M7 13h3M7 17h6"/>
                        </svg>
                    </div>
                    <div>
                        <h2>All Bookings</h2>
                        <p>Review booking details and payment information</p>
                    </div>
                </div>

                <span class="bk-count">
                    {{ $bookings->total() }} Bookings
                </span>
            </div>

            <div class="bk-table-wrap">
                <table class="bk-table">
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
                            @php
                                $paymentStatus = $booking->payments
                                    ->sortByDesc('id')
                                    ->first()?->status ?? 'pending';
                            @endphp

                            <tr>
                                {{-- Member --}}
                                <td>
                                    <div class="bk-member">
                                        {{ $booking->user?->name ?? 'N/A' }}
                                    </div>
                                </td>

                                {{-- Workspace / Room --}}
                                <td>
                                    <div class="bk-workspace">
                                        {{ $booking->room?->workspace?->name ?? 'N/A' }}
                                    </div>
                                    <div class="bk-subtext">
                                        {{ $booking->room?->name ?? 'N/A' }}
                                    </div>
                                </td>

                                {{-- Time Slot --}}
                                <td>
                                    <div class="bk-date">
                                        {{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y') }}
                                    </div>
                                    <div class="bk-time">
                                        {{ \Carbon\Carbon::parse($booking->start_date_time)->format('h:i A') }}
                                        –
                                        {{ \Carbon\Carbon::parse($booking->end_date_time)->format('h:i A') }}
                                    </div>
                                </td>

                                {{-- Amount --}}
                                <td>
                                    <div class="bk-amount">
                                        {{ number_format($booking->total_price, 2) }} JOD
                                    </div>
                                </td>

                                {{-- Booking Status --}}
                                <td>
                                    <span class="bk-badge {{ $booking->status }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                                {{-- Payment Status --}}
                                <td>
                                    <span class="bk-badge {{ $paymentStatus }}">
                                        {{ ucfirst($paymentStatus) }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div class="bk-actions">
                                        <a href="#details-{{ $booking->id }}"
                                           class="bk-action bk-view"
                                           title="View Details"
                                           aria-label="View Details">
                                            <svg viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>

                                        @if(in_array($booking->status, ['pending', 'confirmed']))
                                            <a href="#cancel-{{ $booking->id }}"
                                               class="bk-action bk-cancel"
                                               title="Cancel Booking"
                                               aria-label="Cancel Booking">
                                                <svg viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round">
                                                    <path d="M18 6 6 18M6 6l12 12"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="bk-empty">
                                    <div class="bk-empty-icon">
                                        <svg viewBox="0 0 24 24" fill="none"
                                             stroke="currentColor" stroke-width="1.6"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="17" rx="2"/>
                                            <path d="M7 2v4M17 2v4M3 9h18"/>
                                            <path d="M9 14h6"/>
                                        </svg>
                                    </div>
                                    <strong>No bookings found</strong>
                                    <span>Try changing or resetting your filters.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="bk-pagination">
                <span class="bk-pagination-summary">
                    Showing {{ $bookings->firstItem() ?? 0 }}–{{ $bookings->lastItem() ?? 0 }}
                    of {{ $bookings->total() }} bookings
                </span>

                {{ $bookings->onEachSide(1)->links() }}
            </div>
        </section>

        {{-- Details and Cancellation Modals --}}
        @foreach($bookings as $booking)
            @php
                $latestPaymentStatus = $booking->payments
                    ->sortByDesc('id')
                    ->first()?->status ?? 'pending';
            @endphp

            {{-- Booking Details --}}
            <div id="details-{{ $booking->id }}" class="bk-modal">
                <div class="bk-modal-content">
                    <div class="bk-modal-header">
                        <h2>Booking Details</h2>
                        <a href="#" class="bk-close" aria-label="Close">&times;</a>
                    </div>

                    <div class="bk-detail-row">
                        <span>Member</span>
                        <strong>{{ $booking->user?->name ?? 'N/A' }}</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>Workspace</span>
                        <strong>{{ $booking->room?->workspace?->name ?? 'N/A' }}</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>Room</span>
                        <strong>{{ $booking->room?->name ?? 'N/A' }}</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>Start</span>
                        <strong>{{ \Carbon\Carbon::parse($booking->start_date_time)->format('d M Y, h:i A') }}</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>End</span>
                        <strong>{{ \Carbon\Carbon::parse($booking->end_date_time)->format('d M Y, h:i A') }}</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>Amount</span>
                        <strong>{{ number_format($booking->total_price, 2) }} JOD</strong>
                    </div>

                    <div class="bk-detail-row">
                        <span>Booking Status</span>
                        <span class="bk-badge {{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <div class="bk-detail-row">
                        <span>Payment Status</span>
                        <span class="bk-badge {{ $latestPaymentStatus }}">
                            {{ ucfirst($latestPaymentStatus) }}
                        </span>
                    </div>

                    @if($booking->cancellation_reason ?? null)
                        <div class="bk-detail-row">
                            <span>Cancellation Reason</span>
                            <strong>{{ $booking->cancellation_reason }}</strong>
                        </div>
                    @endif

                    <div class="bk-modal-actions">
                        <a href="#" class="bk-modal-btn bk-modal-btn-secondary">Close</a>
                    </div>
                </div>
            </div>

            {{-- Cancel Booking --}}
            @if(in_array($booking->status, ['pending', 'confirmed']))
                <div id="cancel-{{ $booking->id }}" class="bk-modal">
                    <div class="bk-modal-content">
                        <div class="bk-modal-header">
                            <h2>Cancel Booking #BK-{{ $booking->id }}</h2>
                            <a href="#" class="bk-close" aria-label="Close">&times;</a>
                        </div>

                        <div class="bk-cancel-note">
                            Please review this action carefully. Cancelling this booking may affect the member's reservation.
                        </div>

                        <form action="{{ route('manager.bookings.cancel', $booking->id) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')

                            <p class="bk-cancel-question">
                                Are you sure you want to cancel this booking?
                            </p>

                            <div class="bk-modal-actions">
                                <a href="#" class="bk-modal-btn bk-modal-btn-secondary">
                                    Keep Booking
                                </a>

                                <button type="submit"
                                        class="bk-modal-btn bk-modal-btn-danger">
                                    Confirm Cancellation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('bookingFiltersForm');

    if (!form) return;

    const filters = form.querySelectorAll('select, input[type="date"]');

    filters.forEach(function (filter) {
        filter.addEventListener('change', function () {
            form.requestSubmit();
        });
    });
});
</script>
@endsection
