
(() => {
    const form = document.getElementById('registerForm');
    const steps = [...form.querySelectorAll('.nx-step')];
    const stepper = document.getElementById('stepper');
    const stepperItems = [...stepper.querySelectorAll('li')];
    const backBtn = document.getElementById('backBtn');
    const nextBtn = document.getElementById('nextBtn');
    const loginLink = document.getElementById('loginLink');
    const panel = form.querySelector('.nx-wizard__panel');
    const tabs = document.getElementById('roomTabs');
    const rooms = document.getElementById('rooms');
    const addRoomBtn = document.getElementById('addRoomBtn');

    const roomFacilities = ['WiFi', 'Air Conditioning', 'Projector', 'Smart Board', 'Whiteboard', 'TV Screen',
        'Video Conference', 'Power Outlets', 'Comfortable Chairs', 'Meeting Table', 'Sound System', 'Natural Light'];
    const roomTypes = ['Private Office', 'Meeting Room', 'Conference Room', 'Open Desk', 'Dedicated Desk',
        'Training Room', 'Event Space', 'Other'];

    let current = 0;
    let roomIndex = 0;

    const isManager = () => form.querySelector('input[name="account_type"]:checked').value === 'spaceManager';
    const lastIndex = () => (isManager() ? steps.length - 1 : 0);

    /* Members never submit workspace/room fields: they are disabled while hidden. */
    function applyAccountType() {
        const manager = isManager();
        stepper.hidden = !manager;
        steps.slice(1).forEach(step => {
            step.querySelectorAll('input, select, textarea').forEach(el => { el.disabled = !manager; });
        });
        form.querySelectorAll('[data-manager-required="true"]').forEach(el => { el.required = manager; });
        if (!manager && current > 0) current = 0;
        show(current);
    }

    function show(n) {
        current = n;
        steps.forEach((s, i) => { s.hidden = i !== n; });
        stepperItems.forEach((li, i) => {
            li.classList.toggle('is-active', i === n);
            li.classList.toggle('is-done', i < n);
        });
        backBtn.hidden = n === 0;
        loginLink.hidden = n !== 0;
        nextBtn.textContent = n === lastIndex() ? 'Create account' : 'Continue';
        panel.scrollTop = 0;
    }

    function activateRoom(card) {
        [...rooms.children].forEach(r => {
            r.hidden = r !== card;
            r._tab.classList.toggle('is-active', r === card);
        });
    }

    function renumberRooms() {
        const all = [...rooms.children];
        all.forEach((r, n) => {
            r._tab.textContent = `Room ${n + 1}`;
            r.querySelector('h3').textContent = `Room ${n + 1}`;
            r.querySelector('.nx-btn-remove').hidden = all.length === 1;
        });
    }

    function roomTemplate(i) {
        return `
        <div class="nx-room__bar">
            <h3></h3>
            <button type="button" class="nx-btn-remove">Remove room</button>
        </div>
        <div class="row g-3">
            <div class="col-sm-6">
                <label class="form-label">Room name <span class="nx-req">*</span></label>
                <input type="text" name="rooms[${i}][name]" class="form-control manager-input"
                       placeholder="e.g. Meeting Room A" data-manager-required="true">
            </div>
            <div class="col-sm-6">
                <label class="form-label">Room type <span class="nx-req">*</span></label>
                <select name="rooms[${i}][room_type]" class="form-select manager-input" data-manager-required="true">
                    <option value="">Select a type</option>
                    ${roomTypes.map(t => `<option value="${t}">${t}</option>`).join('')}
                </select>
            </div>
            <div class="col-sm-6">
                <label class="form-label">Capacity <span class="nx-req">*</span></label>
                <input type="number" name="rooms[${i}][capacity]" class="form-control manager-input"
                       min="1" placeholder="People" data-manager-required="true">
            </div>
            <div class="col-sm-6">
                <label class="form-label">Hourly price <span class="nx-req">*</span></label>
                <input type="number" name="rooms[${i}][hourly_price]" class="form-control manager-input"
                       min="0" step="0.01" placeholder="e.g. 15.00" data-manager-required="true">
            </div>
            <div class="col-12">
                <label class="form-label">Facilities</label>
                <div class="nx-chips">
                    ${roomFacilities.map(f => `
                        <label class="nx-chip">
                            <input type="checkbox" name="rooms[${i}][facilities][]" value="${f}" class="manager-input"> ${f}
                        </label>`).join('')}
                </div>
            </div>
            <div class="col-sm-6">
                <label class="form-label">Other facilities</label>
                <input type="text" name="rooms[${i}][custom_facilities]" class="form-control manager-input"
                       placeholder="Coffee machine, extra monitor">
            </div>
            <div class="col-sm-6">
                <label class="form-label">Room images</label>
                <input type="file" name="rooms[${i}][images][]" class="form-control manager-input"
                       accept="image/jpeg,image/png,image/webp" multiple>
            </div>
        </div>`;
    }

    function addRoom() {
        const card = document.createElement('div');
        card.className = 'nx-room';
        card.innerHTML = roomTemplate(roomIndex++);

        const tab = document.createElement('button');
        tab.type = 'button';
        tab.className = 'nx-tab';
        tab.addEventListener('click', () => activateRoom(card));

        card._tab = tab;
        tabs.insertBefore(tab, addRoomBtn);
        rooms.appendChild(card);

        card.querySelector('.nx-btn-remove').addEventListener('click', () => {
            if (rooms.children.length <= 1) return;
            tab.remove();
            card.remove();
            renumberRooms();
            activateRoom(rooms.children[0]);
        });

        renumberRooms();
        activateRoom(card);
        applyDisabledState();
    }

    function applyDisabledState() {
        const manager = isManager();
        rooms.querySelectorAll('input, select, textarea').forEach(el => { el.disabled = !manager; });
        rooms.querySelectorAll('[data-manager-required="true"]').forEach(el => { el.required = manager; });
    }

    function validateStep(i) {
        const step = steps[i];
        if (i === 0) {
            const pw = form.querySelector('#password');
            const confirm = form.querySelector('#password-confirm');
            confirm.setCustomValidity(pw.value !== confirm.value ? 'Passwords do not match.' : '');
        }
        const fields = [...step.querySelectorAll('input, select, textarea')].filter(el => !el.disabled);
        const bad = fields.filter(el => !el.checkValidity());
        fields.forEach(el => el.classList.toggle('is-invalid', bad.includes(el)));
        if (!bad.length) return true;

        const room = bad[0].closest('.nx-room');
        if (room) activateRoom(room);
        bad[0].reportValidity();
        return false;
    }

    nextBtn.addEventListener('click', () => {
        if (!validateStep(current)) return;
        if (current < lastIndex()) { show(current + 1); return; }
        for (let i = 0; i <= lastIndex(); i++) {
            if (!validateStep(i)) { show(i); validateStep(i); return; }
        }
        form.submit();
    });

    backBtn.addEventListener('click', () => show(current - 1));
    addRoomBtn.addEventListener('click', addRoom);

    form.addEventListener('keydown', e => {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && e.target.type !== 'file') {
            e.preventDefault();
            nextBtn.click();
        }
    });
    form.addEventListener('input', e => e.target.classList.remove('is-invalid'));
    form.querySelectorAll('input[name="account_type"]').forEach(r => r.addEventListener('change', () => {
        applyAccountType();
        applyDisabledState();
    }));

    addRoom();
    applyAccountType();
})();
