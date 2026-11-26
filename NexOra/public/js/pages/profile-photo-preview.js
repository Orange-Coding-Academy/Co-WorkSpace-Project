(() => {
    const input = document.querySelector('input[type="file"][name="avatar"]');
    if (!input) return;

    const targets = Array.from(document.querySelectorAll('[data-profile-avatar]'));
    if (!targets.length) return;

    const initialMarkup = targets.map((target) => target.innerHTML);
    let previewUrl = null;

    const info = input.closest('.pf-photo-info, .avatar-info, .form-group') || input.parentElement;
    let status = info?.querySelector('.profile-photo-preview-status');

    if (info && !status) {
        status = document.createElement('span');
        status.className = 'profile-photo-preview-status';
        status.setAttribute('aria-live', 'polite');
        info.append(status);
    }

    function restorePreviews() {
        targets.forEach((target, index) => {
            target.innerHTML = initialMarkup[index];
        });
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }
        if (status) status.textContent = '';
    }

    input.addEventListener('change', () => {
        const file = input.files?.[0];

        if (!file) {
            restorePreviews();
            return;
        }

        if (!file.type.startsWith('image/')) {
            input.value = '';
            restorePreviews();
            if (status) status.textContent = 'Choose an image file to preview.';
            return;
        }

        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = URL.createObjectURL(file);

        targets.forEach((target) => {
            const image = document.createElement('img');
            image.src = previewUrl;
            image.alt = 'Selected profile photo preview';
            image.className = 'profile-photo-preview';
            target.replaceChildren(image);
        });

        if (status) status.textContent = `Previewing ${file.name}. Save changes to update your profile.`;
    });

    input.form?.addEventListener('reset', () => {
        window.setTimeout(restorePreviews, 0);
    });

    window.addEventListener('pagehide', () => {
        if (previewUrl) URL.revokeObjectURL(previewUrl);
    }, { once: true });
})();
