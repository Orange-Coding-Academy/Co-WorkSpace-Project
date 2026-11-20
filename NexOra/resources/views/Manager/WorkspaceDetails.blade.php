@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Workspace details @endsection
@section('page-title') Workspace details @endsection

@section('content')
<div class="nx-page-content">
{{-- Workspace Details Page --}}

<style>
    .nx-page-content .ws-page{
        padding: 25px;
        color: #1D2924;
    }

    .nx-page-content .ws-header{
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .nx-page-content .room-gallery{
        width: 100%;
        height: 230px;
        position: relative;
        overflow: hidden;
        border-radius: 14px;
        background: #F5F7F3;
    }

    .nx-page-content .room-gallery-track{
        width: 100%;
        height: 100%;
    }

    .nx-page-content .room-gallery-image{
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    .nx-page-content .room-gallery-image.active{
        display: block;
    }

    .nx-page-content .gallery-arrow{
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.55);
        color: white;
        cursor: pointer;
        font-size: 17px;
        z-index: 2;
    }

    .nx-page-content .gallery-arrow.prev{
        left: 12px;
    }

    .nx-page-content .gallery-arrow.next{
        right: 12px;
    }

    .nx-page-content .gallery-arrow:hover{
        background: rgba(0, 0, 0, 0.8);
    }

    .nx-page-content .gallery-counter{
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .nx-page-content .no-room-image{
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #7F8B84;
    }

    .nx-page-content .ws-title{
        font-size: 27px;
        font-weight: 700;
    }

    .nx-page-content .ws-subtitle{
        color: #7F8B84;
        margin-top: 6px;
    }

    .nx-page-content .ws-btn{
        border: none;
        padding: 11px 18px;
        border-radius: 9px;
        cursor: pointer;
        font-weight: 600;
    }

    .nx-page-content .ws-btn-primary{
        background: #315C4D;
        color: white;
    }

    .nx-page-content .ws-btn-edit{
        background: #F5F7F3;
        color: #315C4D;
    }

    .nx-page-content .ws-btn-delete{
        background: #F5F7F3;
        color: #dc3545;
    }

    .nx-page-content .ws-overview{
        background: white;
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 30px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, .04);
    }

    .nx-page-content .ws-gallery{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 12px;
        margin-top: 18px;
    }

    .nx-page-content .ws-gallery img{
        width: 100%;
        height: 145px;
        object-fit: cover;
        border-radius: 10px;
    }

    .nx-page-content .ws-info{
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 18px;
    }

    .nx-page-content .ws-info-item{
        color: #53615A;
    }

    .nx-page-content .ws-info-item strong{
        display: block;
        color: #1D2924;
        margin-top: 5px;
    }

    .nx-page-content .ws-section-header{
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .nx-page-content .ws-room-grid{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
    }

    .nx-page-content .ws-room-card{
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0, 0, 0, .05);
    }

    .nx-page-content .ws-room-image{
        width: 100%;
        height: 190px;
        object-fit: cover;
    }

    .nx-page-content .ws-no-image{
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #F5F7F3;
        color: #7F8B84;
    }

    .nx-page-content .ws-room-content{
        padding: 18px;
    }

    .nx-page-content .ws-room-content h3{
        font-size: 19px;
        margin: 0 0 12px;
    }

    .nx-page-content .ws-room-meta{
        display: flex;
        justify-content: space-between;
        gap: 8px;
        margin: 9px 0;
        color: #53615A;
        font-size: 14px;
    }

    .nx-page-content .ws-room-actions{
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }

    .nx-page-content .ws-room-actions button{
        flex: 1;
    }

    .nx-page-content .ws-modal{
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(21, 42, 35, .65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 15px;
    }

    .nx-page-content .ws-modal.show{
        display: flex;
    }

    .nx-page-content .ws-modal-content{
        background: white;
        width: 100%;
        max-width: 620px;
        max-height: 90vh;
        overflow-y: auto;
        border-radius: 15px;
        padding: 25px;
    }

    .nx-page-content .ws-modal-header{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .nx-page-content .ws-modal-header h2{
        margin: 0;
        font-size: 22px;
    }

    .nx-page-content .ws-close{
        border: none;
        background: none;
        font-size: 27px;
        cursor: pointer;
    }

    .nx-page-content .ws-form-group{
        margin-bottom: 17px;
    }

    .nx-page-content .ws-form-group label{
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
    }

    .nx-page-content .ws-form-group input,
    .nx-page-content .ws-form-group select,
    .nx-page-content .ws-form-group textarea{
        width: 100%;
        padding: 11px;
        border: 1px solid #E2E8E2;
        border-radius: 8px;
        outline: none;
        box-sizing: border-box;
    }

    .nx-page-content .ws-form-group input:focus,
    .nx-page-content .ws-form-group select:focus,
    .nx-page-content .ws-form-group textarea:focus{
        border-color: #315C4D;
    }

    .nx-page-content .ws-modal-actions{
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
    }

    .nx-page-content .ws-existing-images{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 12px;
    }

    .nx-page-content .ws-existing-image{
        border: 1px solid #E9EEE9;
        border-radius: 9px;
        padding: 8px;
        font-size: 12px;
    }

    .nx-page-content .ws-existing-image img{
        width: 100%;
        height: 85px;
        object-fit: cover;
        border-radius: 6px;
    }

    .nx-page-content .ws-alert{
        padding: 13px 17px;
        border-radius: 9px;
        margin-bottom: 18px;
    }

    .nx-page-content .ws-success{
        background: #e8f8ef;
        color: #167343;
    }

    .nx-page-content .ws-error{
        background: #F5F7F3;
        color: #b42318;
    }

    .nx-page-content .ws-badge{
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        background: #e8f8ef;
        color: #167343;
        font-size: 12px;
    }

    .nx-page-content .ws-empty{
        background: white;
        border-radius: 14px;
        padding: 50px 20px;
        text-align: center;
        color: #7F8B84;
    }

    @media(max-width:600px) {
        .nx-page-content .ws-page{
            padding: 12px;
        }

        .nx-page-content .ws-title{
            font-size: 22px;
        }
    }

    .nx-page-content .ws-add-room{
        box-sizing: border-box;
        width: min(680px, calc(100% - 24px));
        max-width: none;
        max-height: min(90dvh, 820px);
        overflow: hidden;
        padding: 0;
        border: 1px solid #E2E8E2;
        border-radius: 22px;
        color: #152A23;
        background: #F5F7F3;
        box-shadow: 0 28px 80px #102c3a45;
    }

    .nx-page-content .ws-add-room::backdrop{
        background: #152c2bb3;
    }

    .nx-page-content .ws-add-room h2,
    .nx-page-content .ws-add-room p,
    .nx-page-content .ws-add-previews{
        margin: 0;
    }

    .nx-page-content .ws-add-header,
    .nx-page-content .ws-add-actions{
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px clamp(16px, 4vw, 28px);
    }

    .nx-page-content .ws-add-header{
        background: #152A23;
        color: #F5F7F3;
    }

    .nx-page-content .ws-add-header p{
        margin-top: 2px;
        color: #E2E8E2;
        font-size: .9rem;
    }

    .nx-page-content .ws-add-room[open],
    .nx-page-content .ws-add-room>form{
        display: flex;
        flex-direction: column;
    }

    .nx-page-content .ws-add-room>form{
        min-height: 0;
    }

    .nx-page-content .ws-add-header,
    .nx-page-content .ws-add-actions{
        flex: none;
    }

    .nx-page-content .ws-add-body{
        flex: 1 1 auto;
        min-height: 0;
        padding: 20px clamp(16px, 4vw, 28px);
        overflow-y: auto;
        overscroll-behavior-y: contain;
        scrollbar-width: thin;
        scrollbar-color: #7F8B84 #E9EEE9;
    }

    .nx-page-content .ws-add-fields{
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .nx-page-content .ws-add-fields .ws-form-group{
        margin-bottom: 0;
    }

    .nx-page-content .ws-add-fields textarea{
        resize: vertical;
    }

    .nx-page-content .ws-add-wide{
        grid-column: 1 / -1;
    }

    .nx-page-content .ws-add-muted,
    .nx-page-content .ws-add-fields small{
        color: #53615A;
        font-size: .85rem;
        font-weight: 400;
    }

    .nx-page-content .ws-add-photos{
        margin-top: 20px;
    }

    .nx-page-content .ws-add-photos>label{
        display: block;
        margin-bottom: 7px;
        font-weight: 650;
    }

    .nx-page-content .ws-add-dropzone{
        display: grid;
        gap: 8px;
        padding: 16px;
        border: 1px dashed #7F8B84;
        border-radius: 12px;
        background: #E9EEE9;
    }

    .nx-page-content .ws-add-dropzone.is-dragging{
        border-color: #276a59;
        background: #E2E8E2;
    }

    .nx-page-content .ws-add-dropzone p{
        color: #53615A;
        font-size: .84rem;
    }

    .nx-page-content .ws-add-dropzone input{
        width: 100%;
        font: inherit;
        font-size: .88rem;
    }

    .nx-page-content .ws-add-dropzone input::file-selector-button{
        cursor: pointer;
    }

    .nx-page-content .ws-add-previews{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        padding: 0;
        list-style: none;
    }

    .nx-page-content .ws-add-previews:not(:empty),
    .nx-page-content #C9D4CC-room-image-status:not(:empty){
        margin-top: 10px;
    }

    .nx-page-content .ws-add-preview{
        position: relative;
        min-width: 0;
    }

    .nx-page-content .ws-add-preview img{
        display: block;
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 9px;
        object-fit: cover;
    }

    .nx-page-content .ws-add-cover-label{
        display: inline-block;
        margin-top: 3px;
        color: #152A23;
        font-size: .8rem;
        font-weight: 700;
    }

    .nx-page-content .ws-add-close,
    .nx-page-content .ws-add-remove,
    .nx-page-content .ws-add-save,
    .nx-page-content .ws-add-cancel{
        border: 1px solid transparent;
        border-radius: 9px;
        font: inherit;
        font-weight: 650;
        cursor: pointer;
    }

    .nx-page-content .ws-add-close,
    .nx-page-content .ws-add-remove{
        width: 34px;
        height: 34px;
        background: #152A23;
        color: #fff;
    }

    .nx-page-content .ws-add-close{
        border-color: #ffffff55;
        font-size: 1.2rem;
    }

    .nx-page-content .ws-add-remove{
        position: absolute;
        top: 5px;
        right: 5px;
    }

    .nx-page-content .ws-add-close:hover,
    .nx-page-content .ws-add-remove:hover{
        background: #203D34;
    }

    .nx-page-content .ws-add-actions{
        justify-content: flex-end;
        border-top: 1px solid #E2E8E2;
    }

    .nx-page-content .ws-add-save,
    .nx-page-content .ws-add-cancel{
        min-height: 44px;
        padding: 8px 14px;
        color: #152A23;
    }

    .nx-page-content .ws-add-save{
        background: #C58A62;
    }

    .nx-page-content .ws-add-save:hover{
        background: #E6C9B3;
    }

    .nx-page-content .ws-add-cancel{
        border-color: #E2E8E2;
        background: #F5F7F3;
    }

    .nx-page-content .ws-add-cancel:hover{
        background: #E9EEE9;
    }

    .nx-page-content .ws-add-room :focus-visible{
        outline: 2px solid #29745e;
        outline-offset: 2px;
    }

    .nx-page-content .ws-add-header :focus-visible{
        outline-color: #E6C9B3;
    }

    .nx-page-content .ws-add-errors{
        margin-top: 12px;
        padding: 10px 14px;
        border-radius: 8px;
        background: #fff0ec;
        color: #922f21;
    }

    .nx-page-content .ws-add-errors ul{
        margin: 0 0 6px;
        padding-left: 18px;
    }

    @media (max-width: 480px) {
        .nx-page-content .ws-add-fields{
            grid-template-columns: 1fr;
        }
    }

    .nx-page-content .ws-page{
        --forest: #152A23;
        --ink: #152A23;
        --muted: #53615A;
        --paper: #F5F7F3;
        --line: #E2E8E2;
        min-height: 100vh;
        box-sizing: border-box;
        padding: clamp(16px, 4vw, 48px) max(clamp(16px, 4vw, 48px), calc((100% - 1220px) / 2));
        background: #f7f1e7;
        color: var(--ink);
        font-family: 'Inter', 'Noto Sans Arabic', system-ui, sans-serif;
    }

    .nx-page-content .ws-page *,
    .nx-page-content .ws-page *::before,
    .nx-page-content .ws-page *::after{
        box-sizing: border-box;
    }

    .nx-page-content .ws-page :focus-visible{
        outline: 3px solid #29745e;
        outline-offset: 3px;
    }

    .nx-page-content .ws-page .ws-overview{
        display: grid;
        gap: clamp(18px, 4vw, 36px);
        align-items: center;
        padding: clamp(20px, 4vw, 40px);
        margin-bottom: 30px;
        border-radius: 24px;
        background: var(--forest);
        color: var(--paper);
        box-shadow: 0 14px 35px #103b3420;
    }

    .nx-page-content .ws-overview:has(.ws-gallery){
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    }

    .nx-page-content .ws-overview h1{
        margin: 0;
        font-size: clamp(2.1rem, 5vw, 3.5rem);
        line-height: 1.06;
        letter-spacing: -.055em;
        overflow-wrap: anywhere;
    }

    .nx-page-content .ws-overview-copy>p{
        color: #E9EEE9;
        margin: 10px 0 0;
    }

    .nx-page-content .ws-page .ws-info{
        gap: 10px 20px;
    }

    .nx-page-content .ws-overview .ws-info-item{
        color: #C9D4CC;
        font-size: .8rem;
    }

    .nx-page-content .ws-overview .ws-info-item strong{
        color: var(--paper);
        font-size: .94rem;
    }

    .nx-page-content .ws-facilities{
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        padding: 0;
        margin: 18px 0 0;
        list-style: none;
    }

    .nx-page-content .ws-facilities li{
        padding: 4px 9px;
        border-radius: 999px;
        background: #ffffff22;
        color: var(--paper);
        font-size: .82rem;
    }

    .nx-page-content .ws-page .ws-gallery{
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin: 0;
    }

    .nx-page-content .ws-page .ws-gallery img{
        display: block;
        height: auto;
        aspect-ratio: 4 / 3;
    }

    .nx-page-content .ws-page .ws-gallery img:first-child{
        grid-column: 1 / -1;
        aspect-ratio: 16 / 9;
        border-radius: 16px;
    }

    .nx-page-content .ws-page .ws-section-header{
        margin-bottom: 16px;
    }

    .nx-page-content .ws-rooms-title{
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        font-size: 1.65rem;
        letter-spacing: -.04em;
    }

    .nx-page-content .ws-room-count{
        background: #E2E8E2;
        color: #1D2924;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: .82rem;
        letter-spacing: 0;
    }

    .nx-page-content .ws-page .ws-btn{
        min-height: 44px;
        font: inherit;
        font-size: .9rem;
        font-weight: 700;
    }

    .nx-page-content .ws-page .ws-btn-primary{
        background: #C58A62;
        color: #1D2924;
    }

    .nx-page-content .ws-page .ws-btn-primary:hover{
        background: #E6C9B3;
    }

    .nx-page-content .ws-page .ws-btn-edit{
        background: #E9EEE9;
        color: #203D34;
    }

    .nx-page-content .ws-page .ws-btn-edit:hover{
        background: #E2E8E2;
    }

    .nx-page-content .ws-page .ws-btn-delete{
        background: #fff0ec;
        color: #963529;
    }

    .nx-page-content .ws-page .ws-btn-delete:hover{
        background: #ffe2d9;
    }

    .nx-page-content .ws-page .ws-room-grid{
        grid-template-columns: repeat(auto-fill, minmax(min(100%, 265px), 1fr));
        gap: 16px;
    }

    .nx-page-content .ws-page .ws-room-card{
        display: flex;
        flex-direction: column;
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 18px;
        box-shadow: none;
    }

    .nx-page-content .ws-page .ws-room-card:hover{
        border-color: #C9D4CC;
        box-shadow: 0 8px 22px #103b3410;
    }

    .nx-page-content .ws-page .room-gallery,
    .nx-page-content .ws-page .ws-no-image{
        height: auto;
        aspect-ratio: 16 / 9;
        border-radius: 0;
        background: #E9EEE9;
    }

    .nx-page-content .ws-page .ws-no-image{
        color: var(--muted);
    }

    .nx-page-content .ws-page .gallery-arrow{
        width: 44px;
        height: 44px;
        background: #103b34d9;
    }

    .nx-page-content .ws-page .gallery-arrow:hover{
        background: #152A23;
    }

    .nx-page-content .ws-page .gallery-arrow:focus-visible{
        outline-color: #fff;
    }

    .nx-page-content .ws-page .ws-room-content{
        display: flex;
        flex-direction: column;
        flex: 1;
        padding: 16px;
    }

    .nx-page-content .ws-page .ws-room-content h3{
        margin-bottom: 8px;
        overflow-wrap: anywhere;
    }

    .nx-page-content .ws-page .ws-room-meta{
        margin: 0;
        padding: 8px 0;
        border-bottom: 1px solid var(--line);
        color: var(--muted);
    }

    .nx-page-content .ws-room-meta strong{
        color: var(--ink);
        text-align: right;
    }

    .nx-page-content .ws-page .ws-room-actions{
        margin-top: auto;
        padding-top: 14px;
        flex-wrap: wrap;
    }

    .nx-page-content .ws-page .ws-badge{
        align-self: start;
        background: #E2E8E2;
        color: #185940;
    }

    .nx-page-content .ws-page .ws-badge.is-inactive{
        background: #E9EEE9;
        color: #53615A;
    }

    @media (max-width: 700px) {
        .nx-page-content .ws-overview:has(.ws-gallery){
            grid-template-columns: minmax(0, 1fr);
        }
    }

    .nx-page-content .ws-facility-picker{
        min-width: 0;
        margin: 0;
        padding: 0;
        border: 0;
    }

    .nx-page-content .ws-facility-picker legend{
        margin-bottom: 8px;
        padding: 0;
        font-weight: 650;
    }

    .nx-page-content .ws-facility-options{
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .nx-page-content .ws-facility-option{
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 40px;
        padding: 6px 12px;
        border: 1px solid #E2E8E2;
        border-radius: 9px;
        background: #F5F7F3;
        font-size: .88rem;
        cursor: pointer;
    }

    .nx-page-content .ws-facility-option:hover{
        border-color: #7F8B84;
    }

    .nx-page-content .ws-facility-option:has(:checked){
        border-color: #53615A;
        background: #E9EEE9;
    }

    .nx-page-content .ws-facility-option input{
        width: 16px;
        height: 16px;
        margin: 0;
        accent-color: #276a59;
    }

    .nx-page-content .ws-add-photos .ws-photo-label{
        margin-bottom: 8px;
        font-weight: 650;
    }

    .nx-page-content .ws-add-dropzone{
        grid-template-columns: auto minmax(0, 1fr) auto;
        align-items: center;
        gap: 12px;
    }

    .nx-page-content .ws-upload-icon{
        display: grid;
        width: 42px;
        height: 42px;
        place-items: center;
        border-radius: 11px;
        background: #E2E8E2;
        color: #152A23;
        font-size: 1.25rem;
        font-weight: 750;
    }

    .nx-page-content .ws-upload-copy p{
        margin-top: 3px;
    }

    .nx-page-content .ws-file-picker{
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 8px 13px;
        border: 1px solid #203D34;
        border-radius: 9px;
        background: #152A23;
        color: #F5F7F3;
        font-size: .86rem;
        font-weight: 700;
        cursor: pointer;
    }

    .nx-page-content .ws-file-picker:hover{
        background: #203D34;
    }

    .nx-page-content .ws-file-picker:focus-within{
        outline: 2px solid #29745e;
        outline-offset: 3px;
    }

    .nx-page-content .ws-add-dropzone .ws-file-picker input{
        position: absolute;
        inset: 0;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .nx-page-content .ws-add-preview img{
        border: 1px solid #E2E8E2;
    }

    .nx-page-content .ws-add-previews .ws-add-remove{
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }

    .nx-page-content .ws-add-cover-label{
        padding: 2px 8px;
        border-radius: 999px;
        background: #E9EEE9;
    }

    @media (max-width: 480px) {
        .nx-page-content .ws-add-dropzone{
            grid-template-columns: auto minmax(0, 1fr);
        }

        .nx-page-content .ws-file-picker{
            grid-column: 1 / -1;
            justify-self: start;
        }
    }
    .nx-page-content .ws-back-link{
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 16px;
        padding: 8px 12px;
        border: 1px solid #C9D4CC;
        border-radius: 9px;
        background: #E9EEE9;
        color: #152A23;
        font-weight: 700;
        text-decoration: none;
    }

    .nx-page-content .ws-back-link:hover{ background: #E2E8E2; }
</style>

<div class="ws-page">

    {{-- Alerts --}}

    @if(session('success'))
    <div class="ws-alert ws-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="ws-alert ws-error">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="ws-alert ws-error">
        <strong>Please check the following errors:</strong>

        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <a class="ws-back-link" href="{{ route('manager.workspaces.create', ['view' => 'list']) }}">
        <span aria-hidden="true">&larr;</span> My workspaces
    </a>

    {{-- Workspace Overview --}}
    <section class="ws-overview" aria-labelledby="workspace-title">
        <div class="ws-overview-copy">
            <h1 id="workspace-title">{{ $workspace->name }}</h1>
            @if (filled($workspace->description))
            <p>{{ $workspace->description }}</p>
            @endif

            <div class="ws-info">
                <div class="ws-info-item">Location <strong>{{ $workspace->location }}</strong></div>
                <div class="ws-info-item">Approval <strong>{{ ucfirst($workspace->approval_status) }}</strong></div>
                <div class="ws-info-item">Commission <strong>{{ $workspace->commission_rate }}%</strong></div>
                <div class="ws-info-item">Availability <strong>{{ $workspace->is_active ? 'Active' : 'Inactive' }}</strong></div>
            </div>

            @if ($workspace->facilities)
            <ul class="ws-facilities" role="list" aria-label="Workspace facilities">
                @foreach ($workspace->facilities as $facility)
                <li>{{ $facility }}</li>
                @endforeach
            </ul>
            @endif
        </div>

        @if ($workspace->images->isNotEmpty())
        <div class="ws-gallery">
            @foreach ($workspace->images as $image)
            <img src="{{ asset(\Illuminate\Support\Str::startsWith($image->image_path, ['http://', 'https://', 'img/', '/img/', 'storage/', '/storage/']) ? $image->image_path : 'storage/' . $image->image_path) }}"
                alt="{{ $image->alt_text ?: $workspace->name . ' photo ' . $loop->iteration }}">
            @endforeach
        </div>
        @endif
    </section>

    <div class="ws-section-header">
        <h2 class="ws-rooms-title">Rooms <span class="ws-room-count">{{ $rooms->count() }}</span></h2>
        <button type="button" class="ws-btn ws-btn-primary" onclick="openAddModal()">
            <span aria-hidden="true">+</span> Add room
        </button>
    </div>

    {{-- Room Cards --}}

    <div class="ws-room-grid">

        @forelse($rooms as $room)

        <div class="ws-room-card"
             data-name="{{ $room->name }}"
             data-type="{{ $room->room_type }}"
             data-capacity="{{ $room->capacity }}"
             data-price="{{ $room->hourly_price }}"
             data-facilities="{{ implode(', ', $room->facilities ?? []) }}">

            @if ($room->images->isNotEmpty())
                <div class="room-gallery">
                    <div class="room-gallery-track">
                        @foreach ($room->images->sortBy([['is_primary', 'desc'], ['sort_order', 'asc']]) as $image)
                            <img class="room-gallery-image {{ $loop->first ? 'active' : '' }}"
                                 src="{{ asset(\Illuminate\Support\Str::startsWith($image->image_path, ['http://', 'https://', 'img/', '/img/', 'storage/', '/storage/']) ? $image->image_path : 'storage/' . $image->image_path) }}"
                                 alt="{{ $image->alt_text ?: $room->name }}"
                                 data-image-id="{{ $image->id }}"
                                 data-primary="{{ $image->is_primary ? '1' : '0' }}"
                                 loading="lazy">
                        @endforeach
                    </div>

                    @if ($room->images->count() > 1)
                        <button type="button" class="gallery-arrow prev" aria-label="Previous room photo"
                                onclick="changeRoomImage(this, -1)">&lsaquo;</button>
                        <button type="button" class="gallery-arrow next" aria-label="Next room photo"
                                onclick="changeRoomImage(this, 1)">&rsaquo;</button>
                        <div class="gallery-counter">
                            <span class="current-image">1</span> / {{ $room->images->count() }}
                        </div>
                    @endif
                </div>
            @else
                <div class="ws-no-image">No room photos</div>
            @endif

            <div class="ws-room-content">

                <span @class(['ws-badge', 'is-inactive'=> ! $room->is_active])>{{ $room->is_active ? 'Active' : 'Inactive' }}</span>

                <h3 style="margin-top:12px;">
                    {{ $room->name }}
                </h3>

                <div class="ws-room-meta">
                    <span>Room Type</span>
                    <strong>{{ $room->room_type }}</strong>
                </div>

                <div class="ws-room-meta">
                    <span>Capacity</span>
                    <strong>{{ $room->capacity }} People</strong>
                </div>

                <div class="ws-room-meta">
                    <span>Hourly Price</span>
                    <strong>${{ number_format((float)$room->hourly_price, 2) }}</strong>
                </div>

                @if($room->facilities)
                <div style="margin-top:12px;">
                    <small>
                        <strong>Facilities:</strong>
                        {{ implode(', ', $room->facilities) }}
                    </small>
                </div>
                @endif

                <div class="ws-room-actions">

                    <button
                        class="ws-btn ws-btn-edit"
                        type="button" data-update-url="{{ route('manager.rooms.update', $room->id) }}" onclick="openEditModal(this)">
                        Edit
                    </button>

                    <form
                        method="POST"
                        action="{{ route('manager.rooms.destroy', $room->id) }}"
                        onsubmit="return confirm('Are you sure you want to deactivate this room?')">
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="ws-btn ws-btn-delete">
                            Delete
                        </button>
                    </form>

                </div>

            </div>

        </div>

        @empty

        <div class="ws-empty" style="grid-column:1/-1;">
            <h3>No Rooms Yet</h3>

            <p>
                Start by adding the first room to this workspace.
            </p>

            <button
                class="ws-btn ws-btn-primary"
                onclick="openAddModal()">
                Add Your First Room
            </button>
        </div>

        @endforelse

    </div>

</div>


{{-- ADD ROOM MODAL --}}
<dialog class="ws-modal-content ws-add-room" id="addRoomModal" aria-labelledby="add-room-title">
    <header class="ws-add-header">
        <div>
            <h2 id="add-room-title">Add room</h2>
            <p>{{ $workspace->name }}</p>
        </div>
        <button type="button" class="ws-add-close" aria-label="Close add room" onclick="closeModal('addRoomModal')">&times;</button>
    </header>

    <form method="POST" action="{{ route('manager.rooms.store', $workspace->id) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_room_form" value="add">

        <div class="ws-add-body">
            <div class="ws-add-fields">
                <div class="ws-form-group">
                    <label for="add-room-name">Room name</label>
                    <input id="add-room-name" name="name" type="text" maxlength="255" placeholder="e.g. The Boardroom"
                        value="{{ old('_room_form') === 'add' ? old('name') : '' }}" required autofocus>
                </div>

                <div class="ws-form-group">
                    <label for="add-room-type">Room type</label>
                    <input id="add-room-type" name="room_type" type="text" maxlength="100" placeholder="e.g. Meeting room"
                        value="{{ old('_room_form') === 'add' ? old('room_type') : '' }}" required>
                </div>

                <div class="ws-form-group">
                    <label for="add-room-capacity">Capacity</label>
                    <input id="add-room-capacity" name="capacity" type="number" min="1" step="1" placeholder="e.g. 8"
                        value="{{ old('_room_form') === 'add' ? old('capacity') : '' }}" required>
                </div>

                <div class="ws-form-group">
                    <label for="add-room-price">Hourly price</label>
                    <input id="add-room-price" name="hourly_price" type="number" min="0" step="0.01" placeholder="0.00"
                        value="{{ old('_room_form') === 'add' ? old('hourly_price') : '' }}" required>
                </div>

                <fieldset class="ws-add-wide ws-facility-picker" id="add-room-facility-picker" hidden>
                    <legend>Facilities <span class="ws-add-muted">(optional)</span></legend>
                    <div class="ws-facility-options">
                        @foreach (['Wi-Fi', 'Projector', 'Whiteboard', 'Display screen', 'Air conditioning', 'Video conferencing', 'Power outlets', 'Wheelchair access'] as $facility)
                        <label class="ws-facility-option">
                            <input type="checkbox" value="{{ $facility }}">
                            <span>{{ $facility }}</span>
                        </label>
                        @endforeach
                    </div>
                </fieldset>

                <div class="ws-form-group ws-add-wide" id="add-room-facility-fallback">
                    <label for="add-room-facilities">Facilities <span class="ws-add-muted">(optional)</span></label>
                    <textarea id="add-room-facilities" name="facilities" rows="2" placeholder="Projector, Wi-Fi, whiteboard"
                        aria-describedby="add-room-facilities-hint">{{ old('_room_form') === 'add' ? old('facilities') : '' }}</textarea>
                    <small id="add-room-facilities-hint">Separate each facility with a comma.</small>
                </div>
            </div>

            <div class="ws-add-photos">
                <p class="ws-photo-label" id="add-room-photo-label">Room photos <span class="ws-add-muted">(optional)</span></p>
                <div class="ws-add-dropzone" id="add-room-dropzone">
                    <span class="ws-upload-icon" aria-hidden="true">&uarr;</span>
                    <div class="ws-upload-copy">
                        <strong>Drag and drop room photos</strong>
                        <p id="add-room-image-hint">JPG, PNG, or WebP up to 5 MB each. The first photo is the cover.</p>
                    </div>
                    <label class="ws-file-picker">
                        <span>Choose photos</span>
                        <input id="add-room-images" type="file" name="images[]" multiple
                            accept="image/jpeg,image/png,image/webp"
                            aria-describedby="add-room-photo-label add-room-image-hint">
                    </label>
                </div>
                <p id="add-room-image-status" class="ws-add-muted" aria-live="polite"></p>
                <ul id="add-room-previews" class="ws-add-previews" role="list" aria-label="Selected room photos"></ul>
            </div>

            @if (old('_room_form') === 'add' && $errors->any())
            <div class="ws-add-errors" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <p>Please reselect your photos before saving.</p>
            </div>
            @endif
        </div>

        <footer class="ws-add-actions">
            <button type="button" class="ws-add-cancel" onclick="closeModal('addRoomModal')">Cancel</button>
            <button type="submit" class="ws-add-save">Save room</button>
        </footer>
    </form>
</dialog>

{{-- EDIT ROOM MODAL --}}

<div class="ws-modal" id="editRoomModal">

    <div class="ws-modal-content">

        <div class="ws-modal-header">

            <h2>Edit Room</h2>

            <button
                type="button"
                class="ws-close"
                onclick="closeModal('editRoomModal')">
                &times;
            </button>

        </div>

        <form
            method="POST"
            id="editRoomForm"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="ws-form-group">
                <label>Room Name</label>

                <input
                    type="text"
                    name="name"
                    id="edit_name"
                    required>
            </div>

            <div class="ws-form-group">
                <label>Room Type</label>

                <input
                    type="text"
                    name="room_type"
                    id="edit_room_type"
                    required>
            </div>

            <div class="ws-form-group">
                <label>Capacity</label>

                <input
                    type="number"
                    name="capacity"
                    id="edit_capacity"
                    min="1"
                    required>
            </div>

            <div class="ws-form-group">
                <label>Hourly Price</label>

                <input
                    type="number"
                    name="hourly_price"
                    id="edit_hourly_price"
                    step="0.01"
                    min="0"
                    required>
            </div>

            <div class="ws-form-group">
                <label>Facilities</label>

                <textarea
                    name="facilities"
                    id="edit_facilities"
                    rows="3"></textarea>
            </div>

            <div class="ws-form-group">

                <label>Current Images</label>

                <div
                    class="ws-existing-images"
                    id="existingRoomImages"></div>

                <small>
                    Select the checkbox below an image to remove it.
                    Select a radio button to make an image primary.
                </small>

            </div>

            <div class="ws-form-group">

                <label>Add More Images</label>

                <input
                    type="file"
                    name="images[]"
                    accept="image/*"
                    multiple>

            </div>

            <div class="ws-modal-actions">

                <button
                    type="button"
                    class="ws-btn"
                    onclick="closeModal('editRoomModal')">
                    Cancel
                </button>

                <button
                    type="submit"
                    class="ws-btn ws-btn-primary">
                    Update Room
                </button>

            </div>

        </form>

    </div>

</div>


    @if(old('_room_form') === 'add' && $errors > any())
    openAddModal();
    @endif
</div>
@endsection

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('addRoomModal').showModal();
    }

    function openEditModal(button) {
        const card = button.closest('.ws-room-card');
        const room = card.dataset;

        document.getElementById('editRoomForm').action = button.dataset.updateUrl;
        document.getElementById('edit_name').value = room.name;
        document.getElementById('edit_room_type').value = room.type;
        document.getElementById('edit_capacity').value = room.capacity;
        document.getElementById('edit_hourly_price').value = room.price;
        document.getElementById('edit_facilities').value = room.facilities;

        const imagesContainer = document.getElementById('existingRoomImages');
        imagesContainer.replaceChildren();

        card.querySelectorAll('[data-image-id]').forEach(image => {
            const div = document.createElement('div');
            div.className = 'ws-existing-image';

            const img = document.createElement('img');
            img.src = image.src;
            img.alt = image.alt;

            const primaryLabel = document.createElement('label');
            const radio = document.createElement('input');

            radio.type = 'radio';
            radio.name = 'primary_image_id';
            radio.value = image.dataset.imageId;
            radio.checked = image.dataset.primary === '1';

            primaryLabel.append(radio, ' Primary');

            const removeLabel = document.createElement('label');
            const checkbox = document.createElement('input');

            checkbox.type = 'checkbox';
            checkbox.name = 'remove_images[]';
            checkbox.value = image.dataset.imageId;

            removeLabel.append(checkbox, ' Remove');

            div.append(img, primaryLabel, removeLabel);
            imagesContainer.appendChild(div);
        });

        document.getElementById('editRoomModal').classList.add('show');
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal instanceof HTMLDialogElement) {
            modal.close();
        } else {
            modal.classList.remove('show');
        }
    }

    function changeRoomImage(button, direction) {
        const gallery = button.closest('.room-gallery');
        const images = gallery.querySelectorAll('.room-gallery-image');

        let currentIndex = Array.from(images).findIndex(
            image => image.classList.contains('active')
        );

        if (currentIndex === -1 || images.length < 2) return;

        images[currentIndex].classList.remove('active');

        currentIndex = (currentIndex + direction + images.length) % images.length;

        images[currentIndex].classList.add('active');

        const counter = gallery.querySelector('.current-image');

        if (counter) {
            counter.textContent = currentIndex + 1;
        }
    }

    document.querySelectorAll('.ws-modal').forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        });
    });

    const facilityPicker = document.getElementById('add-room-facility-picker');
    const facilityText = document.getElementById('add-room-facilities');
    const facilityOptions = Array.from(facilityPicker.querySelectorAll('input[type="checkbox"]'));
    const savedFacilities = facilityText.value.split(',').map(value => value.trim()).filter(Boolean);
    const knownFacilities = new Set(facilityOptions.map(option => option.value));
    const otherFacilities = savedFacilities.filter(value => !knownFacilities.has(value));

    for (const option of facilityOptions) {
        option.checked = savedFacilities.includes(option.value);
    }

    facilityPicker.addEventListener('change', () => {
        const selected = facilityOptions.filter(option => option.checked).map(option => option.value);
        facilityText.value = [...selected, ...otherFacilities].join(', ');
    });
    document.getElementById('add-room-facility-fallback').hidden = true;
    facilityPicker.hidden = false;

    const roomPhotoInput = document.getElementById('add-room-images');
    const roomPhotoDropzone = document.getElementById('add-room-dropzone');
    const roomPhotoPreviews = document.getElementById('add-room-previews');
    const roomPhotoStatus = document.getElementById('add-room-image-status');

    let roomPhotos = [];
    let roomPhotoUrls = [];

    function renderRoomPhotos(message = '') {
        roomPhotoUrls.forEach(url => URL.revokeObjectURL(url));
        roomPhotoUrls = [];
        roomPhotoPreviews.replaceChildren();

        roomPhotos.forEach((file, index) => {
            const item = document.createElement('li');
            item.className = 'ws-add-preview';

            const image = document.createElement('img');
            image.src = URL.createObjectURL(file);
            image.alt = file.name;
            roomPhotoUrls.push(image.src);

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'ws-add-remove';
            remove.textContent = '×';
            remove.setAttribute('aria-label', 'Remove ' + file.name);
            remove.addEventListener('click', () => {
                roomPhotos.splice(index, 1);
                syncRoomPhotos();
                roomPhotoPreviews.querySelectorAll('button')[index]?.focus();
                if (document.activeElement === document.body) roomPhotoInput.focus();
            });

            item.append(image, remove);

            if (index === 0) {
                const label = document.createElement('span');
                label.className = 'ws-add-cover-label';
                label.textContent = 'Cover photo';
                item.append(label);
            }

            roomPhotoPreviews.append(item);
        });

        const count = roomPhotos.length;
        roomPhotoStatus.textContent = message || (count ? count + (count === 1 ? ' photo selected' : ' photos selected') : '');
    }

    function syncRoomPhotos(message) {
        const transfer = new DataTransfer();
        roomPhotos.forEach(file => transfer.items.add(file));
        roomPhotoInput.files = transfer.files;
        renderRoomPhotos(message);
    }

    function addRoomPhotos(files) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        let rejected = 0;

        for (const file of files) {
            if (!allowedTypes.includes(file.type) || file.size > 5 * 1024 * 1024) {
                rejected++;
            } else if (!roomPhotos.some(photo => photo.name === file.name && photo.size === file.size && photo.lastModified === file.lastModified)) {
                roomPhotos.push(file);
            }
        }

        syncRoomPhotos(rejected ? 'Some files were skipped. Use JPG, PNG, or WebP up to 5 MB each.' : '');
    }

    if (typeof DataTransfer === 'function') {
        roomPhotoInput.addEventListener('change', () => addRoomPhotos(roomPhotoInput.files));
        roomPhotoDropzone.addEventListener('dragover', event => {
            event.preventDefault();
            roomPhotoDropzone.classList.add('is-dragging');
        });
        roomPhotoDropzone.addEventListener('dragleave', event => {
            if (!roomPhotoDropzone.contains(event.relatedTarget)) {
                roomPhotoDropzone.classList.remove('is-dragging');
            }
        });
        roomPhotoDropzone.addEventListener('drop', event => {
            event.preventDefault();
            roomPhotoDropzone.classList.remove('is-dragging');
            addRoomPhotos(event.dataTransfer.files);
        });
    }
</script>
@endpush
