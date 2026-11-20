<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manager Reviews</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6fa;
            color: #172033;
            font-family: Arial, sans-serif;
        }

        .page {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .card-box {
            background: white;
            border: 1px solid #e8edf4;
            border-radius: 12px;
        }

        .table th {
            color: #778195;
            font-size: 12px;
            text-transform: uppercase;
            background: #f8f9fc;
            white-space: nowrap;
        }

        .table td,
        .table th {
            padding: 16px;
            vertical-align: middle;
        }

        .rating {
            color: #e9a526;
            font-weight: bold;
            white-space: nowrap;
        }

        .view-btn {
            background: #3968ac;
            color: white;
            padding: 7px 14px;
            border-radius: 7px;
            border: none;
            font-size: 13px;
        }

        .view-btn:hover {
            background: #274778;
        }

        .badge-visible {
            background: #e5f6ed;
            color: #168354;
        }

        .badge-hidden {
            background: #fff2d9;
            color: #966514;
        }

        .modal-content {
            border: none;
            border-radius: 14px;
        }

        .modal-header,
        .modal-footer {
            padding: 20px 24px;
        }

        @media (max-width: 768px) {
            .page {
                margin: 25px auto;
            }
        }
    </style>
</head>

<body>

<div class="page">

    <div class="mb-4">
        <h2 class="fw-bold">Customer Reviews</h2>
        <p class="text-secondary">
            View feedback submitted by customers.
        </p>
    </div>

    <div class="card-box p-4 mb-4">
        <small class="text-secondary">Total Reviews</small>
        <h2 class="fw-bold mb-0">{{ $reviews->total() }}</h2>
    </div>

    <div class="card-box overflow-hidden">

        <div class="p-4 border-bottom">
            <h5 class="fw-bold mb-0">All Reviews</h5>
        </div>

        @if($reviews->count())

            <div class="table-responsive">
                <table class="table table-hover mb-0">

                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Visibility</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($reviews as $review)

                            <tr>
                                <td class="fw-semibold">
                                    {{ $review->user->name ?? 'Unknown' }}
                                </td>

                                <td>
                                    <span class="rating">
                                        ★ {{ $review->rating }}/5
                                    </span>
                                </td>

                                <td>
                                    {{ \Illuminate\Support\Str::limit($review->comment ?? 'No comment', 45) }}
                                </td>

                                <td>
                                    @if($review->is_visible)
                                        <span class="badge badge-visible">
                                            Visible
                                        </span>
                                    @else
                                        <span class="badge badge-hidden">
                                            Hidden
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $review->created_at->format('d M Y') }}
                                </td>

                                <td>
                                    <button
                                        type="button"
                                        class="view-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#reviewModal"
                                        data-url="{{ route('reviews.show', $review->id) }}">

                                        View

                                    </button>
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>

            <div class="p-3">
                {{ $reviews->links() }}
            </div>

        @else

            <div class="text-center p-5">
                <h5>No reviews found</h5>
                <p class="text-secondary mb-0">
                    There are currently no reviews.
                </p>
            </div>

        @endif

    </div>

</div>


{{-- Review Details Modal --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content" id="reviewModalContent">

            <div class="modal-body text-center p-5">
                Loading review...
            </div>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const reviewModal = document.getElementById('reviewModal');

    reviewModal.addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;
        const url = button.getAttribute('data-url');
        const content = document.getElementById('reviewModalContent');

        content.innerHTML = `
            <div class="modal-body text-center p-5">
                Loading review...
            </div>
        `;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Could not load review.');
                }

                return response.text();
            })
            .then(html => {
                content.innerHTML = html;
            })
            .catch(() => {
                content.innerHTML = `
                    <div class="modal-body text-center p-5 text-danger">
                        Failed to load review details.
                    </div>
                `;
            });

    });
</script>

</body>
</html>