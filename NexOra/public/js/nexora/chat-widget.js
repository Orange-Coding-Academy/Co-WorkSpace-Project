
document.addEventListener('DOMContentLoaded', () => {
    const widget = document.getElementById('nx-chat-widget');
    if (!widget) return;

    const launcher = document.getElementById('nx-chat-launcher');
    const panel = document.getElementById('nx-chat-panel');
    const close = document.getElementById('nx-chat-close');
    const clear = document.getElementById('nx-chat-clear');
    const form = document.getElementById('nx-chat-form');
    const input = document.getElementById('nx-chat-input');
    const send = document.getElementById('nx-chat-send');
    const messages = document.getElementById('nx-chat-messages');
    const csrf = widget.dataset.csrf;

    function addMessage(role, content) {
        const node = document.createElement('div');
        node.className = `nx-chat-message ${role}`;
        node.textContent = typeof content === 'string' ? content : '';
        messages.appendChild(node);
        messages.scrollTop = messages.scrollHeight;
    }

    async function readResponse(response, fallbackMessage) {
        const body = await response.text();
        let data;

        try {
            data = body ? JSON.parse(body) : {};
        } catch {
            if ([401, 419].includes(response.status)) {
                throw new Error('Your session has expired. Sign in again and retry.');
            }
            throw new Error(fallbackMessage);
        }

        if (!response.ok || !data.success) {
            throw new Error(data.message || fallbackMessage);
        }

        return data;
    }

    function openChat() {
        panel.hidden = false;
        launcher.setAttribute('aria-expanded', 'true');
        input.focus();
    }

    function closeChat() {
        panel.hidden = true;
        launcher.setAttribute('aria-expanded', 'false');
        launcher.focus({ preventScroll: true });
    }

    launcher.addEventListener('click', () => {
        panel.hidden ? openChat() : closeChat();
    });

    close.addEventListener('click', closeChat);

    try {
        const history = JSON.parse(widget.dataset.history || '[]');
        if (history.length) {
            messages.querySelector('[data-chat-welcome]')?.remove();
        }

        history.forEach(item => {
            if (
                ['user', 'assistant'].includes(item.role) &&
                typeof item.content === 'string'
            ) {
                addMessage(item.role, item.content);
            }
        });
    } catch (error) {
        console.error('Chat history error:', error);
    }

    form.addEventListener('submit', async event => {
        event.preventDefault();

        const message = input.value.trim();
        if (!message) return;

        addMessage('user', message);
        input.value = '';
        input.disabled = send.disabled = true;
        clear.disabled = true;
        widget.querySelectorAll('#nx-chat-suggestions button')
            .forEach(button => { button.disabled = true; });

        const typing = document.createElement('div');
        typing.className = 'nx-chat-typing';
        typing.setAttribute('role', 'status');
        typing.textContent = 'NEXORA AI is thinking...';
        messages.appendChild(typing);

        try {
            const response = await fetch(widget.dataset.sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ message })
            });

            const data = await readResponse(response, 'The assistant could not respond right now. Please try again.');
            addMessage(
                'assistant',
                typeof data.answer === 'string' ? data.answer : 'I could not create a response. Please try again.'
            );

        } catch (error) {
            addMessage('assistant', error.message || 'The assistant could not respond right now. Please try again.');

        } finally {
            typing.remove();
            input.disabled = send.disabled = false;
            clear.disabled = false;
            widget.querySelectorAll('#nx-chat-suggestions button')
                .forEach(button => { button.disabled = false; });
            input.focus();
        }
    });
clear.addEventListener('click', async () => {

    clear.disabled = true;

    try {
        const response = await fetch(widget.dataset.clearUrl, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            }
        });

        await readResponse(response, 'Could not clear conversation.');

        messages.replaceChildren();

        addMessage(
            'assistant',
            'Hello! Your conversation has been cleared. How can I help you?'
        );

    }catch (error) {
            addMessage('assistant', error.message || 'Could not clear conversation.');

        } finally {
            clear.disabled = false;
        }
    });

    document.querySelectorAll('#nx-chat-suggestions button')
        .forEach(button => {
            button.addEventListener('click', () => {
                input.value = button.textContent.trim();
                form.requestSubmit();
            });
        });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !panel.hidden) closeChat();
    });
});
