
<div class="nx-chat-widget" id="nx-chat-widget"
     data-send-url="{{ route('member.chatbot.send') }}"
     data-clear-url="{{ route('member.chatbot.clear') }}"
     data-csrf="{{ csrf_token() }}"
     data-history="{{ e(json_encode(session('nexora_chat_' . auth()->id(), []), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)) }}">

    <button class="nx-chat-launcher" id="nx-chat-launcher"
            type="button" aria-label="Open AI chat" aria-expanded="false">
        <span class="nx-chat-launcher-icon">✦</span>
        <span>Ask NexOra AI</span>
    </button>

    <section class="nx-chat-panel" id="nx-chat-panel"
             aria-label="NexOra AI Assistant" hidden>

        <header class="nx-chat-head">
            <div class="nx-chat-brand">
                <span class="nx-chat-brand-icon">✦</span>
                <div>
                    <strong>NEXORA AI</strong>
                    <small>Your personal coworking assistant</small>
                </div>
            </div>

            <div class="nx-chat-head-actions">
                <button type="button" id="nx-chat-clear">↻</button>
                <button type="button" id="nx-chat-close">×</button>
            </div>
        </header>

        <main class="nx-chat-messages" id="nx-chat-messages">
            <div class="nx-chat-message assistant">
                I'm your NEXORA AI Assistant. Ask me about workspaces,
                rooms, prices, bookings, payments or memberships.
            </div>
        </main>

        <div class="nx-chat-suggestions" id="nx-chat-suggestions">
            <button type="button">Show my bookings</button>
            <button type="button">What rooms are available?</button>
            <button type="button">Tell me about my membership</button>
        </div>

        <form class="nx-chat-form" id="nx-chat-form">
            <input type="text" id="nx-chat-input"
                   maxlength="2000"
                   placeholder="Ask NEXORA anything..."
                   autocomplete="off" required>

            <button type="submit" id="nx-chat-send">➤</button>
        </form>
    </section>
</div>

<script src="{{ asset('js/nexora/chat-widget.js') }}" defer></script>
