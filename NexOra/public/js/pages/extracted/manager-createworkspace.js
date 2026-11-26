
        const input = document.querySelector('#image');
        const preview = document.querySelector('#cover-preview');
        const placeholder = document.querySelector('#cover-placeholder');
        let previewUrl;

        input.addEventListener('change', () => {
            if (previewUrl) URL.revokeObjectURL(previewUrl);

            const file = input.files[0];
            preview.hidden = true;
            placeholder.hidden = false;
            preview.removeAttribute('src');

            if (!file) return;

            previewUrl = URL.createObjectURL(file);
            preview.src = previewUrl;
        });

        preview.addEventListener('load', () => {
            preview.hidden = false;
            placeholder.hidden = true;
        });
    