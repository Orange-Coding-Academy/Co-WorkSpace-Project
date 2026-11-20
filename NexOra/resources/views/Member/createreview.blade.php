
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write a Review | NexOra</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7f9;
            color: #253444;
        }

        .container {
            max-width: 850px;
            margin: 55px auto;
            padding: 0 20px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: #119e95;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .page-title {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-description {
            color: #7b8794;
            font-size: 15px;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .review-card {
            background: #ffffff;
            border: 1px solid #e8edf1;
            border-radius: 14px;
            padding: 35px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        }

        .card-title {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .card-description {
            color: #7b8794;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #dce4e8;
            border-radius: 8px;
            background: #ffffff;
            font-size: 14px;
            color: #253444;
            outline: none;
        }

        .form-control:focus {
            border-color: #119e95;
        }

        textarea.form-control {
            min-height: 140px;
            resize: vertical;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .form-hint {
            display: block;
            color: #8b96a3;
            font-size: 12px;
            margin-top: 8px;
        }

        .rating-options {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 8px;
        }

        .rating-options input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .rating-options label {
            font-size: 36px;
            color: #d8dfe4;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-options label:hover,
        .rating-options label:hover ~ label,
        .rating-options input:checked ~ label {
            color: #f4b740;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #edf0f2;
        }

        .btn {
            display: inline-block;
            padding: 12px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: #119e95;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #0d8981;
        }

        .btn-secondary {
            background-color: #f1f4f6;
            color: #536271;
        }

        .btn-secondary:hover {
            background-color: #e5ebee;
        }

        @media (max-width: 600px) {
            .container {
                margin: 30px auto;
            }

            .review-card {
                padding: 22px;
            }

            .page-title {
                font-size: 25px;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn {
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <a href="{{ route('member.reviews.index') }}" class="back-link">
            ← Back to My Reviews
        </a>

        <h1 class="page-title">Write a Review</h1>

        <p class="page-description">
            Share your experience and help others discover
            the perfect workspace.
        </p>

        <div class="review-card">

            <h2 class="card-title">Your Review</h2>

            <p class="card-description">
                Tell us about your experience at the workspace.
            </p>

            @if ($errors->any())
                <div style="color: #dc3545; margin-bottom: 20px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('member.reviews.store') }}" method="POST">
                @csrf

                <!-- Workspace -->
                <div class="form-group">

                    <label for="workspace_id" class="form-label">
                        Select Workspace *
                    </label>

                    <select
                        name="workspace_id"
                        id="workspace_id"
                        class="form-control"
                        required
                    >
                        <option value="">Choose a workspace</option>

                        @foreach($workspaces as $workspace)
                            <option
                                value="{{ $workspace->id }}"
                                {{ old('workspace_id') == $workspace->id ? 'selected' : '' }}
                            >
                                {{ $workspace->name }}
                            </option>
                        @endforeach

                    </select>

                    <span class="form-hint">
                        Only workspaces with completed bookings
                        can be reviewed.
                    </span>

                </div>

                <!-- Rating -->
                <div class="form-group">

                    <label class="form-label">
                        Your Rating *
                    </label>

                    <div class="rating-options">

                        <input type="radio" name="rating" id="star5" value="5"
                            {{ old('rating') == 5 ? 'checked' : '' }}>
                        <label for="star5">★</label>

                        <input type="radio" name="rating" id="star4" value="4"
                            {{ old('rating') == 4 ? 'checked' : '' }}>
                        <label for="star4">★</label>

                        <input type="radio" name="rating" id="star3" value="3"
                            {{ old('rating') == 3 ? 'checked' : '' }}>
                        <label for="star3">★</label>

                        <input type="radio" name="rating" id="star2" value="2"
                            {{ old('rating') == 2 ? 'checked' : '' }}>
                        <label for="star2">★</label>

                        <input type="radio" name="rating" id="star1" value="1"
                            {{ old('rating') == 1 ? 'checked' : '' }}>
                        <label for="star1">★</label>

                    </div>

                    <span class="form-hint">
                        Select a rating from 1 to 5 stars.
                    </span>

                </div>

                <!-- Comment -->
                <div class="form-group">

                    <label for="comment" class="form-label">
                        Your Comment (Optional)
                    </label>

                    <textarea
                        name="comment"
                        id="comment"
                        class="form-control"
                        placeholder="Tell us what you liked about this workspace..."
                    >{{ old('comment') }}</textarea>

                    <span class="form-hint">
                        Share your thoughts about the environment,
                        facilities, and overall experience.
                    </span>

                </div>

                <!-- Buttons -->
                <div class="form-actions">

                    <a href="{{ route('member.reviews.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Submit Review
                    </button>

                </div>

            </form>

        </div>

    </div>

</body>

</html>
