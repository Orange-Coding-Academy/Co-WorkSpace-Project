@extends('layouts.dashboard')

@section('dashboard-role', 'member')

@section('title') NEXORA AI Assistant @endsection
@section('page-title') NEXORA AI Assistant @endsection

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-7d81b7f3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/layouts-dashboard-partials-app-compat-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pages/member-chatbot.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
@endpush
@section('content')
<div class="nx-page-content nx-member-chatbot">
<div class="chat-container">

    <header class="chat-header">

        <div class="header-info">

           <div class="bot-icon">
    <span>✦</span>
</div>

<div>
    <h2>NEXORA AI Assistant</h2>
    <p>Your personal coworking assistant</p>
</div>
        <button type="button" class="clear-btn" id="clear-chat">
            Clear Chat
        </button>

    </header>

    <div data-nx-page-main class="chat-messages" id="chat-messages"
         role="log" aria-live="polite" aria-relevant="additions text">

        <div class="message assistant">I'm your NEXORA AI Assistant. Ask me about workspaces, rooms, prices, bookings, payments or memberships.</div>

    </div>

    <footer class="chat-footer">

        <div class="suggestions">

            <button type="button" class="suggestion">
                Show my bookings
            </button>

            <button type="button" class="suggestion">
                What rooms are available?
            </button>

            <button type="button" class="suggestion">
                Tell me about my membership
            </button>

            <button type="button" class="suggestion">
                Show my payments
            </button>

        </div>

        <form class="input-area" id="chat-form"
              data-send-url="{{ route('member.chatbot.send') }}"
              data-clear-url="{{ route('member.chatbot.clear') }}">

            <input
                type="text"
                id="message-input"
                placeholder="Ask NEXORA anything..."
                maxlength="2000"
                autocomplete="off"
                aria-label="Message NexOra AI"
                required
            >
<button type="submit" class="send-btn" id="send-btn">
    <span class="send-sparkle">✦</span>
    Send
</button>

        </form>

    </footer>

</div>
</div>
@endsection

@push('scripts')
<script>
    window.NEXORA_CHAT_HISTORY = @json(
        $history,
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_AMP |
        JSON_HEX_QUOT
    );
</script>
<script >

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('chat-form');
    const input = document.getElementById('message-input');
    const messages = document.getElementById('chat-messages');
    const sendButton = document.getElementById('send-btn');
    const clearButton = document.getElementById('clear-chat');

    const csrf = document.querySelector(
        'meta[name="csrf-token"]'
    )?.content || '';

    async function parseResponse(response) {
        const body = await response.text();
        let data;

        try {
            data = body ? JSON.parse(body) : {};
        } catch {
            if ([401, 419].includes(response.status)) {
                throw new Error('Your session has expired. Sign in again and retry.');
            }
            throw new Error('The assistant could not respond right now. Please try again.');
        }

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Unable to get a response.');
        }

        return data;
    }

    function addMessage(role, content) {

        const message = document.createElement('div');

        message.className = `message ${role}`;

        // Use textContent to prevent HTML injection
        message.textContent = typeof content === 'string' ? content : '';

        messages.appendChild(message);

        messages.scrollTop = messages.scrollHeight;
    }

    function showTyping() {

        const typing = document.createElement('div');

        typing.className = 'typing';
        typing.id = 'typing-indicator';
        typing.setAttribute('role', 'status');
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
    const history = Array.isArray(window.NEXORA_CHAT_HISTORY)
        ? window.NEXORA_CHAT_HISTORY
        : [];

    if (Array.isArray(history) && history.length) {
        messages.querySelector('.message.assistant')?.remove();
    }

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
        clearButton.disabled = true;
        document.querySelectorAll('.suggestion').forEach(function (button) {
            button.disabled = true;
        });

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

            const data = await parseResponse(response);

            removeTyping();
            addMessage(
                'assistant',
                typeof data.answer === 'string'
                    ? data.answer
                    : 'I could not create a response. Please try again.'
            );

        } catch (error) {

            removeTyping();

            addMessage(
                'assistant',
                error.message || 'Something went wrong. Please try again.'
            );

        } finally {

            sendButton.disabled = false;
            input.disabled = false;
            clearButton.disabled = false;
            document.querySelectorAll('.suggestion').forEach(function (button) {
                button.disabled = false;
            });
            input.focus();

        }

    });

    // Clear conversation
    clearButton.addEventListener('click', async function () {

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

            await parseResponse(response);

            messages.replaceChildren();

            addMessage(
                'assistant',
                'Hello! Your conversation has been cleared. How can I help you?'
            );

        } catch (error) {

            addMessage('assistant', error.message || 'Could not clear conversation.');

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

</script>
@endpush
