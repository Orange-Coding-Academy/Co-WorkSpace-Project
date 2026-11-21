/* =========================================================
   NexOra — Room Details interactions
   Keeps the existing availability endpoint and booking checks.
   ========================================================= */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const gallery = document.getElementById('roomGallery');
        const dateInput = document.getElementById('bookingDate');
        const slotsContainer = document.getElementById('slots');
        const startDateTimeInput = document.getElementById('startDateTime');
        const durationInput = document.getElementById('duration');
        const bookingForm = document.getElementById('bookingForm');
        const message = document.getElementById('availabilityMessage');

        // Gallery controls and thumbnails
        if (gallery) {
            const images = Array.from(gallery.querySelectorAll('.nx-room__gallery-image'));
            const thumbnails = Array.from(document.querySelectorAll('[data-gallery-thumb]'));
            const currentLabel = document.getElementById('galleryCurrent');
            let activeIndex = Math.max(0, images.findIndex(img => img.classList.contains('is-active')));

            function showImage(index) {
                if (!images.length) return;
                activeIndex = (index + images.length) % images.length;

                images.forEach((image, i) => image.classList.toggle('is-active', i === activeIndex));
                thumbnails.forEach((thumb, i) => {
                    const active = i === activeIndex;
                    thumb.classList.toggle('is-active', active);
                    thumb.setAttribute('aria-pressed', active ? 'true' : 'false');
                });
                if (currentLabel) currentLabel.textContent = String(activeIndex + 1);
            }

            gallery.querySelectorAll('[data-gallery-step]').forEach(button => {
                button.addEventListener('click', function () {
                    showImage(activeIndex + Number(button.dataset.galleryStep));
                });
            });

            thumbnails.forEach((thumb, index) => {
                thumb.addEventListener('click', () => showImage(index));
            });
        }

        // Availability and booking validation
        if (!dateInput || !slotsContainer || !startDateTimeInput || !durationInput || !bookingForm || !message) {
            return;
        }

        const roomId = document.querySelector('.nx-room')?.dataset.roomId;
        let selectedStart = null;
        let availableSlots = [];
        let availabilityController = null;

        function setMessage(text, isError) {
            message.textContent = text;
            message.classList.toggle('is-error', Boolean(isError));
        }

        async function loadAvailability() {
            selectedStart = null;
            startDateTimeInput.value = '';
            availableSlots = [];
            setMessage('Loading availability…', false);
            slotsContainer.innerHTML = '<p class="nx-room__slots-placeholder">Checking available times for this date…</p>';

            const date = dateInput.value;
            if (!date) {
                slotsContainer.innerHTML = '<p class="nx-room__slots-placeholder">Select a date to view available times.</p>';
                setMessage('', false);
                return;
            }

            if (availabilityController) availabilityController.abort();
            availabilityController = new AbortController();

            try {
                const response = await fetch(
                    `/rooms/${roomId}/availability?date=${encodeURIComponent(date)}`,
                    {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                        signal: availabilityController.signal
                    }
                );

                if (!response.ok) throw new Error('Could not load availability. Please try again.');

                const data = await response.json();
                availableSlots = Array.isArray(data.slots) ? data.slots : [];
                slotsContainer.innerHTML = '';

                if (!availableSlots.length) {
                    slotsContainer.innerHTML = '<p class="nx-room__slots-placeholder">No time slots are available for this date.</p>';
                    setMessage('Please choose another date.', false);
                    return;
                }

                availableSlots.forEach(slot => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = `nx-room__slot ${slot.status}`;
                    button.textContent = slot.start;
                    button.setAttribute('aria-label', `${slot.start} — ${slot.status}`);

                    if (slot.available) {
                        button.addEventListener('click', function () {
                            slotsContainer.querySelectorAll('.nx-room__slot.selected')
                                .forEach(item => item.classList.remove('selected'));

                            button.classList.add('selected');
                            selectedStart = slot.start;
                            startDateTimeInput.value = `${date} ${slot.start}:00`;
                            setMessage(`Selected start time: ${slot.start}`, false);
                        });
                    } else {
                        button.disabled = true;
                    }

                    slotsContainer.appendChild(button);
                });

                setMessage('Choose an available start time.', false);
            } catch (error) {
                if (error.name === 'AbortError') return;
                slotsContainer.innerHTML = '<p class="nx-room__slots-placeholder">Availability could not be loaded.</p>';
                setMessage(error.message, true);
            }
        }

        dateInput.addEventListener('change', loadAvailability);

        bookingForm.addEventListener('submit', function (event) {
            if (!selectedStart || !startDateTimeInput.value) {
                event.preventDefault();
                setMessage('Please select an available start time first.', true);
                slotsContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            const duration = parseInt(durationInput.value, 10);
            const start = new Date(`${dateInput.value}T${selectedStart}:00`);
            const end = new Date(start.getTime() + duration * 60 * 60 * 1000);
            let cursor = new Date(start);

            // Keep the original rule: every hour in the selected duration must be available.
            while (cursor < end) {
                const hour = cursor.getHours();
                const expectedStart = String(hour).padStart(2, '0') + ':00';
                const slot = availableSlots.find(item => item.start === expectedStart);

                if (!slot || !slot.available) {
                    event.preventDefault();
                    setMessage('The selected duration includes an unavailable hour. Please choose another time.', true);
                    return;
                }

                cursor.setHours(cursor.getHours() + 1);
            }
        });

        loadAvailability();
    });
})();
