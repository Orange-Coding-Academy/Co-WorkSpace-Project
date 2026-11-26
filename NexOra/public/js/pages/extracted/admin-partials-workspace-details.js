
        for (const gallery of document.querySelectorAll('.room-media')) {
            const images = gallery.querySelectorAll('img');
            let current = 0;

            for (const button of gallery.querySelectorAll('[data-image-step]')) {
                button.addEventListener('click', () => {
                    images[current].hidden = true;
                    current = (current + Number(button.dataset.imageStep) + images.length) % images.length;
                    images[current].hidden = false;
                });
            }
        }
    