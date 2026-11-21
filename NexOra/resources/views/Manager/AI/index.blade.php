
@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title') Manager AI | NEXORA @endsection
@section('page-title') Manager AI @endsection

@push('styles')
    @include('layouts.dashboard.partials.app-compat-styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/manager-ai.css') }}">
@endpush

@section('content')
<div class="nx-page-content nx-manager-ai">

    <div class="nx-ai-container">

        {{-- Page Header --}}
        <header class="nx-ai-header">
            <div class="nx-ai-header-content">
                <div class="nx-ai-eyebrow">
                    <span class="nx-ai-eyebrow-dot"></span>
                    INTELLIGENT WORKSPACE
                </div>

                <h1>Manager AI</h1>

                <p>
                    Your intelligent workspace assistant.
                    Get insights, analyze bookings, and create smarter marketing campaigns.
                </p>
            </div>

            <div class="nx-ai-header-icon" aria-hidden="true">
                <div class="nx-ai-orbit nx-ai-orbit-one"></div>
                <div class="nx-ai-orbit nx-ai-orbit-two"></div>
                <div class="nx-ai-icon-core">
                    <span>✦</span>
                </div>
            </div>
        </header>


        {{-- AI Assistant --}}
        <section class="nx-ai-section">

            <div class="nx-section-heading">
                <div>
                    <span class="nx-section-kicker">YOUR AI ASSISTANT</span>
                    <h2>Let's work smarter.</h2>
                    <p>Ask questions and get insights about your workspace.</p>
                </div>

                <div class="nx-ai-online">
                    <span></span>
                    AI Assistant
                </div>
            </div>


            <div class="nx-chat-card">

                <div class="nx-chat-topbar">
                    <div class="nx-chat-profile">
                        <div class="nx-chat-avatar">
                            <span>✦</span>
                        </div>

                        <div>
                            <strong>NexOra Assistant</strong>
                            <span>Your intelligent workspace partner</span>
                        </div>
                    </div>

                    <div class="nx-chat-status">
                        <span></span>
                        Ready to help
                    </div>
                </div>


                <div class="nx-chat-messages" id="messages" aria-live="polite">

                    <div class="nx-message-row ai-row">
                        <div class="nx-message-avatar">✦</div>

                        <div class="nx-message-content">
                            <span class="nx-message-sender">NexOra AI</span>

                            <div class="nx-message ai">
                                Hello! I'm your NEXORA Manager AI.

                                <br><br>

                                I can help you understand your bookings,
                                discover important trends, and find ways
                                to improve your workspace.

                                <br><br>

                                What would you like to explore today?
                            </div>

                            <span class="nx-message-time">AI Assistant</span>
                        </div>
                    </div>

                </div>


                <div class="nx-chat-composer">

                    <div class="nx-chat-input-wrap">
                        <textarea
                            id="question"
                            rows="1"
                            placeholder="Ask anything about your workspace..."
                            aria-label="Ask the AI assistant"
                        ></textarea>

                        <button
                            type="button"
                            id="sendBtn"
                            class="nx-send-btn"
                            onclick="sendQuestion()"
                            aria-label="Send message"
                        >
                            <span>Send</span>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 12H19M19 12L12 5M19 12L12 19"
                                      stroke="currentColor"
                                      stroke-width="1.8"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>

                    <div class="nx-chat-hint">
                        <span>↵</span>
                        Press Enter to send
                    </div>

                </div>


                <div class="nx-chat-suggestions">
                    <div class="nx-suggestions-heading">
                        <span>TRY ASKING</span>
                        <span class="nx-suggestions-line"></span>
                    </div>

                    <div class="nx-suggestion-list">

                        <button
                            type="button"
                            class="nx-suggestion"
                            onclick="askSuggestion('Analyze my bookings.')"
                        >
                            <span class="nx-suggestion-icon">↗</span>
                            <span>Analyze Bookings</span>
                        </button>

                        <button
                            type="button"
                            class="nx-suggestion"
                            onclick="askSuggestion('What are my peak booking days?')"
                        >
                            <span class="nx-suggestion-icon">◷</span>
                            <span>Peak Booking Days</span>
                        </button>

                        <button
                            type="button"
                            class="nx-suggestion"
                            onclick="askSuggestion('Give me marketing ideas.')"
                        >
                            <span class="nx-suggestion-icon">✧</span>
                            <span>Marketing Ideas</span>
                        </button>

                    </div>
                </div>

            </div>
        </section>


        {{-- Marketing Studio --}}
        <section class="nx-marketing-section">

            <div class="nx-section-heading">
                <div>
                    <span class="nx-section-kicker">AI-POWERED MARKETING</span>
                    <h2>Marketing Studio</h2>
                    <p>Create engaging campaigns and reach your previous customers.</p>
                </div>

                <div class="nx-marketing-heading-icon">
                    ✧
                </div>
            </div>


            <div class="nx-marketing-card">

                <div class="nx-marketing-intro">
                    <div class="nx-marketing-intro-icon">✦</div>

                    <div>
                        <h3>Turn ideas into campaigns.</h3>
                        <p>
                            Tell AI what you want to promote.
                            Review the generated content before sending your campaign.
                        </p>
                    </div>
                </div>


                <div class="nx-marketing-grid">

                    {{-- Campaign Setup --}}
                    <div class="nx-marketing-setup">

                        <div class="nx-marketing-step">
                            <span>01</span>
                            <div>
                                <h4>Campaign setup</h4>
                                <p>Choose your workspace and describe your idea.</p>
                            </div>
                        </div>


                        <div class="nx-form-group">
                            <label for="workspace_id">
                                Select Workspace
                                <span class="nx-required">*</span>
                            </label>

                            <select id="workspace_id" class="form-select">
                                <option value="">Choose a workspace</option>

                                @foreach($workspaces as $workspace)
                                    <option value="{{ $workspace->id }}">
                                        {{ $workspace->name }}
                                    </option>
                                @endforeach
                            </select>

                            <span class="nx-field-hint">
                                Select the workspace you want to promote.
                            </span>
                        </div>


                        <div class="nx-form-group">
                            <label for="marketing_request">
                                Your Campaign Idea
                                <span class="nx-required">*</span>
                            </label>

                            <textarea
                                id="marketing_request"
                                class="form-control nx-marketing-request"
                                rows="5"
                                placeholder="Example: Create an attractive campaign promoting our meeting rooms with a special weekend offer..."
                            ></textarea>

                            <span class="nx-field-hint">
                                Be specific about your audience, offer, and campaign goal.
                            </span>
                        </div>


                        <button
                            type="button"
                            id="generateMarketingBtn"
                            class="nx-generate-btn"
                        >
                            <span class="nx-btn-sparkle">✦</span>
                            <span>Generate with AI</span>
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 12H19M19 12L12 5M19 12L12 19"
                                      stroke="currentColor"
                                      stroke-width="1.8"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </button>

                    </div>


                    {{-- Campaign Editor --}}
                    <div class="nx-marketing-editor">

                        <div class="nx-marketing-step">
                            <span>02</span>
                            <div>
                                <h4>Review your campaign</h4>
                                <p>Edit the AI-generated content before approval.</p>
                            </div>
                        </div>


                        <div class="nx-editor-panel">

                            <div class="nx-editor-toolbar">
                                <div class="nx-editor-label">
                                    <span class="nx-editor-dot"></span>
                                    CAMPAIGN CONTENT
                                </div>

                                <span class="nx-editor-badge">Editable</span>
                            </div>


                            <div class="nx-form-group">
                                <label for="marketing_subject">
                                    Email Subject
                                </label>

                                <input
                                    type="text"
                                    id="marketing_subject"
                                    class="form-control"
                                    placeholder="Enter your campaign subject..."
                                >
                            </div>


                            <div class="nx-form-group nx-content-group">
                                <label for="marketing_content">
                                    Advertisement Content
                                </label>

                                <textarea
                                    id="marketing_content"
                                    class="form-control nx-marketing-content"
                                    rows="10"
                                    placeholder="Your AI-generated advertisement will appear here. You can edit it before sending."
                                ></textarea>
                            </div>


                            <div class="nx-editor-footer">
                                <span>
                                    <span class="nx-edit-icon">✎</span>
                                    You can edit the content before sending.
                                </span>
                            </div>

                        </div>


                        <button
                            type="button"
                            id="sendMarketingBtn"
                            class="nx-approve-btn"
                        >
                            <span>✓</span>
                            Approve & Send Campaign
                        </button>

                    </div>

                </div>


                <div
                    id="marketingMessage"
                    class="nx-marketing-message"
                    role="status"
                    aria-live="polite"
                ></div>

            </div>
        </section>


        {{-- Footer Note --}}
        <div class="nx-ai-footer">
            <span>✦</span>
            Powered by NEXORA Intelligence
        </div>

    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    ).content;


    // =====================================================
    // Marketing Assistant
    // =====================================================

    const workspaceSelect = document.getElementById('workspace_id');
    const requestInput = document.getElementById('marketing_request');
    const subjectInput = document.getElementById('marketing_subject');
    const contentInput = document.getElementById('marketing_content');
    const messageBox = document.getElementById('marketingMessage');

    const generateBtn = document.getElementById('generateMarketingBtn');
    const sendMarketingBtn = document.getElementById('sendMarketingBtn');


    function showMarketingMessage(message, type = 'info') {
        messageBox.textContent = message;
        messageBox.className = 'nx-marketing-message is-visible ' + type;
    }


    generateBtn.addEventListener('click', async function () {

        const workspaceId = workspaceSelect.value;
        const marketingRequest = requestInput.value.trim();

        if (!workspaceId || !marketingRequest) {
            showMarketingMessage(
                'Please select a workspace and enter your campaign idea.',
                'error'
            );
            return;
        }

        this.disabled = true;
        this.innerHTML = '<span class="nx-loading-spinner"></span> Generating your campaign...';

        showMarketingMessage('AI is creating your advertisement. Please wait...', 'info');

        try {

            const response = await fetch(
                "{{ route('manager.ai.marketing') }}",
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        workspace_id: workspaceId,
                        request: marketingRequest
                    })
                }
            );

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'AI generation failed.');
            }

            contentInput.value = data.content;

            if (!subjectInput.value.trim()) {
                subjectInput.value =
                    'Discover ' +
                    workspaceSelect.options[
                        workspaceSelect.selectedIndex
                    ].text;
            }

            showMarketingMessage(
                'Your advertisement has been generated successfully. Review and edit it before sending.',
                'success'
            );

            contentInput.focus();

        } catch (error) {

            showMarketingMessage(error.message, 'error');

        } finally {

            this.disabled = false;
            this.innerHTML = '<span class="nx-btn-sparkle">✦</span><span>Generate with AI</span><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';

        }

    });


    // Send advertisement after manager approval
    sendMarketingBtn.addEventListener('click', async function () {

        const workspaceId = workspaceSelect.value;
        const subject = subjectInput.value.trim();
        const content = contentInput.value.trim();

        if (!workspaceId || !subject || !content) {
            showMarketingMessage(
                'Please select a workspace and provide an email subject and advertisement.',
                'error'
            );
            return;
        }

        const approved = confirm(
            'Are you sure you want to send this advertisement to all eligible previous bookers?'
        );

        if (!approved) {
            return;
        }

        this.disabled = true;
        this.innerHTML = '<span class="nx-loading-spinner"></span> Sending campaign...';

        showMarketingMessage('Your campaign is being sent. Please wait...', 'info');

        try {

            const response = await fetch(
                "{{ route('manager.ai.marketing.send') }}",
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        workspace_id: workspaceId,
                        subject: subject,
                        content: content
                    })
                }
            );

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Email sending failed.');
            }

            showMarketingMessage(
                'Campaign completed! Total recipients: ' +
                data.total_recipients +
                ' | Sent: ' + data.sent +
                ' | Failed: ' + data.failed,
                'success'
            );

        } catch (error) {

            showMarketingMessage(error.message, 'error');

        } finally {

            this.disabled = false;
            this.innerHTML = '<span>✓</span> Approve & Send Campaign';

        }

    });


    // =====================================================
    // AI Chat
    // =====================================================

    const messages = document.getElementById('messages');
    const questionInput = document.getElementById('question');
    const sendBtn = document.getElementById('sendBtn');


    function addMessage(text, type) {

        const row = document.createElement('div');
        row.classList.add(
            'nx-message-row',
            type === 'user' ? 'user-row' : 'ai-row'
        );

        if (type === 'ai') {

            const avatar = document.createElement('div');
            avatar.className = 'nx-message-avatar';
            avatar.textContent = '✦';
            row.appendChild(avatar);

        }

        const content = document.createElement('div');
        content.className = 'nx-message-content';

        const sender = document.createElement('span');
        sender.className = 'nx-message-sender';
        sender.textContent = type === 'user' ? 'You' : 'NexOra AI';

        const bubble = document.createElement('div');
        bubble.className = 'nx-message ' + type;
        bubble.textContent = text;

        content.appendChild(sender);
        content.appendChild(bubble);

        row.appendChild(content);
        messages.appendChild(row);

        messages.scrollTop = messages.scrollHeight;

        return row;
    }


    window.askSuggestion = function (text) {
        questionInput.value = text;
        sendQuestion();
    };


    window.sendQuestion = async function () {

        const question = questionInput.value.trim();

        if (!question || sendBtn.disabled) return;

        addMessage(question, 'user');

        questionInput.value = '';
        sendBtn.disabled = true;

        const loading = addMessage('Thinking...', 'ai');
        loading.classList.add('is-loading');

        try {

            const response = await fetch(
                "{{ route('manager.ai.ask') }}",
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        question: question
                    })
                }
            );

            const data = await response.json();

            loading.remove();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Something went wrong.');
            }

            addMessage(data.answer, 'ai');

        } catch (error) {

            loading.remove();
            addMessage(error.message, 'ai');

        } finally {

            sendBtn.disabled = false;
            questionInput.focus();

        }

    };


    // Enter to send, Shift + Enter for a new line
    questionInput.addEventListener('keydown', function (event) {

        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendQuestion();
        }

    });


});
</script>
@endpush
