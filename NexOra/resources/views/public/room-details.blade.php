
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $room->name }} | NexOra</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f7f8fa;
            color: #202b3c;
        }

        .page {
            max-width: 1150px;
            margin: auto;
            padding: 40px 22px;
        }

        .back {
            display: inline-block;
            margin-bottom: 25px;
            color: #263b5b;
            text-decoration: none;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .gallery {
            position: relative;
            height: 380px;
            border-radius: 16px;
            overflow: hidden;
            background: #e9edf2;
        }

        .gallery img {
            display: none;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gallery img.active {
            display: block;
        }

        .gallery button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            background: rgba(0,0,0,.6);
            color: white;
        }

        .gallery .prev {
            left: 12px;
        }

        .gallery .next {
            right: 12px;
        }

        .room-info,
        .booking-box,
        .calendar-box {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,.04);
        }

        .workspace {
            color: #778497;
            margin-bottom: 12px;
        }

        h1 {
            font-size: 30px;
            margin-bottom: 15px;
        }

        .description {
            line-height: 1.7;
            color: #718096;
            margin: 18px 0;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 20px 0;
        }

        .meta span,
        .facility {
            background: #edf2f7;
            padding: 9px 12px;
            border-radius: 20px;
            font-size: 13px;
        }

        .facilities {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 15px;
        }

        .price {
            font-size: 27px;
            font-weight: bold;
            color: #263b5b;
            margin-top: 25px;
        }

        .price small {
            font-size: 13px;
            color: #718096;
        }

        .booking-section {
            margin-top: 30px;
        }

        .booking-section h2 {
            margin-bottom: 18px;
        }

        .calendar-box {
            margin-bottom: 20px;
        }

        .calendar-box label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input, select {
            padding: 12px;
            width: 100%;
            border: 1px solid #dfe5ec;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .slots {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .slot {
            border: 0;
            border-radius: 8px;
            padding: 13px 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .slot.available {
            background: #dcfce7;
            color: #166534;
        }

        .slot.booked {
            background: #fee2e2;
            color: #991b1b;
            cursor: not-allowed;
        }

        .slot.past {
            background: #e5e7eb;
            color: #6b7280;
            cursor: not-allowed;
        }

        .slot.selected {
            outline: 3px solid #263b5b;
        }

        .legend {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 20px;
            font-size: 13px;
        }

        .legend span::before {
            content: "";
            display: inline-block;
            width: 11px;
            height: 11px;
            margin-right: 7px;
            border-radius: 3px;
        }

        .legend .green::before {
            background: #22c55e;
        }

        .legend .red::before {
            background: #ef4444;
        }

        .legend .gray::before {
            background: #9ca3af;
        }

        .booking-box {
            margin-top: 20px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            border: 0;
            border-radius: 9px;
            background: #263b5b;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .message {
            margin-top: 15px;
            color: #b91c1c;
        }

        @media(max-width: 800px) {
            .details-grid {
                grid-template-columns: 1fr;
            }

            .slots {
                grid-template-columns: repeat(3, 1fr);
            }

            .gallery {
                height: 280px;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <a href="{{ route('explore') }}" class="back">
        ← Back to Explore
    </a>

    <div class="details-grid">

        <div class="gallery">

            @forelse($room->images as $index => $image)

                <img
                    src="{{ asset('storage/' . $image->image_path) }}"
                    class="{{ $index === 0 ? 'active' : '' }}"
                    alt="{{ $image->alt_text ?? $room->name }}"
                >

            @empty

                <div style="height:100%;display:grid;place-items:center;">
                    No Image Available
                </div>

            @endforelse

            @if($room->images->count() > 1)

                <button type="button" class="prev"
                    onclick="changeImage(this,-1)">&#10094;</button>

                <button type="button" class="next"
                    onclick="changeImage(this,1)">&#10095;</button>

            @endif

        </div>

        <div class="room-info">

            <div class="workspace">
                {{ $room->workspace->name }}
            </div>

            <h1>{{ $room->name }}</h1>

            <div class="meta">
                <span>{{ $room->room_type }}</span>
                <span>{{ $room->capacity }} People</span>
                <span>{{ $room->workspace->location }}</span>
            </div>

            <p class="description">
                {{ $room->workspace->description ?? 'No workspace description available.' }}
            </p>

            <h3>Room Equipment</h3>

            <div class="facilities">
                @forelse(($room->facilities ?? []) as $facility)
                    <span class="facility">{{ $facility }}</span>
                @empty
                    <span>No equipment information available.</span>
                @endforelse
            </div>

            <div class="price">
                {{ number_format((float)$room->hourly_price, 2) }}
                <small>/ hour</small>
            </div>

        </div>

    </div>

    <div class="booking-section">

        <h2>Check Availability</h2>

        <div class="calendar-box">

            <label for="bookingDate">Select Date</label>

            <input
                type="date"
                id="bookingDate"
                min="{{ now()->toDateString() }}"
                value="{{ now()->toDateString() }}"
            >

            <div id="slots" class="slots">
                <p>Select a date to view availability.</p>
            </div>

            <div class="legend">
                <span class="green">Available</span>
                <span class="red">Booked</span>
                <span class="gray">Past / Unavailable</span>
            </div>

            <p id="availabilityMessage" class="message"></p>

        </div>

        <div class="booking-box">

            <form
                method="POST"
                action="{{ route('rooms.book', $room->id) }}"
                id="bookingForm"
            >

                @csrf

                <label for="duration">Booking Duration</label>

                <select name="duration" id="duration" required>
                    @for($i = 1; $i <= 48; $i++)
                        <option value="{{ $i }}">
                            {{ $i }} {{ $i === 1 ? 'Hour' : 'Hours' }}
                        </option>
                    @endfor
                </select>

                <input
                    type="hidden"
                    name="start_date_time"
                    id="startDateTime"
                >

                <button type="submit" class="btn">
                    Book Now
                </button>

            </form>

            @if($errors->has('booking'))
                <p class="message">{{ $errors->first('booking') }}</p>
            @endif

        </div>

    </div>

</div>

<script>

const roomId = {{ $room->id }};

const dateInput = document.getElementById('bookingDate');
const slotsContainer = document.getElementById('slots');
const startDateTimeInput = document.getElementById('startDateTime');
const durationInput = document.getElementById('duration');
const message = document.getElementById('availabilityMessage');

let selectedStart = null;
let availableSlots = [];

async function loadAvailability() {

    selectedStart = null;
    startDateTimeInput.value = '';

    message.textContent = 'Loading availability...';
    slotsContainer.innerHTML = '';

    const date = dateInput.value;

    if (!date) return;

    try {

        const response = await fetch(
            `/rooms/${roomId}/availability?date=${encodeURIComponent(date)}`
        );

        if (!response.ok) {
            throw new Error('Could not load availability.');
        }

        const data = await response.json();

        availableSlots = data.slots;

        data.slots.forEach(slot => {

            const button = document.createElement('button');

            button.type = 'button';
            button.className = `slot ${slot.status}`;
            button.textContent = slot.start;

            if (slot.available) {

                button.addEventListener('click', () => {

                    document.querySelectorAll('.slot.selected')
                        .forEach(item => item.classList.remove('selected'));

                    button.classList.add('selected');

                    selectedStart = slot.start;

                    startDateTimeInput.value =
                        `${date} ${slot.start}:00`;

                    message.textContent =
                        `Selected start time: ${slot.start}`;

                });

            } else {

                button.disabled = true;

            }

            slotsContainer.appendChild(button);

        });

        message.textContent = 'Choose an available start time.';

    } catch (error) {

        message.textContent = error.message;

    }
}

dateInput.addEventListener('change', loadAvailability);

document.getElementById('bookingForm').addEventListener('submit', function(event) {

    if (!selectedStart || !startDateTimeInput.value) {

        event.preventDefault();

        message.textContent =
            'Please select an available start time first.';

        return;
    }

    const duration = parseInt(durationInput.value, 10);

    const start = new Date(
        `${dateInput.value}T${selectedStart}:00`
    );

    const end = new Date(start.getTime() + duration * 60 * 60 * 1000);

    // Check the selected booking period against all displayed hourly slots.
    let cursor = new Date(start);

    while (cursor < end) {

        const hour = cursor.getHours();

        const slot = availableSlots.find(item =>
            item.start === String(hour).padStart(2, '0') + ':00'
        );

        if (!slot || !slot.available) {

            event.preventDefault();

            message.textContent =
                'The selected duration includes an unavailable hour. Please choose another time.';

            return;
        }

        cursor.setHours(cursor.getHours() + 1);
    }

});

function changeImage(button, direction) {

    const gallery = button.closest('.gallery');
    const images = gallery.querySelectorAll('img');

    if (images.length < 2) return;

    let current = Array.from(images).findIndex(
        image => image.classList.contains('active')
    );

    if (current < 0) current = 0;

    images[current].classList.remove('active');

    current = (current + direction + images.length) % images.length;

    images[current].classList.add('active');
}

loadAvailability();

</script>

</body>
</html>
