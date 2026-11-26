
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
