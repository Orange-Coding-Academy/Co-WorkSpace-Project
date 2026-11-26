
    const reviewModal = document.getElementById('reviewModal');

    reviewModal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;
        const url = button.getAttribute('data-url');
        const content = document.getElementById('reviewModalContent');

        content.innerHTML = `
            <div class="modal-body text-center p-5">
                Loading review...
            </div>
        `;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Could not load review.');
                }

                return response.text();
            })
            .then(html => {
                content.innerHTML = html;
            })
            .catch(() => {
                content.innerHTML = `
                    <div class="modal-body text-center p-5 text-danger">
                        Failed to load review details.
                    </div>
                `;
            });

    });
