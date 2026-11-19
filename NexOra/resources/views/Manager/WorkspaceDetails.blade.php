
{{-- Workspace Details Page --}}

<style>
    .ws-page {
        padding: 25px;
        color: #253047;
    }

    .ws-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }
    .room-gallery {
    width: 100%;
    height: 230px;
    position: relative;
    overflow: hidden;
    border-radius: 14px;
    background: #f1f1f1;
}

.room-gallery-track {
    width: 100%;
    height: 100%;
}

.room-gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: none;
}

.room-gallery-image.active {
    display: block;
}

.gallery-arrow {
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

.gallery-arrow.prev {
    left: 12px;
}

.gallery-arrow.next {
    right: 12px;
}

.gallery-arrow:hover {
    background: rgba(0, 0, 0, 0.8);
}

.gallery-counter {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
}

.no-room-image {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #888;
}

    .ws-title {
        font-size: 27px;
        font-weight: 700;
    }

    .ws-subtitle {
        color: #8490a3;
        margin-top: 6px;
    }

    .ws-btn {
        border: none;
        padding: 11px 18px;
        border-radius: 9px;
        cursor: pointer;
        font-weight: 600;
    }

    .ws-btn-primary {
        background: #5146e5;
        color: white;
    }

    .ws-btn-edit {
        background: #eef0ff;
        color: #5146e5;
    }

    .ws-btn-delete {
        background: #fff0f0;
        color: #dc3545;
    }

    .ws-overview {
        background: white;
        border-radius: 14px;
        padding: 22px;
        margin-bottom: 30px;
        box-shadow: 0 3px 15px rgba(0,0,0,.04);
    }

    .ws-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 12px;
        margin-top: 18px;
    }

    .ws-gallery img {
        width: 100%;
        height: 145px;
        object-fit: cover;
        border-radius: 10px;
    }

    .ws-info {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 18px;
    }

    .ws-info-item {
        color: #64748b;
    }

    .ws-info-item strong {
        display: block;
        color: #253047;
        margin-top: 5px;
    }

    .ws-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .ws-room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
    }

    .ws-room-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0,0,0,.05);
    }

    .ws-room-image {
        width: 100%;
        height: 190px;
        object-fit: cover;
    }

    .ws-no-image {
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f3f8;
        color: #8490a3;
    }

    .ws-room-content {
        padding: 18px;
    }

    .ws-room-content h3 {
        font-size: 19px;
        margin: 0 0 12px;
    }

    .ws-room-meta {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        margin: 9px 0;
        color: #64748b;
        font-size: 14px;
    }

    .ws-room-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
    }

    .ws-room-actions button {
        flex: 1;
    }

    .ws-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .65);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 15px;
    }

    .ws-modal.show {
        display: flex;
    }

    .ws-modal-content {
        background: white;
        width: 100%;
        max-width: 620px;
        max-height: 90vh;
        overflow-y: auto;
        border-radius: 15px;
        padding: 25px;
    }

    .ws-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .ws-modal-header h2 {
        margin: 0;
        font-size: 22px;
    }

    .ws-close {
        border: none;
        background: none;
        font-size: 27px;
        cursor: pointer;
    }

    .ws-form-group {
        margin-bottom: 17px;
    }

    .ws-form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
    }

    .ws-form-group input,
    .ws-form-group select,
    .ws-form-group textarea {
        width: 100%;
        padding: 11px;
        border: 1px solid #dce1eb;
        border-radius: 8px;
        outline: none;
        box-sizing: border-box;
    }

    .ws-form-group input:focus,
    .ws-form-group select:focus,
    .ws-form-group textarea:focus {
        border-color: #5146e5;
    }

    .ws-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
    }

    .ws-existing-images {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 12px;
    }

    .ws-existing-image {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 8px;
        font-size: 12px;
    }

    .ws-existing-image img {
        width: 100%;
        height: 85px;
        object-fit: cover;
        border-radius: 6px;
    }

    .ws-alert {
        padding: 13px 17px;
        border-radius: 9px;
        margin-bottom: 18px;
    }

    .ws-success {
        background: #e8f8ef;
        color: #167343;
    }

    .ws-error {
        background: #fff0f0;
        color: #b42318;
    }

    .ws-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        background: #e8f8ef;
        color: #167343;
        font-size: 12px;
    }

    .ws-empty {
        background: white;
        border-radius: 14px;
        padding: 50px 20px;
        text-align: center;
        color: #8490a3;
    }

    @media(max-width:600px) {
        .ws-page {
            padding: 12px;
        }

        .ws-title {
            font-size: 22px;
        }
    }
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

    {{-- Header --}}

    <div class="ws-header">
        <div>
            <h1 class="ws-title">Workspace Details</h1>
            <p class="ws-subtitle">
                Manage your workspace and its rooms.
            </p>
        </div>
    </div>

    {{-- Workspace Overview --}}

    <div class="ws-overview">

        <h2>{{ $workspace->name }}</h2>

        <p>{{ $workspace->description ?? 'No description available.' }}</p>

        <div class="ws-info">

            <div class="ws-info-item">
                Location
                <strong>{{ $workspace->location }}</strong>
            </div>

            <div class="ws-info-item">
                Approval Status
                <strong>{{ ucfirst($workspace->approval_status) }}</strong>
            </div>

            <div class="ws-info-item">
                Commission Rate
                <strong>{{ $workspace->commission_rate }}%</strong>
            </div>

            <div class="ws-info-item">
                Status
                <strong>
                    {{ $workspace->is_active ? 'Active' : 'Inactive' }}
                </strong>
            </div>

            <div class="ws-info-item">
                Total Rooms
                <strong>{{ $rooms->count() }}</strong>
            </div>

        </div>

        {{-- Workspace Facilities --}}

        @if($workspace->facilities)
            <div style="margin-top:20px;">
                <strong>Facilities</strong>

                <p>
                    {{ implode(', ', $workspace->facilities) }}
                </p>
            </div>
        @endif

        {{-- Workspace Images --}}

        <h3 style="margin-top:25px;">Workspace Gallery</h3>

        <div class="ws-gallery">

            @forelse($workspace->images as $image)

                <img
                    src="{{ asset('storage/' . $image->image_path) }}"
                    alt="{{ $image->alt_text ?? $workspace->name }}"
                >

            @empty

                <p>No workspace images available.</p>

            @endforelse

        </div>

    </div>

    {{-- Rooms Section --}}

    <div class="ws-section-header">

        <div>
            <h2>Workspace Rooms</h2>

            <p class="ws-subtitle">
                Manage all rooms belonging to this workspace.
            </p>
        </div>

      <button
    type="button"
    class="ws-btn ws-btn-primary"
    onclick="openAddModal()"
