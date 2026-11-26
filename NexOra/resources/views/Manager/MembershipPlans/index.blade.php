
@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title')
    Membership Plans
@endsection

@section('page-title')
    Membership Plans
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-16.css') }}">
@endpush

@push('page-overrides')
    <link rel="stylesheet" href="{{ asset('css/pages/manager-membershipplans-index.css') }}?v=20261126-ui1">
@endpush

@section('content')
<div class="nx-page-content nx-membership-page">
    <div class="mp-container">

        
{{-- Page Header --}}
<header class="mp-hero">

    <div class="mp-hero-content">
        <div class="mp-hero-eyebrow">
            <span class="mp-hero-eyebrow-dot"></span>
            WORKSPACE MEMBERSHIPS
        </div>

        <h1>Membership Plans</h1>

        <p>
            Create and manage membership offers for your workspace.
            Keep track of subscribers, pricing, and plan activity.
        </p>
    </div>

    <div class="mp-hero-icon" aria-hidden="true">
        <div class="mp-hero-orbit mp-hero-orbit-one"></div>
        <div class="mp-hero-orbit mp-hero-orbit-two"></div>

        <div class="mp-hero-icon-core">
            <span>♛</span>
        </div>
    </div>

</header>

<button type="button" class="mp-create-btn" id="openCreatePlan">
    + Create Plan
</button>
        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Membership Plans Table --}}
        <div class="mp-card">
            <div class="mp-table-wrap">
                <table class="mp-table">
                    <thead>
                        <tr>
                            <th>Plan</th>
                            <th>Workspace</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Subscribers</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td data-label="Plan">
                                    <strong>{{ $plan->plan_name }}</strong>
                                </td>

                                <td data-label="Workspace">
                                    {{ $plan->workspace?->name ?? 'N/A' }}
                                </td>

                                <td data-label="Price">
                                    {{ number_format($plan->price, 2) }}
                                    <span>JOD</span>
                                </td>

                                <td data-label="Duration">
                                    {{ ucfirst($plan->duration_type) }}
                                    ({{ $plan->duration_days }} days)
                                </td>

                                <td data-label="Subscribers">
                                    {{ $plan->memberships_count }}
                                </td>

                                <td data-label="Status">
                                    @if($plan->is_active)
                                        <span class="mp-status mp-status-active">
                                            Active
                                        </span>
                                    @else
                                        <span class="mp-status mp-status-inactive">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td data-label="Actions">
                                    <div class="mp-actions">

                                        {{-- View --}}
                                        <button
                                            type="button"
                                            class="mp-action-btn mp-view-btn nx-plan-view"
                                            data-plan-name="{{ $plan->plan_name }}"
                                            data-workspace="{{ $plan->workspace?->name ?? 'N/A' }}"
                                            data-price="{{ number_format((float) $plan->price, 2) }}"
                                            data-duration="{{ ucfirst($plan->duration_type) }} ({{ $plan->duration_days }} days)"
                                            data-subscribers="{{ $plan->memberships_count }}"
                                            data-status="{{ $plan->is_active ? 'Active' : 'Inactive' }}"
                                            data-description="{{ $plan->description ?: 'No description provided.' }}"
                                            data-edit-url="{{ route('manager.membership-plans.edit', $plan->id) }}"
                                            data-subscribers-url="{{ route('manager.membership-plans.subscribers', $plan->id) }}"
                                            aria-haspopup="dialog">
                                            View
                                        </button>

                                       <button
    type="button"
    class="mp-action-btn mp-edit-btn nx-plan-edit"
    data-id="{{ $plan->id }}"
    data-update-url="{{ route('manager.membership-plans.update', $plan->id) }}"
    data-workspace-id="{{ $plan->workspace_id }}"
    data-name="{{ $plan->plan_name }}"
    data-duration-type="{{ $plan->duration_type }}"
    data-duration-days="{{ $plan->duration_days }}"
    data-price="{{ $plan->price }}"
    data-description="{{ $plan->description }}"
    data-active="{{ $plan->is_active }}">
    Edit
