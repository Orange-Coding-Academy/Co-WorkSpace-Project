
@extends('layouts.app')

@section('content')

<style>
    .register-page {
        background: #f5f7fb;
        min-height: 100vh;
        padding: 55px 15px;
    }

    .register-container {
        max-width: 1000px;
        margin: auto;
    }

    .register-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .register-header h2 {
        font-weight: 800;
        color: #172554;
        margin-bottom: 10px;
    }

    .register-header p {
        color: #64748b;
    }

    .register-card {
        background: #fff;
        border-radius: 18px;
        padding: 35px;
        box-shadow: 0 8px 35px rgba(15, 23, 42, .07);
        margin-bottom: 25px;
    }

    .section-title {
        color: #172554;
        font-weight: 700;
        font-size: 20px;
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
    }

    .form-control,
    .form-select {
        border-radius: 9px;
        padding: 11px 13px;
        border-color: #dbe2ea;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
    }

    .account-option {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        cursor: pointer;
        transition: .2s;
        height: 100%;
    }

    .account-option:hover {
        border-color: #818cf8;
    }

    .account-option.active {
        border-color: #4f46e5;
        background: #eef2ff;
    }

    .account-option input {
        margin-right: 8px;
    }

    .account-option h6 {
        font-weight: 700;
        color: #172554;
        margin-top: 10px;
    }

    .account-option p {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 0;
    }

    .facility-item {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 9px 13px;
        border-radius: 8px;
        margin: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .facility-item input {
        accent-color: #4f46e5;
    }

    .room-card {
        border: 1px solid #e2e8f0;
        border-radius: 13px;
        padding: 22px;
        margin-bottom: 20px;
        background: #fcfdff;
    }

    .room-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .room-card-header h5 {
        color: #172554;
        font-weight: 700;
        margin: 0;
    }

    .btn-primary-custom {
        background: #4f46e5;
        border: none;
        color: white;
        padding: 12px 25px;
        border-radius: 9px;
        font-weight: 600;
    }

    .btn-primary-custom:hover {
        background: #4338ca;
        color: white;
    }

    .btn-add-room {
        border: 1px dashed #6366f1;
        background: #eef2ff;
        color: #4338ca;
        padding: 12px 18px;
        border-radius: 9px;
        font-weight: 600;
        width: 100%;
    }

    .btn-add-room:hover {
        background: #e0e7ff;
    }

    .btn-remove-room {
        background: #fee2e2;
        color: #dc2626;
        border: none;
        padding: 7px 12px;
        border-radius: 7px;
        font-size: 13px;
    }

    .manager-section {
        display: none;
    }

    .required-star {
        color: #dc2626;
    }

    .section-divider {
        border-top: 1px solid #e2e8f0;
        margin: 28px 0;
    }

    .help-text {
        color: #94a3b8;
        font-size: 12px;
        margin-top: 5px;
    }

    @media(max-width: 768px) {
        .register-card {
            padding: 22px;
        }
    }
</style>

<div class="register-page">

    <div class="register-container">

        <div class="register-header">
            <h2>Create Your NexOra Account</h2>
            <p>Join NexOra and discover a better way to work.</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please check the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('register') }}"
              enctype="multipart/form-data"
              id="registerForm">

            @csrf

            <!-- ACCOUNT TYPE -->
            <div class="register-card">

                <h4 class="section-title">Choose Account Type</h4>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="account-option d-block
                            {{ old('account_type', 'member') === 'member' ? 'active' : '' }}"
                            id="memberOption">

                            <input type="radio"
                                   name="account_type"
                                   value="member"
                                   {{ old('account_type', 'member') === 'member' ? 'checked' : '' }}
                                   onchange="toggleAccountFields()">

                            <h6>Member Account</h6>

                            <p>
                                Explore workspaces, book rooms and enjoy
                                coworking facilities.
                            </p>

                        </label>
                    </div>

                    <div class="col-md-6">
                        <label class="account-option d-block
                            {{ old('account_type') === 'spaceManager' ? 'active' : '' }}"
                            id="managerOption">

                            <input type="radio"
                                   name="account_type"
                                   value="spaceManager"
                                   {{ old('account_type') === 'spaceManager' ? 'checked' : '' }}
                                   onchange="toggleAccountFields()">

                            <h6>Workspace Manager</h6>

                            <p>
                                Register your workspace, add rooms and
                                manage your coworking business.
                            </p>

                        </label>
                    </div>

                </div>

            </div>

            <!-- PERSONAL INFORMATION -->
            <div class="register-card">

                <h4 class="section-title">Personal Information</h4>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">
                            Full Name <span class="required-star">*</span>
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Enter your full name"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Email Address <span class="required-star">*</span>
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}"
                               placeholder="example@email.com"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Password <span class="required-star">*</span>
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Minimum 8 characters"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Confirm Password <span class="required-star">*</span>
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Confirm your password"
                               required>
                    </div>

                </div>

            </div>

            <!-- MANAGER INFORMATION -->
            <div id="managerFields" class="manager-section">

                <!-- WORKSPACE DETAILS -->
                <div class="register-card">

                    <h4 class="section-title">Workspace Information</h4>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">
                                Workspace Name <span class="required-star">*</span>
                            </label>

                            <input type="text"
                                   name="workspace[name]"
                                   class="form-control manager-input"
                                   value="{{ old('workspace.name') }}"
                                   placeholder="Enter workspace name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Location <span class="required-star">*</span>
                            </label>

                            <input type="text"
                                   name="workspace[location]"
                                   class="form-control manager-input"
                                   value="{{ old('workspace.location') }}"
                                   placeholder="City, area or full address">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Description</label>

                            <textarea name="workspace[description]"
                                      class="form-control manager-input"
                                      rows="4"
                                      placeholder="Describe your workspace...">{{ old('workspace.description') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">
                                Workspace Images
                            </label>

                            <input type="file"
                                   name="workspace_images[]"
                                   class="form-control manager-input"
                                   accept="image/jpeg,image/png,image/webp"
                                   multiple>

                            <div class="help-text">
                                You can upload multiple images. Maximum 5 MB per image.
                            </div>
                        </div>

                    </div>

                    <div class="section-divider"></div>

                    <h5 class="section-title">Workspace Facilities</h5>

                    <p class="text-muted small">
                        Select the facilities available in your workspace.
                    </p>

                    <div class="d-flex flex-wrap">

                        @php
                            $workspaceFacilities = [
                                'WiFi',
                                'Air Conditioning',
                                'Parking',
                                'Coffee',
                                'Reception',
                                'Printer',
                                'Kitchen',
                                'Restrooms',
                                '24/7 Access',
                                'Security',
                                'Lounge Area',
                                'Outdoor Area',
                                'Elevator',
                                'CCTV'
                            ];

                            $selectedWorkspaceFacilities = old('workspace.facilities', []);
                        @endphp

                        @foreach($workspaceFacilities as $facility)

                            <label class="facility-item">
                                <input type="checkbox"
                                       name="workspace[facilities][]"
                                       value="{{ $facility }}"
                                       class="manager-input"
                                       {{ in_array($facility, $selectedWorkspaceFacilities) ? 'checked' : '' }}>

                                {{ $facility }}
                            </label>

                        @endforeach

                    </div>

                    <div class="mt-3">
                        <label class="form-label">
                            Other Facilities
                        </label>

                        <input type="text"
                               name="workspace[custom_facilities]"
                               class="form-control manager-input"
                               value="{{ old('workspace.custom_facilities') }}"
                               placeholder="e.g. Swimming Pool, Gaming Area, Rooftop">

                        <div class="help-text">
                            Add facilities that are not listed above.
                            Separate multiple facilities with commas.
                        </div>
                    </div>

                </div>

                <!-- ROOMS -->
                <div class="register-card">

                    <h4 class="section-title">Workspace Rooms</h4>

                    <p class="text-muted small mb-4">
                        Add the rooms available in your workspace.
                        You can add multiple rooms.
                    </p>

                    <div id="roomsContainer"></div>

                    <button type="button"
                            class="btn-add-room"
                            onclick="addRoom()">

                        + Add Another Room

                    </button>

                </div>

                <!-- APPLICATION NOTICE -->
                <div class="alert alert-info">
                    <strong>Application Review</strong>

                    <p class="mb-0 mt-1">
                        Your workspace application will be submitted
                        to the NexOra administration team.
                        You will be able to log in after your workspace
                        has been approved.
                    </p>
                </div>

            </div>

            <!-- SUBMIT -->
            <div class="register-card text-center">

                <button type="submit"
                        class="btn-primary-custom w-100">

                    Create Account

                </button>

                <p class="mt-3 mb-0 text-muted">
                    Already have an account?

                    <a href="{{ route('login') }}"
                       class="text-decoration-none fw-bold">
                        Login
                    </a>
                </p>

            </div>

        </form>

    </div>

</div>

<script>

    let roomIndex = 0;

    const roomFacilities = [
        'WiFi',
        'Air Conditioning',
        'Projector',
        'Smart Board',
        'Whiteboard',
        'TV Screen',
        'Video Conference',
        'Power Outlets',
        'Comfortable Chairs',
        'Meeting Table',
        'Sound System',
        'Natural Light'
    ];

    function toggleAccountFields() {

        const accountType = document.querySelector(
            'input[name="account_type"]:checked'
        ).value;

        const managerFields = document.getElementById('managerFields');

        const managerInputs = document.querySelectorAll('.manager-input');

        const isManager = accountType === 'spaceManager';

        managerFields.style.display = isManager ? 'block' : 'none';

        managerInputs.forEach(input => {
            input.disabled = !isManager;
        });

        document.getElementById('memberOption')
            .classList.toggle('active', !isManager);

        document.getElementById('managerOption')
            .classList.toggle('active', isManager);

        // Required fields for workspace managers
        document.querySelectorAll(
            '[data-manager-required="true"]'
        ).forEach(input => {
            input.required = isManager;
        });
    }

    function createRoom(index) {

        const card = document.createElement('div');

        card.className = 'room-card';

        card.innerHTML = `

            <div class="room-card-header">

                <h5>Room ${index + 1}</h5>

                <button type="button"
                        class="btn-remove-room"
                        onclick="removeRoom(this)">

                    Remove Room

                </button>

            </div>

            <div class="row g-3">

                <div class="col-md-6">

                    <label class="form-label">
                        Room Name <span class="required-star">*</span>
                    </label>

                    <input type="text"
                           name="rooms[${index}][name]"
                           class="form-control manager-input"
                           placeholder="e.g. Meeting Room A"
                           data-manager-required="true">

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        Room Type <span class="required-star">*</span>
                    </label>

                    <select name="rooms[${index}][room_type]"
                            class="form-select manager-input"
                            data-manager-required="true">

                        <option value="">Select Room Type</option>
                        <option value="Private Office">Private Office</option>
                        <option value="Meeting Room">Meeting Room</option>
                        <option value="Conference Room">Conference Room</option>
                        <option value="Open Desk">Open Desk</option>
                        <option value="Dedicated Desk">Dedicated Desk</option>
                        <option value="Training Room">Training Room</option>
                        <option value="Event Space">Event Space</option>
                        <option value="Other">Other</option>

                    </select>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        Capacity <span class="required-star">*</span>
                    </label>

                    <input type="number"
                           name="rooms[${index}][capacity]"
                           class="form-control manager-input"
                           min="1"
                           placeholder="Number of people"
                           data-manager-required="true">

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        Hourly Price <span class="required-star">*</span>
                    </label>

                    <input type="number"
                           name="rooms[${index}][hourly_price]"
                           class="form-control manager-input"
                           min="0"
                           step="0.01"
                           placeholder="e.g. 15.00"
                           data-manager-required="true">

                </div>

                <div class="col-12">

                    <label class="form-label">
                        Room Images
                    </label>

                    <input type="file"
                           name="rooms[${index}][images][]"
                           class="form-control manager-input"
                           accept="image/jpeg,image/png,image/webp"
                           multiple>

                    <div class="help-text">
                        Upload one or more images for this room.
                    </div>

                </div>

                <div class="col-12">

                    <hr>

                    <label class="form-label">
                        Room Facilities
                    </label>

                    <div class="d-flex flex-wrap">

                        ${roomFacilities.map(facility => `

                            <label class="facility-item">

                                <input type="checkbox"
                                       name="rooms[${index}][facilities][]"
                                       value="${facility}"
                                       class="manager-input">

                                ${facility}

                            </label>

                        `).join('')}

                    </div>

                </div>

                <div class="col-12">

                    <label class="form-label">
                        Other Room Facilities
                    </label>

                    <input type="text"
                           name="rooms[${index}][custom_facilities]"
                           class="form-control manager-input"
                           placeholder="e.g. Coffee Machine, Extra Monitor">

                    <div class="help-text">
                        Add any additional facilities, separated by commas.
                    </div>

                </div>

            </div>
        `;

        return card;
    }

    function addRoom() {

        const container = document.getElementById('roomsContainer');

        const room = createRoom(roomIndex);

        container.appendChild(room);

        roomIndex++;

        toggleAccountFields();
    }

    function removeRoom(button) {

        const rooms = document.querySelectorAll('.room-card');

        if (rooms.length <= 1) {
            alert('You must have at least one room.');
            return;
        }

        button.closest('.room-card').remove();

        updateRoomTitles();
    }

    function updateRoomTitles() {

        document.querySelectorAll('.room-card').forEach((room, index) => {

            room.querySelector('h5').textContent = `Room ${index + 1}`;

        });

    }

    document.addEventListener('DOMContentLoaded', function () {

        // Start with one room
        addRoom();

        // Set correct account fields
        toggleAccountFields();

    });

</script>

@endsection