>
    + Add New Room
</button>

    </div>

    {{-- Room Cards --}}

    <div class="ws-room-grid">

        @forelse($rooms as $room)

            @php
                $primaryImage = $room->images->firstWhere('is_primary', true)
                    ?? $room->images->first();
            @endphp

            <div class="ws-room-card">

                @if($primaryImage)
<div class="room-gallery">

    @php
        $roomImages = $room->images
            ->sortBy('sort_order')
            ->sortByDesc('is_primary')
            ->values();
    @endphp

    @if($roomImages->count() > 0)

        <div class="room-gallery-track">

            @foreach($roomImages as $index => $image)
                <img
                    src="{{ asset('storage/' . $image->image_path) }}"
                    class="room-gallery-image {{ $index === 0 ? 'active' : '' }}"
                    alt="{{ $image->alt_text ?? $room->name }}"
                >
            @endforeach

        </div>

        @if($roomImages->count() > 1)
            <button type="button"
                    class="gallery-arrow prev"
                    onclick="changeRoomImage(this, -1)">
                &#10094;
            </button>

            <button type="button"
                    class="gallery-arrow next"
                    onclick="changeRoomImage(this, 1)">
                &#10095;
            </button>

            <div class="gallery-counter">
                <span class="current-image">1</span>
                /
                {{ $roomImages->count() }}
            </div>
        @endif

    @else

        <div class="no-room-image">
            No Images Available
        </div>

    @endif

</div>
                @endif

                <div class="ws-room-content">

                    <span class="ws-badge">Active</span>

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
                            onclick="openEditModal({{ $room->id }})"
                        >
                            Edit
                        </button>

                        <form
                            method="POST"
                            action="{{ route('manager.rooms.destroy', $room->id) }}"
                            onsubmit="return confirm('Are you sure you want to deactivate this room?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="ws-btn ws-btn-delete"
                            >
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
                    onclick="openAddModal()"
                >
                    Add Your First Room
                </button>
            </div>

        @endforelse

    </div>

</div>


{{-- ADD ROOM MODAL --}}

<div class="ws-modal" id="addRoomModal">

    <div class="ws-modal-content">

        <div class="ws-modal-header">
            <h2>Add New Room</h2>

            <button
                type="button"
                class="ws-close"
                onclick="closeModal('addRoomModal')"
            >
                &times;
            </button>
        </div>

        <form
            method="POST"
            action="{{ route('manager.rooms.store', $workspace->id) }}"
            enctype="multipart/form-data"
        >

            @csrf

            <div class="ws-form-group">
                <label>Room Name</label>
                <input type="text" name="name" required>
            </div>

            <div class="ws-form-group">
                <label>Room Type</label>

                <input
                    type="text"
                    name="room_type"
                    placeholder="Meeting Room, Private Office..."
                    required
                >
            </div>

            <div class="ws-form-group">
                <label>Capacity</label>
                <input type="number" name="capacity" min="1" required>
            </div>

            <div class="ws-form-group">
                <label>Hourly Price</label>
                <input
                    type="number"
                    name="hourly_price"
                    step="0.01"
                    min="0"
                    required
                >
            </div>

            <div class="ws-form-group">
                <label>Facilities</label>

                <textarea
                    name="facilities"
                    rows="3"
                    placeholder="Projector, WiFi, Whiteboard, Screen"
                ></textarea>

                <small>Separate facilities with commas.</small>
            </div>

            <div class="ws-form-group">
                <label>Room Images</label>

                <input
                    type="file"
                    name="images[]"
                    accept="image/*"
                    multiple
                >

                <small>You can select multiple images.</small>
            </div>

            <div class="ws-modal-actions">

                <button
                    type="button"
                    class="ws-btn"
                    onclick="closeModal('addRoomModal')"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="ws-btn ws-btn-primary"
                >
                    Save Room
                </button>

            </div>

        </form>

    </div>

