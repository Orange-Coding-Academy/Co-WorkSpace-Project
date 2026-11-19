
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('css/manager-ai.css') }}">
    <title>Manager AI | NEXORA</title>

</head>

<body>

<div class="container">

    <div class="header">
        <h1>Manager AI Assistant</h1>
        <p>Your intelligent assistant for managing your workspace.</p>
    </div>

    <div class="chat-box">

        <div class="messages" id="messages">
            <div class="message ai">
                Hello! I'm your NEXORA Manager AI.
                How can I help you today?
            </div>
        </div>

        <div class="input-area">
            <textarea
                id="question"
                rows="2"
                placeholder="Ask me anything..."
            ></textarea>

            <button id="sendBtn" onclick="sendQuestion()">
                Send
            </button>
        </div>

        <div class="suggestions">
            <button onclick="askSuggestion('Analyze my bookings.')">
                Analyze Bookings
            </button>

            <button onclick="askSuggestion('What are my peak booking days?')">
                Peak Days
            </button>

            <button onclick="askSuggestion('Give me marketing ideas.')">
                Marketing Ideas
            </button>
        </div>

    </div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="card mt-4">
    <div class="card-header">
        <h4>Marketing Assistant</h4>
    </div>

    <div class="card-body">

        <div class="mb-3">
            <label for="workspace_id" class="form-label">
                Select Workspace
            </label>

            <select id="workspace_id" class="form-control">
                <option value="">Choose Workspace</option>

                @foreach($workspaces as $workspace)
                    <option value="{{ $workspace->id }}">
                        {{ $workspace->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="marketing_request" class="form-label">
                What kind of advertisement do you want?
            </label>

            <textarea
                id="marketing_request"
                class="form-control"
                rows="3"
                placeholder="Write a request for AI..."
            ></textarea>
        </div>

        <button type="button" id="generateMarketingBtn" class="btn btn-primary">
            Generate Advertisement
        </button>

        <hr>

        <div class="mb-3">
            <label for="marketing_subject" class="form-label">
                Email Subject
            </label>

            <input
                type="text"
                id="marketing_subject"
                class="form-control"
                placeholder="Enter email subject"
            >
        </div>

        <div class="mb-3">
            <label for="marketing_content" class="form-label">
                Advertisement Content
            </label>

            <textarea
                id="marketing_content"
                class="form-control"
                rows="8"
                placeholder="AI generated advertisement will appear here..."
            ></textarea>
        </div>

        <button type="button" id="sendMarketingBtn" class="btn btn-success">
            Approve & Send Advertisement
        </button>

        <div id="marketingMessage" class="mt-3"></div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]'
    ).getAttribute('content');

    const workspaceSelect = document.getElementById('workspace_id');
    const requestInput = document.getElementById('marketing_request');
    const subjectInput = document.getElementById('marketing_subject');
    const contentInput = document.getElementById('marketing_content');
    const messageBox = document.getElementById('marketingMessage');

    // Generate advertisement using AI
    document.getElementById('generateMarketingBtn')
        .addEventListener('click', async function () {

            const workspaceId = workspaceSelect.value;
            const marketingRequest = requestInput.value.trim();

            if (!workspaceId || !marketingRequest) {
                messageBox.innerText =
                    'Please select a workspace and enter your request.';
                return;
            }

            this.disabled = true;
            this.innerText = 'Generating...';

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

                if (!subjectInput.value) {
                    subjectInput.value =
                        'Discover ' +
                        workspaceSelect.options[
                            workspaceSelect.selectedIndex
                        ].text;
                }

                messageBox.innerText =
                    'Advertisement generated. Review it before sending.';

            } catch (error) {
                messageBox.innerText = error.message;
            } finally {
                this.disabled = false;
                this.innerText = 'Generate Advertisement';
            }
        });


    // Send advertisement after manager approval
    document.getElementById('sendMarketingBtn')
        .addEventListener('click', async function () {

            const workspaceId = workspaceSelect.value;
            const subject = subjectInput.value.trim();
            const content = contentInput.value.trim();

            if (!workspaceId || !subject || !content) {
                messageBox.innerText =
                    'Please select a workspace and provide an email subject and advertisement.';
                return;
            }

            const approved = confirm(
                'Are you sure you want to send this advertisement to all eligible previous bookers?'
            );

            if (!approved) {
                return;
            }

            this.disabled = true;
            this.innerText = 'Sending...';

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

                messageBox.innerText =
                    'Campaign completed! Total recipients: ' +
                    data.total_recipients +
                    ' | Sent: ' + data.sent +
                    ' | Failed: ' + data.failed;

            } catch (error) {
                messageBox.innerText = error.message;
            } finally {
                this.disabled = false;
                this.innerText = 'Approve & Send Advertisement';
            }
        });

});
</script>
<script>
    const messages = document.getElementById('messages');
    const questionInput = document.getElementById('question');
    const sendBtn = document.getElementById('sendBtn');

    function addMessage(text, type) {
        const div = document.createElement('div');

        div.classList.add('message', type);
        div.textContent = text;

        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;

        return div;
    }

    function askSuggestion(text) {
        questionInput.value = text;
        sendQuestion();
    }

    async function sendQuestion() {
        const question = questionInput.value.trim();

        if (!question || sendBtn.disabled) return;

        addMessage(question, 'user');

        questionInput.value = '';
        sendBtn.disabled = true;

        const loading = addMessage('Thinking...', 'ai');

        try {
            const response = await fetch(
                "{{ route('manager.ai.ask') }}",
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN':
                            document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content
                    },

                    body: JSON.stringify({
                        question: question
                    })
                }
            );

            const data = await response.json();

            loading.remove();

            if (!response.ok || !data.success) {
                throw new Error(
                    data.message || 'Something went wrong.'
                );
            }

            addMessage(data.answer, 'ai');

        } catch (error) {
            loading.remove();
            addMessage(error.message, 'ai');

        } finally {
            sendBtn.disabled = false;
            questionInput.focus();
        }
    }

    
</script>

</body>
</html>
