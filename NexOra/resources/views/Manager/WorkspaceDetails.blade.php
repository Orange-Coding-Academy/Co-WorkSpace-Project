@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Workspace details @endsection
@section('page-title') Workspace details @endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/manager-workspacedetails.css') }}?v=20261125-ui2">
@endpush

@push('page-overrides')
<link rel="stylesheet" href="{{ asset('css/nexora/workspace-pages.css') }}?v=20261125-ui5">
@endpush

@section('content')
<div class="nx-page-content nx-workspace-ui nx-workspace-details">
    {{-- Workspace Details Page --}}

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

        <nav class="ws-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('manager.dashboard') }}">Manager</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('manager.workspaces.create', ['view' => 'list']) }}">My workspaces</a>
            <span aria-hidden="true">/</span>
            <strong>{{ $workspace->name }}</strong>
        </nav>

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
                <img
    src="{{ asset($image->image_path) }}"
    alt="{{ $image->alt_text ?: $workspace->name . ' photo ' . $loop->iteration }}"
>
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
    src="{{ asset('storage/' . $image->image_path) }}"
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
                    <div style="margin-top:12px;" >
                        <small class="fac">
                            <strong class="fac">Facilities:</strong>
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
                            onsubmit="return openDeleteModal(event, this)">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="ws-btn ws-btn-delete">
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


    <dialog id="deleteModal" class="custom-delete-modal">
        <div class="delete-modal-header">
            <div class="delete-modal-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="30"
                    height="30"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M3 6h18" />
                    <path d="M9 6V4h6v2" />
                    <path d="M5 6l1 14h12l1-14" />
                    <path d="M10 10v6M14 10v6" />
                </svg>
            </div>
            <button type="button" onclick="closeDeleteModal()">×</button>
        </div>

        <h3>Delete Room?</h3>

        <p>
            Are you sure you want to delete this room?
            This action cannot be undone.
        </p>

        <div class="delete-modal-actions">
            <button type="button"
                class="modal-cancel"
                onclick="closeDeleteModal()">
                Cancel
            </button>

            <button type="button"
                class="modal-delete"
                onclick="confirmDelete()">
                Yes, Delete
            </button>
        </div>
    </dialog>


    {{-- EDIT ROOM MODAL --}}

    <div class="ws-modal" id="editRoomModal" role="dialog" aria-modal="true" aria-labelledby="edit-room-title">

        <div class="ws-modal-content">

            <div class="ws-modal-header">

                <h2 id="edit-room-title">Edit Room</h2>

                <button
                    type="button"
                    class="ws-close"
                    aria-label="Close edit room"
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
                    <label for="edit_name">Room Name</label>

                    <input
                        type="text"
                        name="name"
                        id="edit_name"
                        required>
                </div>

                <div class="ws-form-group">
                    <label for="edit_room_type">Room Type</label>

                    <input
                        type="text"
                        name="room_type"
                        id="edit_room_type"
                        required>
                </div>

                <div class="ws-form-group">
                    <label for="edit_capacity">Capacity</label>

                    <input
                        type="number"
                        name="capacity"
                        id="edit_capacity"
                        min="1"
                        required>
                </div>

                <div class="ws-form-group">
                    <label for="edit_hourly_price">Hourly Price</label>

                    <input
                        type="number"
                        name="hourly_price"
                        id="edit_hourly_price"
                        step="0.01"
                        min="0"
                        required>
                </div>

                <div class="ws-form-group">
                    <label for="edit_facilities">Facilities</label>

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
                        Mark photos for removal, then click Update Room to save.
                        You can undo removal before saving. If you remove the primary photo,
                        another remaining photo becomes primary automatically.
                    </small>
                    <p id="edit-room-image-status" role="status" aria-live="polite"></p>

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


</div>
@if(old('_room_form') === 'add' && $errors->any())
openAddModal();
@endif

@endsection

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('addRoomModal').showModal();
    }


    function syncRoomImageRemoval(container) {
        const radios = Array.from(container.querySelectorAll('input[name="primary_image_id"]'));
        let removedCount = 0;

        container.querySelectorAll('.ws-existing-image').forEach(item => {
            const remove = item.querySelector('input[name="remove_images[]"]');
            const primary = item.querySelector('input[name="primary_image_id"]');
            const pending = remove.checked;
            item.classList.toggle('is-pending-removal', pending);
            primary.disabled = pending;
            if (pending) {
                primary.checked = false;
                removedCount++;
            }
            item.querySelector('[data-removal-label]').textContent = pending ?
                ' Undo removal' : ' Remove photo';
        });

        const available = radios.filter(radio => !radio.disabled);
        if (available.length && !available.some(radio => radio.checked)) {
            available[0].checked = true;
        }
        document.getElementById('edit-room-image-status').textContent = removedCount ?
            `${removedCount} photo${removedCount === 1 ? '' : 's'} marked for removal. Changes apply when you click Update Room.` :
            'No photos marked for removal.';
    }

    function openEditModal(button) {
        const card = button.closest('.ws-room-card');
        const room = card.dataset;

        document.getElementById('editRoomForm').reset();
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

            const removalText = document.createElement('span');
            removalText.dataset.removalLabel = '';
            removalText.textContent = ' Remove photo';
            removeLabel.append(checkbox, removalText);
            checkbox.addEventListener('change', () => syncRoomImageRemoval(imagesContainer));

            div.append(img, primaryLabel, removeLabel);
            imagesContainer.appendChild(div);
        });
        syncRoomImageRemoval(imagesContainer);

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


    let deleteForm = null;

    function openDeleteModal(event, form) {
        event.preventDefault();

        deleteForm = form;

        document.getElementById('deleteModal').showModal();

        return false;
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').close();
        deleteForm = null;
    }

    function confirmDelete() {
        if (deleteForm) {
            deleteForm.submit();
        }
    }


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