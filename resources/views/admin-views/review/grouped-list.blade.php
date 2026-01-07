@extends('layouts.dashboard-main')

@section('content')
<div class="container-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Grouped Review List</h4>
                        <small class="text-muted">Reviews grouped by order with both restaurant and deliveryman ratings</small>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Controls -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.reviews.grouped-list') }}" class="d-inline-flex">
                                <input type="text" name="search" class="form-control me-2" placeholder="Search by customer name, order ID, or review text..." value="{{ $search }}" style="width: 300px;">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                @if($search)
                                <a href="{{ route('admin.reviews.grouped-list') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                                @endif
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <form method="GET" action="{{ route('admin.reviews.grouped-list') }}" class="d-inline-flex">
                                @if($search)
                                    <input type="hidden" name="search" value="{{ $search }}">
                                @endif
                                <label class="form-label me-2 mt-2">Show:</label>
                                <select name="per_page" class="form-select" style="width: auto;" onchange="this.form.submit()">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="grouped-review-table" class="table" role="grid">
                            <thead>
                                <tr class="light">
                                    <th>S.No.</th>
                                    <th>Order Details</th>
                                    <th>Customer</th>
                                    <th>Restaurant Review</th>
                                    <th>Deliveryman Review</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupedReviews as $index => $reviewGroup)
                                <tr>
                                    <td class="text-center">{{ ($pagination->currentPage() - 1) * $pagination->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <div>
                                            <strong>Order  <a href="{{route('admin.order.details', $reviewGroup['order_id'])}}">#{{ $reviewGroup['order_id'] }}</a></strong><br>
                                            <small class="text-muted">{{ \App\CentralLogics\Helpers::timeAgo($reviewGroup['created_at']) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($reviewGroup['customer'])
                                        <a href="{{ route('admin.customer.view', ['id' => $reviewGroup['customer']->id]) }}" class="text-decoration-none">
                                            {{ ucfirst($reviewGroup['customer']->f_name ?? 'Unknown') }} {{ ucfirst($reviewGroup['customer']->l_name ?? '') }}
                                        </a>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($reviewGroup['restaurant_review'])
                                        <div class="review-container">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="badge bg-success text-white me-2">
                                                    {{ $reviewGroup['restaurant_review']->rating }} <i class="fas fa-star ms-1"></i>
                                                </div>
                                                <small class="text-muted">{{ $reviewGroup['restaurant_review']->restaurant->name ?? 'N/A' }}</small>
                                            </div>
                                            <p class="mb-0 text-dark">{{ $reviewGroup['restaurant_review']->review }}</p>
                                            <button class="btn btn-sm btn-outline-primary mt-2 edit-review-btn" 
                                                    data-review-id="{{ $reviewGroup['restaurant_review']->id }}"
                                                    data-review-type="restaurant"
                                                    data-rating="{{ $reviewGroup['restaurant_review']->rating }}"
                                                    data-review-text="{{ $reviewGroup['restaurant_review']->review }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </div>
                                        @else
                                        <span class="text-muted">No restaurant review</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($reviewGroup['deliveryman_review'])
                                        <div class="review-container">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="badge bg-info text-white me-2">
                                                    {{ $reviewGroup['deliveryman_review']->rating }} <i class="fas fa-star ms-1"></i>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $reviewGroup['deliveryman_review']->deliveryman ? 
                                                       ucfirst($reviewGroup['deliveryman_review']->deliveryman->f_name) . ' ' . ucfirst($reviewGroup['deliveryman_review']->deliveryman->l_name) : 
                                                       'N/A' }}
                                                </small>
                                            </div>
                                            <p class="mb-0 text-dark">{{ $reviewGroup['deliveryman_review']->review }}</p>
                                            <button class="btn btn-sm btn-outline-info mt-2 edit-review-btn" 
                                                    data-review-id="{{ $reviewGroup['deliveryman_review']->id }}"
                                                    data-review-type="deliveryman"
                                                    data-rating="{{ $reviewGroup['deliveryman_review']->rating }}"
                                                    data-review-text="{{ $reviewGroup['deliveryman_review']->review }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </div>
                                        @else
                                        <span class="text-muted">No deliveryman review</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.order.details', $reviewGroup['order_id']) }}" 
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-eye"></i> View Order
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Controls -->
                    @if($pagination->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                            <div>
                                <small class="text-muted">
                                    Showing {{ $pagination->firstItem() }} to {{ $pagination->lastItem() }} of {{ $pagination->total() }} entries
                                </small>
                            </div>
                            <div>
                                {{ $pagination->appends(['search' => $search, 'per_page' => $perPage])->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Review Modal -->
<div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReviewModalLabel">Edit Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editReviewForm">
                <div class="modal-body">
                    <input type="hidden" id="reviewId" name="review_id">
                    <input type="hidden" id="reviewType" name="review_type">
                    
                    <div class="mb-3">
                        <label for="editRating" class="form-label">Rating <span class="text-danger">*</span></label>
                        <select class="form-select" id="editRating" name="rating" required>
                            <option value="">Select Rating</option>
                            <option value="1">1 Star - Poor</option>
                            <option value="2">2 Stars - Fair</option>
                            <option value="3">3 Stars - Good</option>
                            <option value="4">4 Stars - Very Good</option>
                            <option value="5">5 Stars - Excellent</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editReviewText" class="form-label">Review <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="editReviewText" name="review" rows="4" maxlength="500" required 
                                  placeholder="Enter review text..."></textarea>
                        <div class="d-flex justify-content-between">
                            <small class="form-text text-muted">Maximum 500 characters</small>
                            <small class="form-text text-muted">
                                <span id="charCount">0</span>/500
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('javascript')
<script>
$(document).ready(function() {
    // Initialize DataTable with minimal features since we have server-side pagination
    $('#grouped-review-table').DataTable({
        responsive: true,
        ordering: false, // Disable ordering since we handle it server-side
        searching: false, // Disable search since we have custom search
        paging: false, // Disable paging since we use custom pagination
        info: false, // Disable info since we show custom info
        lengthChange: false // Disable length change since we have custom controls
    });

    // Character counter
    $('#editReviewText').on('input', function() {
        const length = $(this).val().length;
        $('#charCount').text(length);
        
        if (length > 450) {
            $('#charCount').addClass('text-warning');
        } else {
            $('#charCount').removeClass('text-warning');
        }
        
        if (length >= 500) {
            $('#charCount').addClass('text-danger').removeClass('text-warning');
        } else {
            $('#charCount').removeClass('text-danger');
        }
    });

    // Edit review button click
    $('.edit-review-btn').on('click', function() {
        const reviewId = $(this).data('review-id');
        const reviewType = $(this).data('review-type');
        const rating = $(this).data('rating');
        const reviewText = $(this).data('review-text');
        
        $('#reviewId').val(reviewId);
        $('#reviewType').val(reviewType);
        $('#editRating').val(rating);
        $('#editReviewText').val(reviewText);
        
        // Update character counter
        $('#charCount').text(reviewText.length);
        
        $('#editReviewModalLabel').text(`Edit ${reviewType.charAt(0).toUpperCase() + reviewType.slice(1)} Review`);
        $('#editReviewModal').modal('show');
    });

    // Handle form submission
    $('#editReviewForm').on('submit', function(e) {
        e.preventDefault();
        
        // Disable submit button to prevent double submission
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).text('Updating...');
        
        const formData = {
            review_id: $('#reviewId').val(),
            rating: $('#editRating').val(),
            review: $('#editReviewText').val(),
            _token: '{{ csrf_token() }}'
        };
        
        const reviewType = $('#reviewType').val();
        const url = reviewType === 'restaurant' ? 
                   '{{ route("admin.reviews.update-restaurant") }}' : 
                   '{{ route("admin.reviews.update-deliveryman") }}';
        
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            beforeSend: function() {
                // Clear any previous error messages
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#editReviewModal').modal('hide');
                    // Instead of full reload, you could update the specific row
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Failed to update review');
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred while updating the review';
                
                if (xhr.status === 422) {
                    // Validation errors
                    const response = xhr.responseJSON;
                    if (response && response.errors) {
                        Object.keys(response.errors).forEach(function(field) {
                            const fieldElement = $(`[name="${field}"]`);
                            fieldElement.addClass('is-invalid');
                            fieldElement.after(`<div class="invalid-feedback">${response.errors[field][0]}</div>`);
                        });
                        errorMessage = 'Please correct the validation errors';
                    }
                } else if (xhr.status === 403) {
                    errorMessage = 'You do not have permission to perform this action';
                } else if (xhr.status === 404) {
                    errorMessage = 'Review not found';
                } else if (xhr.status >= 500) {
                    errorMessage = 'Server error occurred. Please try again later';
                }
                
                toastr.error(errorMessage);
            },
            complete: function() {
                // Re-enable submit button
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.review-container {
    max-width: 250px;
}

.review-container p {
    font-size: 0.9rem;
    line-height: 1.4;
    max-height: 60px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
}

.badge {
    font-size: 0.8rem;
}

.table td {
    vertical-align: middle;
}

#grouped-review-table_wrapper .dataTables_length,
#grouped-review-table_wrapper .dataTables_filter {
    margin-bottom: 1rem;
}
</style>
@endpush
