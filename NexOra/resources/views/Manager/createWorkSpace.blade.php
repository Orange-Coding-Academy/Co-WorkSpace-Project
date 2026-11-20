<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create workspace | NexOra</title>

    <style>
        :root {
            --forest: #103b34;
            --cream: #f7f1e7;
            --paper: #fffdf8;
            --orange: #ff8750;
            --orange-hover: #ffa071;
            --ink: #18352d;
            --muted: #53665c;
            --line: #d8d8ca;
            --error: #a32c24;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font: 1rem/1.5 system-ui, sans-serif;
        }

        .topbar {
            background: var(--forest);
            color: var(--paper);
        }

        .topbar-inner, main {
            width: min(1060px, calc(100% - 32px));
            margin-inline: auto;
        }

        .topbar-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            min-height: 64px;
            padding-block: 12px;
        }

        .brand {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -.04em;
        }

        .back {
            color: inherit;
            font-size: .9rem;
            font-weight: 650;
            text-decoration: none;
        }

        .back:hover { text-decoration: underline; }

        main { padding-block: clamp(28px, 5vw, 64px); }

        .page-heading { margin-bottom: 24px; }

        h1, h2, p { margin: 0; }

        h1 {
            font-size: clamp(2.1rem, 5vw, 3.4rem);
            line-height: 1.05;
            letter-spacing: -.055em;
        }

        .page-heading p { margin-top: 10px; color: var(--muted); }

        .workspace-form {
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 22px;
            background: var(--paper);
            box-shadow: 0 12px 30px #103b340b;
        }

        .form-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        }

        .panel { padding: clamp(20px, 4vw, 32px); }
        .cover-panel { background: #f0f3eb; border-left: 1px solid var(--line); }

        h2 {
            margin-bottom: 18px;
            font-size: 1.15rem;
            letter-spacing: -.02em;
        }

        .field + .field { margin-top: 20px; }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: .92rem;
            font-weight: 700;
        }

        input[type="text"], textarea {
            width: 100%;
            min-height: 48px;
            padding: 10px 12px;
            border: 1px solid var(--line);
            border-radius: 10px;
            background: #fff;
            color: var(--ink);
            font: inherit;
        }

        input[aria-invalid="true"], textarea[aria-invalid="true"] { border-color: var(--error); }

        a:focus-visible, button:focus-visible, input:focus-visible, textarea:focus-visible {
            outline: 2px solid #276a59;
            outline-offset: 3px;
        }

        .preview {
            display: grid;
            place-items: center;
            aspect-ratio: 16 / 9;
            margin-bottom: 14px;
            overflow: hidden;
            border: 1px dashed #9fae9f;
            border-radius: 14px;
            background: #e3ebde;
        }

        .preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-placeholder {
            display: grid;
            justify-items: center;
            gap: 6px;
            padding: 16px;
            color: var(--muted);
            font-size: .9rem;
            text-align: center;
        }

        [hidden] { display: none !important; }

        input[type="file"] {
            width: 100%;
            color: var(--ink);
            font: inherit;
            font-size: .88rem;
        }

        input::file-selector-button {
            margin-right: 10px;
            padding: 8px 12px;
            border: 1px solid #a6b7a7;
            border-radius: 8px;
            background: var(--paper);
            color: var(--forest);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }

        input::file-selector-button:hover { background: #e3ebde; }

        .hint, .error {
            margin-top: 6px;
            font-size: .85rem;
        }

        .hint { color: var(--muted); }
        .error { color: var(--error); }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
            gap: 14px;
            padding: 16px clamp(20px, 4vw, 32px);
            border-top: 1px solid var(--line);
        }

        .cancel { color: var(--forest); }

        .submit {
            min-height: 46px;
            padding: 9px 18px;
            border: 1px solid #e97540;
            border-radius: 10px;
            background: var(--orange);
            color: #252016;
            font: inherit;
            font-weight: 800;
            cursor: pointer;
        }

        .submit:hover { background: var(--orange-hover); }

        @media (max-width: 680px) {
            .form-grid { grid-template-columns: 1fr; }

            .cover-panel {
                border-top: 1px solid var(--line);
                border-left: 0;
            }

            .submit { flex: 1; }
        }
        textarea { display: block; resize: vertical; }

        .optional { color: var(--muted); font-weight: 400; }

        .facilities { min-width: 0; margin-inline: 0; padding: 0; border: 0; }

        .facilities legend {
            margin-bottom: 8px;
            padding: 0;
            font-size: .92rem;
            font-weight: 700;
        }

        .facility-options {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .facility-options label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin: 0;
            padding: 7px 10px;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: .85rem;
            font-weight: 500;
            cursor: pointer;
        }

        .facility-options label:hover,
        .facility-options label:has(input:checked) { background: #e3ebde; }

        .facility-options input { margin: 0; accent-color: var(--forest); }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <span class="brand">NexOra</span>
            <a class="back" href="{{ url('/manager/workspaces') }}">&larr; My workspaces</a>
        </div>
    </header>

    <main>
        <header class="page-heading">
            <h1>Create workspace</h1>
            <p>Add your workspace details and a cover photo. You can add rooms after creating the workspace.</p>
        </header>

        <form class="workspace-form" method="POST"
              action="{{ url('/manager/workspaces') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <section class="panel" aria-labelledby="basics-title">
                    <h2 id="basics-title">Workspace basics</h2>

                    <div class="field">
                        <label for="name">Workspace name</label>
                        <input id="name" name="name" type="text"
                               value="{{ old('name') }}" placeholder="e.g. The Foundry"
                               maxlength="255" required
                               @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>
                        @error('name')
                            <p class="error" id="name-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="location">Location</label>
                        <input id="location" name="location" type="text"
                               value="{{ old('location') }}" placeholder="e.g. Abdali, Amman"
                               maxlength="255" required
                               @error('location') aria-invalid="true" aria-describedby="location-error" @enderror>
                        @error('location')
                            <p class="error" id="location-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="description">Description <span class="optional">(optional)</span></label>
                        <textarea id="description" name="description" rows="4"
                                  placeholder="Describe the space and what people can use it for."
                                  @error('description') aria-invalid="true" aria-describedby="description-error" @enderror>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="error" id="description-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <fieldset class="field facilities">
                        <legend>Facilities <span class="optional">(optional)</span></legend>
                        <div class="facility-options">
                            @foreach (['Wi-Fi', 'Meeting rooms', 'Private offices', 'Parking', 'Printing', 'Coffee bar', 'Air conditioning', 'Wheelchair access'] as $facility)
                                <label>
                                    <input type="checkbox" name="facilities[]" value="{{ $facility }}"
                                           @checked(in_array($facility, (array) old('facilities', []), true))>
                                    {{ $facility }}
                                </label>
                            @endforeach
                        </div>
                        @error('facilities')
                            <p class="error">{{ $message }}</p>
                        @enderror
                        @error('facilities.*')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </fieldset>
                </section>

                <section class="panel cover-panel" aria-labelledby="cover-title">
                    <h2 id="cover-title">Cover photo</h2>

                    <div class="preview">
                        <div class="preview-placeholder" id="cover-placeholder">
                            <span>No photo selected</span>
                        </div>
                        <img id="cover-preview" alt="Selected cover photo preview" hidden>
                    </div>

                    <label for="image">Choose a photo</label>
                    <input id="image" name="image" type="file"
                           accept="image/jpeg,image/png,image/webp" required
                           aria-describedby="image-hint @error('image') image-error @enderror"
                           @error('image') aria-invalid="true" @enderror>
                    <p class="hint" id="image-hint">JPG, PNG, or WebP. A landscape photo works best.</p>
                    @error('image')
                        <p class="error" id="image-error">{{ $message }}</p>
                    @enderror
                </section>
            </div>

            <footer class="form-footer">
                <a class="back cancel" href="{{ url('/manager/workspaces') }}">Cancel</a>
                <button class="submit" type="submit">
                    Create workspace <span aria-hidden="true">&rarr;</span>
                </button>
            </footer>
        </form>
    </main>

    <script>
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
    </script>
</body>
</html>