</div>


{{-- EDIT ROOM MODAL --}}

<div class="ws-modal" id="editRoomModal">

    <div class="ws-modal-content">

        <div class="ws-modal-header">

            <h2>Edit Room</h2>

            <button
                type="button"
                class="ws-close"
                onclick="closeModal('editRoomModal')"
            >
                &times;
            </button>

        </div>

        <form
            method="POST"
            id="editRoomForm"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <div class="ws-form-group">
                <label>Room Name</label>

                <input
                    type="text"
                    name="name"
                    id="edit_name"
                    required
                >
            </div>

            <div class="ws-form-group">
                <label>Room Type</label>

                <input
                    type="text"
                    name="room_type"
                    id="edit_room_type"
                    required
                >
            </div>

            <div class="ws-form-group">
                <label>Capacity</label>

                <input
                    type="number"
                    name="capacity"
                    id="edit_capacity"
                    min="1"
                    required
                >
            </div>

            <div class="ws-form-group">
                <label>Hourly Price</label>

                <input
                    type="number"
                    name="hourly_price"
                    id="edit_hourly_price"
                    step="0.01"
                    min="0"
                    required
                >
            </div>

            <div class="ws-form-group">
                <label>Facilities</label>

                <textarea
                    name="facilities"
                    id="edit_facilities"
                    rows="3"
                ></textarea>
            </div>

            <div class="ws-form-group">

                <label>Current Images</label>

                <div
                    class="ws-existing-images"
                    id="existingRoomImages"
                ></div>

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
                    multiple
                >

            </div>

            <div class="ws-modal-actions">

                <button
                    type="button"
                    class="ws-btn"
                    onclick="closeModal('editRoomModal')"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="ws-btn ws-btn-primary"
                >
                    Update Room
                </button>

            </div>

        </form>

    </div>

</div>


{{-- Room Data for Edit Modal --}}

@php
    $roomsData = $rooms->mapWithKeys(function ($room) {
        return [
            $room->id => [
                'id' => $room->id,
                'name' => $room->name,
                'room_type' => $room->room_type,
                'capacity' => $room->capacity,
                'hourly_price' => $room->hourly_price,
                'facilities' => $room->facilities ?? [],
                'images' => $room->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'path' => asset('storage/' . $image->image_path),
                        'primary' => $image->is_primary,
                    ];
                })->values()->all(),
            ],
        ];
    })->all();
@endphp

<script>
    const roomsData = {{ Illuminate\Support\Js::from($roomsData) }};

    function openAddModal() {
        document.getElementById('addRoomModal').classList.add('show');
    }

    function openEditModal(roomId) {
        const room = roomsData[roomId];

        if (!room) {
            console.error('Room not found:', roomId);
            return;
        }

        document.getElementById('editRoomForm').action =
            `/manager/rooms/${room.id}`;

        document.getElementById('edit_name').value = room.name;
        document.getElementById('edit_room_type').value = room.room_type;
        document.getElementById('edit_capacity').value = room.capacity;
        document.getElementById('edit_hourly_price').value = room.hourly_price;

        document.getElementById('edit_facilities').value =
            (room.facilities || []).join(', ');

        const imagesContainer = document.getElementById('existingRoomImages');
        imagesContainer.innerHTML = '';

        room.images.forEach(image => {
            const div = document.createElement('div');
            div.className = 'ws-existing-image';

            const img = document.createElement('img');
            img.src = image.path;
            img.alt = 'Room image';

            const primaryLabel = document.createElement('label');
            const radio = document.createElement('input');

            radio.type = 'radio';
            radio.name = 'primary_image_id';
            radio.value = image.id;
            radio.checked = image.primary;

            primaryLabel.append(radio, ' Primary');

            const removeLabel = document.createElement('label');
            const checkbox = document.createElement('input');

            checkbox.type = 'checkbox';
            checkbox.name = 'remove_images[]';
            checkbox.value = image.id;

            removeLabel.append(checkbox, ' Remove');

            div.append(img, primaryLabel, removeLabel);
            imagesContainer.appendChild(div);
        });

        document.getElementById('editRoomModal').classList.add('show');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('show');
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

    console.log('Room JavaScript loaded');
</script>