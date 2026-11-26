@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Create Membership Plan @endsection
@section('page-title') Create Membership Plan @endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-04.css') }}">
@endpush

@section('content')
<div class="nx-page-content bg-light">
<div class="container py-4">

    <div class="mb-4">
        <a href="{{ route('manager.membership-plans.index') }}" class="text-decoration-none">
            &larr; Back to Membership Plans
        </a>

        <h3 class="fw-bold mt-3">Create Membership Plan</h3>
        <p class="text-muted">Create a new membership offer for your workspace.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('manager.membership-plans.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Workspace</label>

                    <select name="workspace_id" class="form-select" required>
                        <option value="">Select workspace</option>

                        @foreach($workspaces as $workspace)
                            <option value="{{ $workspace->id }}"
                                {{ old('workspace_id') == $workspace->id ? 'selected' : '' }}>
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

                    <input type="text" name="plan_name" class="form-control"
                           value="{{ old('plan_name') }}"
                           placeholder="e.g. Premium Monthly" required>

                    @error('plan_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Duration Type</label>

                        <select name="duration_type" class="form-select" required>
                            <option value="">Select duration</option>

                            @foreach(['weekly', 'monthly', 'yearly', 'custom'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('duration_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>

                        @error('duration_type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Duration (Days)</label>

                        <input type="number" name="duration_days" class="form-control"
                               min="1" value="{{ old('duration_days') }}" required>

                        @error('duration_days')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Price</label>

                    <input type="number" name="price" class="form-control"
                           min="0" step="0.01" value="{{ old('price') }}" required>

                    @error('price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>

                    <textarea name="description" class="form-control" rows="4"
                              placeholder="Describe what this membership offers...">{{ old('description') }}</textarea>

                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-check mb-4">
                    <input type="hidden" name="is_active" value="0">

                    <input type="checkbox" name="is_active" value="1"
                           class="form-check-input" id="is_active"
                           {{ old('is_active', '1') ? 'checked' : '' }}>

                    <label class="form-check-label" for="is_active">
                        Activate this plan immediately
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Create Plan</button>

                <a href="{{ route('manager.membership-plans.index') }}"
                   class="btn btn-light">Cancel</a>

            </form>
        </div>
    </div>

</div>
</div>
@endsection
