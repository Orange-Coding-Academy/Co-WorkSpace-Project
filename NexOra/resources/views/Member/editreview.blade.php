
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review | NexOra</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f5f8fa;
            color: #243746;
            min-height: 100vh;
        }

        .container {
            max-width: 780px;
            margin: 60px auto;
            padding: 0 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #119e95;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 28px;
        }

        .back-link:hover {
            color: #087d76;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-description {
            color: #7a8994;
            font-size: 15px;
            line-height: 1.7;
        }

        .review-card {
            background: #ffffff;
            border: 1px solid #e8eef0;
            border-radius: 18px;
            padding: 38px;
            box-shadow: 0 8px 30px rgba(20, 50, 60, 0.04);
        }

        .card-heading {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .card-description {
            color: #87949e;
            font-size: 14px;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 28px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #344653;
            margin-bottom: 12px;
        }

        .required {
            color: #119e95;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #dce5e9;
            border-radius: 10px;
            background: #ffffff;
            color: #344653;
            font-family: inherit;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #119e95;
            box-shadow: 0 0 0 3px rgba(17, 158, 149, 0.1);
        }

        .form-control:disabled {
            background: #f4f7f8;
            color: #60717d;
            cursor: not-allowed;
        }

        .form-hint {
            display: block;
            font-size: 12px;
            color: #92a0aa;
            margin-top: 9px;
            line-height: 1.6;
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
            line-height: 1.7;
        }

        /* Star Rating */
        .rating-options {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 7px;
            width: fit-content;
        }

        .rating-options input {
            position: absolute;
            opacity: 0;
            width: 1px;
            height: 1px;
        }

        .rating-options label {
            font-size: 38px;
            color: #d9e1e5;
            cursor: pointer;
            line-height: 1;
            transition: color 0.2s, transform 0.2s;
        }

        .rating-options label:hover {
            transform: scale(1.1);
        }

        .rating-options label:hover,
        .rating-options label:hover ~ label,
        .rating-options input:checked ~ label {
            color: #f4b942;
        }

        .rating-options input:focus-visible + label {
            outline: 2px solid #119e95;
            outline-offset: 3px;
            border-radius: 4px;
        }

        .divider {
            height: 1px;
            background: #edf1f3;
            margin: 30px 0;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 13px 24px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            text-decoration: none;
            cursor: pointer;
            transition: 0.2s;
            border: none;
        }

        .btn-secondary {
            background: #f1f5f6;
            color: #52636e;
        }

        .btn-secondary:hover {
            background: #e5edef;
        }

        .btn-primary {
            background: #119e95;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #0d827b;
        }

        .alert-error {
            background: #fff1f1;
            border: 1px solid #f5cccc;
            border-radius: 10px;
            color: #b42332;
            padding: 15px 18px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .alert-error ul {
            padding-left: 20px;
        }

        .alert-error li {
            margin: 5px 0;
        }

        @media (max-width: 600px) {
            .container {
                margin: 30px auto;
                padding: 0 15px;
            }

            .page-title {
                font-size: 26px;
            }

            .review-card {
                padding: 24px 20px;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
            }

            .rating-options label {
                font-size: 34px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <a href="{{ route('member.reviews.index') }}" class="back-link">
            ← Back to My Reviews
        </a>

        <div class="page-header">
            <h1 class="page-title">Edit Review</h1>
            <p class="page-description">
                Update your rating and share your latest experience
                with this workspace.
            </p>
        </div>

        <div class="review-card">

            <h2 class="card-heading">Update Your Review</h2>

            <p class="card-description">
                Make changes to your review below.
                Your feedback helps us improve the workspace experience.
            </p>

            @if ($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('member.reviews.update', $review->id) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <!-- Workspace -->
                <div class="form-group">
                    <label for="workspace" class="form-label">
                        Workspace
                    </label>

                    <input
                        type="text"
                        id="workspace"
                        class="form-control"
                        value="{{ $review->workspace->name }}"
                        disabled
                    >

                    <span class="form-hint">
                        You cannot change the workspace of an existing review.
                    </span>
                </div>

                <!-- Rating -->
                <div class="form-group">

                    <label class="form-label">
                        Your Rating <span class="required">*</span>
                    </label>

                    <div class="rating-options">

                        <input
                            type="radio"
                            name="rating"
                            id="star5"
                            value="5"
                            {{ old('rating', $review->rating) == 5 ? 'checked' : '' }}
                            required
                        >
                        <label for="star5" title="5 Stars">★</label>

                        <input
                            type="radio"
                            name="rating"
                            id="star4"
                            value="4"
                            {{ old('rating', $review->rating) == 4 ? 'checked' : '' }}
                        >
                        <label for="star4" title="4 Stars">★</label>

                        <input
                            type="radio"
                            name="rating"
                            id="star3"
                            value="3"
                            {{ old('rating', $review->rating) == 3 ? 'checked' : '' }}
                        >
                        <label for="star3" title="3 Stars">★</label>

                        <input
                            type="radio"
                            name="rating"
                            id="star2"
                            value="2"
                            {{ old('rating', $review->rating) == 2 ? 'checked' : '' }}
                        >
                        <label for="star2" title="2 Stars">★</label>

                        <input
                            type="radio"
                            name="rating"
                            id="star1"
                            value="1"
                            {{ old('rating', $review->rating) == 1 ? 'checked' : '' }}
                        >
                        <label for="star1" title="1 Star">★</label>

                    </div>

                    <span class="form-hint">
                        Choose a rating between 1 and 5 stars.
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
                        placeholder="Tell us about your experience..."
                    >{{ old('comment', $review->comment) }}</textarea>

                    <span class="form-hint">
                        Share what you liked or what could be improved.
                    </span>

                </div>

                <div class="divider"></div>

                <!-- Actions -->
                <div class="form-actions">

                    <a
                        href="{{ route('member.reviews.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>

                </div>

            </form>

        </div>

    </div>

</body>
</html>
