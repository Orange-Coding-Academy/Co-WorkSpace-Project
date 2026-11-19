
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>NEXORA AI Assistant</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6fb;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            color: #172033;
        }

        .chat-container {
            width: 100%;
            max-width: 950px;
            height: 85vh;
            min-height: 550px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 12px 45px rgba(0,0,0,.08);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background: #172554;
            color: white;
            padding: 22px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .bot-icon {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            background: #3b82f6;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 23px;
        }

        .header-info h2 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header-info p {
            font-size: 12px;
            color: #bfdbfe;
        }

        .clear-btn {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            color: white;
            padding: 10px 15px;
            border-radius: 9px;
            cursor: pointer;
        }

        .clear-btn:hover {
            background: rgba(255,255,255,.2);
        }

        .chat-messages {
            flex: 1;
            padding: 28px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .message {
            max-width: 78%;
            padding: 15px 18px;
            border-radius: 15px;
            line-height: 1.7;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            font-size: 14px;
        }

        .message.assistant {
            align-self: flex-start;
            background: #f1f5f9;
            border-bottom-left-radius: 4px;
        }

        .message.user {
            align-self: flex-end;
            background: #2563eb;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chat-footer {
            border-top: 1px solid #e5e7eb;
            padding: 20px;
        }

        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 9px;
            margin-bottom: 16px;
        }

        .suggestion {
            border: 1px solid #dbeafe;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 9px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
        }

        .suggestion:hover {
            background: #dbeafe;
        }

        .input-area {
            display: flex;
            gap: 12px;
        }

        .input-area input {
            flex: 1;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 15px;
            outline: none;
            font-size: 14px;
        }

        .input-area input:focus {
            border-color: #2563eb;
        }

        .send-btn {
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0 25px;
            cursor: pointer;
        }

        .send-btn:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .typing {
            color: #64748b;
            font-size: 13px;
            padding: 5px;
        }

        @media(max-width: 600px) {
            body {
                padding: 0;
            }

            .chat-container {
                height: 100vh;
                border-radius: 0;
            }

            .chat-messages {
                padding: 17px;
            }

            .message {
                max-width: 90%;
            }

            .chat-header {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

<div class="chat-container">

    <header class="chat-header">

        <div class="header-info">

            <div class="bot-icon">✦</div>

            <div>
                <h2>NEXORA AI Assistant</h2>
                <p>Your personal coworking assistant</p>
            </div>

        </div>

        <button class="clear-btn" id="clear-chat">
            Clear Chat
        </button>

    </header>

    <main class="chat-messages" id="chat-messages">

        <div class="message assistant">
           

            I'm your NEXORA AI Assistant.
            Ask me about workspaces, rooms, prices,
            your bookings, payments or memberships.
        </div>

    </main>

    <footer class="chat-footer">

        <div class="suggestions">

            <button class="suggestion">
                Show my bookings
            </button>

            <button class="suggestion">
                What rooms are available?
            </button>

            <button class="suggestion">
                Tell me about my membership
            </button>

            <button class="suggestion">
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
                required
            >

            <button type="submit" class="send-btn" id="send-btn">
                Send
            </button>

        </form>

    </footer>

</div>

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

</script>

</body>
</html>
