
    const form = document.getElementById('workspaceAIForm');

    const loading = document.getElementById('loading');
    const results = document.getElementById('results');
    const aiAnswer = document.getElementById('aiAnswer');
    const roomsList = document.getElementById('roomsList');
    const errorMessage = document.getElementById('errorMessage');
    const submitBtn = document.getElementById('submitBtn');

    function escapeHTML(value) {
        return String(value ?? '').replace(/[&<>"']/g, function(char) {
            const entities = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };

            return entities[char];
        });
    }

    form.addEventListener('submit', async function(event) {

        event.preventDefault();

        loading.style.display = 'block';
        results.style.display = 'none';
        errorMessage.style.display = 'none';

        submitBtn.disabled = true;
        submitBtn.textContent = 'Analyzing...';

        const formData = new FormData(form);

        try {

            const response = await fetch(form.dataset.url, {

                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,

                    'Accept': 'application/json'
                },

                body: formData

            });

            const data = await response.json();

            if (!response.ok) {

                if (response.status === 422 && data.errors) {
                    const messages = Object.values(data.errors).flat();
                    throw new Error(messages.join('\n'));
                }

                throw new Error(
                    data.message || 'Something went wrong.'
                );
            }

            aiAnswer.textContent = data.answer || 'No AI explanation received.';

            displayRooms(data.rooms || []);

            results.style.display = 'block';

        } catch (error) {

            errorMessage.textContent = error.message;
            errorMessage.style.display = 'block';

        } finally {

            loading.style.display = 'none';

            submitBtn.disabled = false;
            submitBtn.textContent = 'Find My Perfect Workspace';

        }

    });


    function displayRooms(rooms) {

        roomsList.innerHTML = '';

        if (rooms.length === 0) {

            roomsList.innerHTML = `
                <p>
                    No matching rooms were found.
                    Try increasing your budget or changing your requirements.
                </p>
            `;

            return;
        }

        const roomUrlTemplate = form.dataset.roomUrl;

        rooms.forEach(function(room) {

            const facilities = Array.isArray(room.facilities) ?
                room.facilities.map(escapeHTML).join(', ') :
                'Not specified';

            const card = document.createElement('div');

            card.className = 'room-card';

            // Generate the correct details URL using the room ID.
            const hasRoomId =
                room.id !== undefined &&
                room.id !== null &&
                String(room.id).trim() !== '';

            const bookUrl = hasRoomId
                ? roomUrlTemplate.replace(
                    '__ROOM_ID__',
                    encodeURIComponent(String(room.id))
                )
                : null;

            const bookButton = bookUrl
                ? `
                    <a href="${escapeHTML(bookUrl)}" class="room-book-btn">
                        <span>Book This Room</span>
                        <span class="book-arrow" aria-hidden="true">→</span>
                    </a>
                `
                : `
                    <span class="room-book-unavailable">
                        Booking currently unavailable
                    </span>
                `;

            const hourlyPrice = Number(room.hourly_price);

            const formattedPrice = Number.isFinite(hourlyPrice)
                ? hourlyPrice.toFixed(2)
                : 'Not specified';

            card.innerHTML = `
                <h3>${escapeHTML(room.room_name)}</h3>

                <p>
                    <strong>Workspace:</strong>
                    ${escapeHTML(room.workspace_name)}
                </p>

                <div class="room-details">

                    <div>
                        <strong>Location</strong>
                        ${escapeHTML(room.location || 'Not specified')}
                    </div>

                    <div>
                        <strong>Room type</strong>
                        ${escapeHTML(room.room_type || 'Not specified')}
                    </div>

                    <div>
                        <strong>Capacity</strong>
                        ${escapeHTML(room.capacity ?? 'Not specified')} people
                    </div>

                    <div>
                        <strong>Hourly price</strong>
                        ${formattedPrice}
                    </div>

                    <div>
                        <strong>Facilities</strong>
                        ${facilities}
                    </div>

                </div>

                <div class="room-card-footer">
                    ${bookButton}
                </div>
            `;

            roomsList.appendChild(card);

        });

    }
