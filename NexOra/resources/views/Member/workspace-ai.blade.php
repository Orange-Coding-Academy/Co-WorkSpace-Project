
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>NEXORA | AI Workspace Assistant</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #182230;
        }

        .page {
            max-width: 1100px;
            margin: 45px auto;
            padding: 20px;
        }

        .hero {
            background: #172554;
            color: white;
            padding: 35px;
            border-radius: 18px;
            margin-bottom: 25px;
        }

        .hero h1 {
            margin-top: 0;
            font-size: 30px;
        }

        .hero p {
            color: #dbeafe;
            line-height: 1.7;
        }

        .panel {
            background: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 5px 25px rgba(0,0,0,.05);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .field {
            margin-bottom: 20px;
        }

        .field label {
            display: block;
            font-weight: bold;
            margin-bottom: 9px;
        }

        input[type="number"] {
            width: 100%;
            padding: 13px;
            border: 1px solid #d5dce5;
            border-radius: 9px;
            font-size: 15px;
        }

        .equipment-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .equipment-option {
            background: #f8fafc;
            padding: 13px;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
        }

        .equipment-option label {
            display: inline;
            font-weight: normal;
            margin-left: 5px;
        }

        .submit-btn {
            width: 100%;
            padding: 15px;
            background: #2563eb;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        .submit-btn:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        #loading, #results {
            display: none;
            margin-top: 25px;
        }

        .ai-answer {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 20px;
            border-radius: 10px;
            white-space: pre-wrap;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .room-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .room-card h3 {
            margin-top: 0;
            color: #1d4ed8;
        }

        .room-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            color: #475569;
            margin-top: 15px;
        }

        .error {
            color: #b91c1c;
            background: #fef2f2;
            padding: 15px;
            border-radius: 9px;
            margin-top: 15px;
            display: none;
        }

        @media (max-width: 650px) {
            .form-grid,
            .equipment-grid,
            .room-details {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <section class="hero">
        <h1>AI Workspace Assistant</h1>

        <p>
            Find the perfect workspace for your next meeting.
            Tell us your meeting size, required equipment,
            and budget. Our AI assistant will help you choose
            the most suitable rooms.
        </p>
    </section>

    <section class="panel">

        <h2>Tell us what you need</h2>

        <form id="workspaceAIForm"
              data-url="{{ route('member.workspace-ai.recommend') }}">

            <div class="form-grid">

                <div class="field">
                    <label for="attendees">Meeting size</label>

                    <input
                        type="number"
                        id="attendees"
                        name="attendees"
                        min="1"
                        max="100"
                        placeholder="Number of people"
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
                        placeholder="Enter your budget"
                        required
                    >
                </div>

            </div>

            <div class="field">

                <label>Required equipment</label>

                <div class="equipment-grid">

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="Projector">
                        <label>Projector</label>
                    </div>

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="Whiteboard">
                        <label>Whiteboard</label>
                    </div>

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="WiFi">
                        <label>WiFi</label>
                    </div>

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="Screen">
                        <label>Screen</label>
                    </div>

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="TV">
                        <label>TV</label>
                    </div>

                    <div class="equipment-option">
                        <input type="checkbox"
                               name="equipment[]"
                               value="Video Conference">
                        <label>Video Conference</label>
                    </div>

                </div>

                <small>
                    Select all equipment required for your meeting.
                </small>

            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                Find My Perfect Workspace
            </button>

        </form>

        <div id="loading">
            <p>AI is analyzing your requirements and finding suitable rooms...</p>
        </div>

        <div id="errorMessage" class="error"></div>

    </section>

    <section id="results">

        <h2>AI Recommendations</h2>

        <div id="aiAnswer" class="ai-answer"></div>

        <h2>Matching Rooms</h2>

        <div id="roomsList"></div>

    </section>

</div>

<script>

const form = document.getElementById('workspaceAIForm');

const loading = document.getElementById('loading');
const results = document.getElementById('results');
const aiAnswer = document.getElementById('aiAnswer');
const roomsList = document.getElementById('roomsList');
const errorMessage = document.getElementById('errorMessage');
const submitBtn = document.getElementById('submitBtn');

function escapeHTML(value) {
    return String(value ?? '').replace(/[&<>"']/g, function (char) {
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

form.addEventListener('submit', async function (event) {

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
            <p>No matching rooms were found.
               Try increasing your budget or changing your requirements.</p>
        `;

        return;
    }

    rooms.forEach(function (room) {

        const facilities = Array.isArray(room.facilities)
            ? room.facilities.map(escapeHTML).join(', ')
            : 'Not specified';

        const card = document.createElement('div');

        card.className = 'room-card';

        card.innerHTML = `
            <h3>${escapeHTML(room.room_name)}</h3>

            <p>
                <strong>Workspace:</strong>
                ${escapeHTML(room.workspace_name)}
            </p>

            <div class="room-details">

                <div>
                    <strong>Location:</strong>
                    ${escapeHTML(room.location || 'Not specified')}
                </div>

                <div>
                    <strong>Room type:</strong>
                    ${escapeHTML(room.room_type || 'Not specified')}
                </div>

                <div>
                    <strong>Capacity:</strong>
                    ${escapeHTML(room.capacity)} people
                </div>

                <div>
                    <strong>Hourly price:</strong>
                    ${Number(room.hourly_price).toFixed(2)}
                </div>

                <div>
                    <strong>Facilities:</strong>
                    ${facilities}
                </div>

            </div>
        `;

        roomsList.appendChild(card);

    });

}

</script>

</body>
</html>