</button>

                                        {{-- Activate / Deactivate --}}
                                        <form
                                            action="{{ route('manager.membership-plans.status', $plan->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="is_active"
                                                value="{{ $plan->is_active ? 0 : 1 }}">

                                            <button
                                                type="submit"
                                                class="mp-action-btn {{ $plan->is_active ? 'mp-deactivate-btn' : 'mp-activate-btn' }}">
                                                {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="mp-empty-state">
                                    No membership plans found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($plans->hasPages())
                <div class="bk-pagination">

                    <div class="bk-pagination-summary">
                        Showing
                        <strong>{{ $plans->firstItem() ?? 0 }}</strong>
                        to
                        <strong>{{ $plans->lastItem() ?? 0 }}</strong>
                        of
                        <strong>{{ $plans->total() }}</strong>
                        plans
                    </div>

                    <div class="bk-pager">

                        {{-- Previous --}}
                        @if($plans->onFirstPage())
                            <span class="bk-pager-link is-disabled">
                                Previous
                            </span>
                        @else
                            <a href="{{ $plans->withQueryString()->previousPageUrl() }}"
                               class="bk-pager-link">
                                Previous
                            </a>
                        @endif

                        @php
                            $currentPage = $plans->currentPage();
                            $lastPage = $plans->lastPage();

                            $windowStart = max(1, $currentPage - 2);
                            $windowEnd = min($lastPage, $currentPage + 2);
                        @endphp

                        {{-- First Page --}}
                        @if($windowStart > 1)
                            <a href="{{ $plans->withQueryString()->url(1) }}"
                               class="bk-pager-link">
                                1
                            </a>

                            @if($windowStart > 2)
                                <span class="bk-pager-link is-disabled">
                                    ...
                                </span>
                            @endif
                        @endif

                        {{-- Page Numbers --}}
                        @for($page = $windowStart; $page <= $windowEnd; $page++)
                            @if($page == $currentPage)
                                <span class="bk-pager-link is-active">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $plans->withQueryString()->url($page) }}"
                                   class="bk-pager-link">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        {{-- Last Page --}}
                        @if($windowEnd < $lastPage)
                            @if($windowEnd < $lastPage - 1)
                                <span class="bk-pager-link is-disabled">
                                    ...
                                </span>
                            @endif

                            <a href="{{ $plans->withQueryString()->url($lastPage) }}"
                               class="bk-pager-link">
                                {{ $lastPage }}
                            </a>
                        @endif

                        {{-- Next --}}
                        @if($plans->hasMorePages())
                            <a href="{{ $plans->withQueryString()->nextPageUrl() }}"
                               class="bk-pager-link">
                                Next
                            </a>
                        @else
                            <span class="bk-pager-link is-disabled">
                                Next
                            </span>
                        @endif

                    </div>
                </div>
            @endif
        </div>
{{-- Create / Edit Membership Plan Dialog --}}
<dialog class="mp-plan-dialog mp-form-dialog" id="membershipFormDialog">

    <div class="mp-plan-dialog__header">
        <div>
            <span class="mp-plan-dialog__eyebrow">WORKSPACE MEMBERSHIPS</span>
            <h2 id="membershipFormTitle">Create Membership Plan</h2>
        </div>

        <button type="button"
                class="mp-plan-dialog__close"
                id="closeMembershipForm"
                aria-label="Close">
            ×
        </button>
    </div>

    <form id="membershipPlanForm"
          action="{{ route('manager.membership-plans.store') }}"
          method="POST">

        @csrf

        <input type="hidden" name="_method" id="membershipFormMethod" value="POST">

        <div class="mp-form-body">

            <div class="mb-3">
                <label class="form-label">Workspace</label>

                <select name="workspace_id" id="formWorkspace" class="form-select" required>
                    <option value="">Select workspace</option>

                    @foreach($workspaces as $workspace)
                        <option value="{{ $workspace->id }}">
                            {{ $workspace->name }}
                        </option>
                    @endforeach
                </select>

                @error('workspace_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Plan Name</label>
                <input type="text"
                       name="plan_name"
                       id="formPlanName"
                       class="form-control"
                       placeholder="e.g. Premium Monthly"
                       required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Duration Type</label>

                    <select name="duration_type"
                            id="formDurationType"
                            class="form-select"
                            required>
                        <option value="">Select duration</option>
                        @foreach(['weekly', 'monthly', 'yearly', 'custom'] as $type)
                            <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Duration (Days)</label>

                    <input type="number"
                           name="duration_days"
                           id="formDurationDays"
                           class="form-control"
                           min="1"
                           required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Price</label>

                <input type="number"
                       name="price"
                       id="formPrice"
                       class="form-control"
                       min="0"
                       step="0.01"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>

                <textarea name="description"
                          id="formDescription"
                          class="form-control"
                          rows="4"
                          placeholder="Describe what this membership offers..."></textarea>
            </div>

            <div class="form-check mb-4">
                <input type="hidden" name="is_active" value="0">

                <input type="checkbox"
                       name="is_active"
                       value="1"
                       class="form-check-input"
                       id="formIsActive"
                       checked>

                <label class="form-check-label" for="formIsActive">
                    Activate this plan
                </label>
            </div>

        </div>

        <div class="mp-form-actions">
            <button type="submit" class="btn btn-primary" id="membershipSubmitButton">
                Create Plan
            </button>

            <button type="button"
                    class="btn btn-light"
                    id="cancelMembershipForm">
                Cancel
            </button>
        </div>

    </form>
</dialog>
        {{-- Membership Plan Details Dialog --}}
        <dialog class="mp-plan-dialog"
                id="membershipPlanDialog"
                aria-labelledby="mpPlanTitle">

            <div class="mp-plan-dialog__header">
                <div>
                    <span class="mp-plan-dialog__eyebrow">
                        MEMBERSHIP PLAN
                    </span>

                    <h2 id="mpPlanTitle">Plan details</h2>
                </div>

                <button
                    type="button"
                    class="mp-plan-dialog__close"
                    id="closePlanDialog"
                    aria-label="Close plan details">
                    ×
                </button>
            </div>

            <div class="mp-plan-dialog__summary">
                <div>
                    <span>Price</span>
                    <strong>
                        <span id="mpPlanPrice"></span> JOD
                    </strong>
                </div>

                <div>
                    <span>Duration</span>
                    <strong id="mpPlanDuration"></strong>
                </div>

                <div>
                    <span>Subscribers</span>
                    <strong id="mpPlanSubscribers"></strong>
                </div>
            </div>

            <dl class="mp-plan-dialog__details">
                <div>
                    <dt>Workspace</dt>
                    <dd id="mpPlanWorkspace"></dd>
                </div>

                <div>
                    <dt>Status</dt>
                    <dd id="mpPlanStatus"
                        class="mp-plan-dialog__status"></dd>
                </div>

                <div class="mp-plan-dialog__description">
                    <dt>About this plan</dt>
                    <dd id="mpPlanDescription"></dd>
                </div>
            </dl>

            <div class="mp-plan-dialog__actions">
                <a class="btn btn-outline-secondary"
                   id="mpPlanSubscribersLink"
                   href="#">
                    View subscribers
                </a>

                <a class="btn btn-primary"
                   id="mpPlanEditLink"
                   href="#">
                    Edit plan
                </a>
            </div>

        </dialog>

    </div>
</div>
@endsection

@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', () => {

    // Create / Edit Form
    const formDialog = document.getElementById('membershipFormDialog');
    const membershipForm = document.getElementById('membershipPlanForm');

    const createButton = document.getElementById('openCreatePlan');
    const closeFormButton = document.getElementById('closeMembershipForm');
    const cancelFormButton = document.getElementById('cancelMembershipForm');

    const formTitle = document.getElementById('membershipFormTitle');
    const formMethod = document.getElementById('membershipFormMethod');
    const submitButton = document.getElementById('membershipSubmitButton');

    const storeUrl = @json(route('manager.membership-plans.store'));

    // Open Create Form
    createButton.addEventListener('click', () => {
        membershipForm.reset();

        membershipForm.action = storeUrl;
        formMethod.value = 'POST';

        formTitle.textContent = 'Create Membership Plan';
        submitButton.textContent = 'Create Plan';

        document.getElementById('formIsActive').checked = true;

        formDialog.showModal();
    });

    // Open Edit Form
    document.querySelectorAll('.nx-plan-edit').forEach(button => {
        button.addEventListener('click', () => {

            membershipForm.reset();

            membershipForm.action = button.dataset.updateUrl;
            formMethod.value = 'PUT';

            document.getElementById('formWorkspace').value =
                button.dataset.workspaceId;

            document.getElementById('formPlanName').value =
                button.dataset.name;

            document.getElementById('formDurationType').value =
                button.dataset.durationType;

            document.getElementById('formDurationDays').value =
                button.dataset.durationDays;

            document.getElementById('formPrice').value =
                button.dataset.price;

            document.getElementById('formDescription').value =
                button.dataset.description || '';

            document.getElementById('formIsActive').checked =
                button.dataset.active === '1';

            formTitle.textContent = 'Edit Membership Plan';
            submitButton.textContent = 'Save Changes';

            formDialog.showModal();
        });
    });

    // Close Form
    closeFormButton.addEventListener('click', () => {
        formDialog.close();
    });

    cancelFormButton.addEventListener('click', () => {
        formDialog.close();
    });

    formDialog.addEventListener('click', event => {
        if (event.target === formDialog) {
            formDialog.close();
        }
    });


    // View Plan Details (Existing Functionality)
    const dialog = document.getElementById('membershipPlanDialog');
    const closeButton = document.getElementById('closePlanDialog');

    if (!dialog || !closeButton) return;

    const fields = {
        title: document.getElementById('mpPlanTitle'),
        price: document.getElementById('mpPlanPrice'),
        duration: document.getElementById('mpPlanDuration'),
        subscribers: document.getElementById('mpPlanSubscribers'),
        workspace: document.getElementById('mpPlanWorkspace'),
        status: document.getElementById('mpPlanStatus'),
        description: document.getElementById('mpPlanDescription'),
        editLink: document.getElementById('mpPlanEditLink'),
        subscribersLink: document.getElementById('mpPlanSubscribersLink')
    };

    document.querySelectorAll('.nx-plan-view').forEach(button => {
        button.addEventListener('click', () => {

            fields.title.textContent = button.dataset.planName || 'Plan details';
            fields.price.textContent = button.dataset.price || '0.00';
            fields.duration.textContent = button.dataset.duration || 'Not set';
            fields.subscribers.textContent = button.dataset.subscribers || '0';
            fields.workspace.textContent = button.dataset.workspace || 'N/A';

            fields.status.textContent = button.dataset.status || 'Unknown';

            fields.status.classList.toggle(
                'is-active',
                button.dataset.status === 'Active'
            );

            fields.status.classList.toggle(
                'is-inactive',
                button.dataset.status !== 'Active'
            );

            fields.description.textContent =
                button.dataset.description || 'No description provided.';

            fields.editLink.href = button.dataset.editUrl || '#';
            fields.subscribersLink.href = button.dataset.subscribersUrl || '#';

            dialog.showModal();
        });
    });

    closeButton.addEventListener('click', () => {
        dialog.close();
    });

    dialog.addEventListener('click', event => {
        if (event.target === dialog) {
            dialog.close();
        }
    });

});

</script>
@endpush
