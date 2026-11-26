

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('chat-form');
    const input = document.getElementById('message-input');
    const messages = document.getElementById('chat-messages');
    const sendButton = document.getElementById('send-btn');
    const clearButton = document.getElementById('clear-chat');

    const csrf = document.querySelector(
        'meta[name="csrf-token"]'
    ).content;

    function addMessage(role, content) {

        const message = document.createElement('div');

        message.className = `message ${role}`;

        // Use textContent to prevent HTML injection
        message.textContent = content;

        messages.appendChild(message);

        messages.scrollTop = messages.scrollHeight;
    }

    function showTyping() {

        const typing = document.createElement('div');

        typing.className = 'typing';
        typing.id = 'typing-indicator';
        typing.textContent = 'NEXORA AI is thinking...';

        messages.appendChild(typing);

        messages.scrollTop = messages.scrollHeight;
    }

    function removeTyping() {

        const typing = document.getElementById(
            'typing-indicator'
        );

        if (typing) {
            typing.remove();
        }
    }

    // Restore previous session messages
    const history = window.NEXORA_CHAT_HISTORY || [];

    history.forEach(function (item) {

        if (
            ['user', 'assistant'].includes(item.role) &&
            typeof item.content === 'string'
        ) {
            addMessage(item.role, item.content);
        }

    });

    // Send message
    form.addEventListener('submit', async function (event) {

        event.preventDefault();

        const message = input.value.trim();

        if (!message) {
            return;
        }

        addMessage('user', message);

        input.value = '';

        sendButton.disabled = true;
        input.disabled = true;

        showTyping();

        try {

            const response = await fetch(
                form.dataset.sendUrl,
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },

                    body: JSON.stringify({
                        message: message
                    })
                }
            );

            const data = await response.json();

            removeTyping();

            if (!response.ok || !data.success) {

                throw new Error(
                    data.message || 'Unable to get a response.'
                );
            }

            addMessage('assistant', data.answer);

        } catch (error) {

            removeTyping();

            addMessage(
                'assistant',
                error.message || 'Something went wrong. Please try again.'
            );

        } finally {

            sendButton.disabled = false;
            input.disabled = false;
            input.focus();

        }

    });

    // Clear conversation
    clearButton.addEventListener('click', async function () {

        const confirmed = confirm(
            'Are you sure you want to clear this conversation?'
        );

        if (!confirmed) {
            return;
        }

        clearButton.disabled = true;

        try {

            const response = await fetch(
                form.dataset.clearUrl,
                {
                    method: 'DELETE',

                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    }
                }
            );

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error('Could not clear conversation.');
            }

            messages.innerHTML = '';

            addMessage(
                'assistant',
                'Hello! Your conversation has been cleared. How can I help you?'
            );

        } catch (error) {

            alert(error.message);

        } finally {

            clearButton.disabled = false;

        }

    });

    // Suggested questions
    document.querySelectorAll('.suggestion').forEach(function (button) {

        button.addEventListener('click', function () {

            input.value = button.textContent.trim();

            form.requestSubmit();

        });

    });

});

