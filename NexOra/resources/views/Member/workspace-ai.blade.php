
@extends('layouts.member')

@section('title')
    NEXORA | AI Workspace Assistant
@endsection

@push('styles')

<link rel="stylesheet" href="{{ asset('css/pages/member-workspace-ai.css') }}?v=20261125-green-contrast4">
@endpush

@section('content')
<div class="nx-page-content">
    <div class="page">

        <section class="hero">
            <div class="hero-content">
                <div class="hero-kicker">NEXORA INTELLIGENCE</div>

                <h1>
                    Your next great
                    <span>workspace</span>
                    starts here.
                </h1>

                <p>
                    A smarter way to find your ideal meeting space.
                    Share your requirements and let our AI discover
                    the workspace that fits your plans.
                </p>
            </div>

            <div class="hero-visual" aria-hidden="true">
                <div class="orbit">
                    <span class="orbit-dot one"></span>
                    <span class="orbit-dot two"></span>
                    <div class="orbit-core">✦</div>
                </div>
            </div>
        </section>

        <div class="intro-strip">
            <div class="intro-label">PERSONALIZED WORKSPACE MATCHING</div>
            <div class="intro-note">Thoughtfully matched. Intelligently selected.</div>
        </div>

        <section class="panel">

            <div class="panel-heading">
                <div>
                    <h2>Let's find your perfect space</h2>
                    <p>Tell us a little about your meeting to get tailored recommendations.</p>
                </div>

                <div class="private-note">
                    <span>✦</span>
                    AI Powered Search
                </div>
            </div>

            <form
                id="workspaceAIForm"
                data-url="{{ route('member.workspace-ai.recommend') }}"
                data-room-url="{{ route('rooms.details', ['room' => '__ROOM_ID__']) }}"
            >

                <div class="form-section-label">
                    <span>01</span>
                    Meeting requirements
                </div>

                <div class="form-grid">

                    <div class="field">
                        <label for="attendees">How many people are attending?</label>
                        <input
                            type="number"
                            id="attendees"
                            name="attendees"
                            min="1"
                            max="100"
                            placeholder="e.g. 12 attendees"
                            required
                        >
                    </div>

                    <div class="field">
                        <label for="budget">Maximum hourly budget</label>
                        <input
                            type="number"
                            id="budget"
                            name="budget"
                            min="0.01"
                            step="0.01"
                            placeholder="e.g. 25.00"
                            required
                        >
                    </div>

                </div>

                <div class="equipment-area">

                    <div class="form-section-label">
                        <span>02</span>
                        What equipment will you need?
                    </div>

                    <div class="equipment-grid">

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="Projector">
                            <span>Projector</span>
                        </label>

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="Whiteboard">
                            <span>Whiteboard</span>
                        </label>

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="WiFi">
                            <span>High-speed WiFi</span>
                        </label>

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="Screen">
                            <span>Presentation Screen</span>
                        </label>

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="TV">
                            <span>Smart TV</span>
                        </label>

                        <label class="equipment-option">
                            <input type="checkbox" name="equipment[]" value="Video Conference">
                            <span>Video Conference</span>
                        </label>

                    </div>

                    <p class="equipment-help">
                        Select all the facilities that matter for your meeting.
                    </p>

                </div>

                <div class="form-footer">
                    <div class="form-footer-note">
                        <strong class="span1">✧</strong>
                        <span>
                            <strong>Smart recommendations</strong><br>
                           <span> Based on your requirements and budget.</span>
                        </span>
                    </div>

                    <button type="submit" class="submit-btn" id="submitBtn">
                        Find My Perfect Workspace
                    </button>
                </div>

            </form>

            <div id="loading">
                <div class="loading-orbit"></div>
                <p>Our AI is finding the right workspace for you...</p>
            </div>

            <div id="errorMessage" class="error"></div>

        </section>

        <section id="results">

            <div class="results-heading">
                <h2>AI Recommendations</h2>
                <span class="result-tag">PERSONALIZED INSIGHT</span>
                <span class="heading-line"></span>
            </div>

            <a id="resultsJump" class="nx-ai-results-jump" href="#roomsList" hidden>
                See matching workspaces <span aria-hidden="true">↓</span>
            </a>

            <div class="results-heading" style="margin-top:27px;">
                <h2>Matching Workspaces</h2>
                <span class="heading-line"></span>
            </div>

            <div id="roomsList"></div>

        </section>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const form = document.getElementById('workspaceAIForm');

    const loading = document.getElementById('loading');
    const results = document.getElementById('results');
    const resultsJump = document.getElementById('resultsJump');
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
        resultsJump.hidden = true;
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
    ).getAttribute('content'),

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

          

            displayRooms(data.rooms || []);

            results.style.display = 'block';
            resultsJump.hidden = false;

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
</script>
@endpush
