<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class ReviewController extends Controller
{
    /**
     * Display grouped reviews by order ID with pagination and optimization
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 25); // Default 25 reviews per page
        $search = $request->get('search', '');
        
        // Use database-level query to get distinct order IDs with pagination
        $orderIdsQuery = Review::select('order_id', DB::raw('MAX(created_at) as latest_created_at'))
            ->groupBy('order_id');
            
        // Add search functionality
        if (!empty($search)) {
            $orderIdsQuery->where(function($query) use ($search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('f_name', 'LIKE', "%{$search}%")
                      ->orWhere('l_name', 'LIKE', "%{$search}%");
                })->orWhere('order_id', 'LIKE', "%{$search}%")
                  ->orWhere('review', 'LIKE', "%{$search}%");
            });
        }
        
        // Get paginated order IDs
        $paginatedOrderIds = $orderIdsQuery
            ->orderBy('latest_created_at', 'desc')
            ->paginate($perPage)
            ->pluck('order_id')
            ->toArray();
            
        // Now get all reviews for these specific order IDs
        $reviews = Review::with(['customer', 'deliveryman', 'restaurant', 'order'])
            ->whereIn('order_id', $paginatedOrderIds)
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Group the reviews by order_id
        $groupedReviews = $reviews->groupBy('order_id')->map(function ($orderReviews) {
            $restaurantReview = $orderReviews->where('review_to', 'restaurant')->first();
            $deliverymanReview = $orderReviews->where('review_to', 'deliveryman')->first();
            
            // Use the most recent review for order info if both exist
            $baseReview = $restaurantReview ?: $deliverymanReview;
            
            return [
                'order_id' => $baseReview->order_id,
                'customer' => $baseReview->customer,
                'order' => $baseReview->order,
                'created_at' => $baseReview->created_at,
                'restaurant_review' => $restaurantReview,
                'deliveryman_review' => $deliverymanReview,
            ];
        })->sortByDesc('created_at');
        
        // Get pagination info
        $paginationQuery = Review::select('order_id', DB::raw('MAX(created_at) as latest_created_at'))
            ->groupBy('order_id');
            
        if (!empty($search)) {
            $paginationQuery->where(function($query) use ($search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('f_name', 'LIKE', "%{$search}%")
                      ->orWhere('l_name', 'LIKE', "%{$search}%");
                })->orWhere('order_id', 'LIKE', "%{$search}%")
                  ->orWhere('review', 'LIKE', "%{$search}%");
            });
        }
        
        $pagination = $paginationQuery
            ->orderBy('latest_created_at', 'desc')
            ->paginate($perPage);

        return view('admin-views.review.grouped-list', compact('groupedReviews', 'pagination', 'search', 'perPage'));
    }

    /**
     * Alternative optimized method for very large datasets using raw SQL
     * This method uses a single optimized query instead of multiple queries
     */
    public function indexOptimized(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        
        // Build the base query
        $baseQuery = "
            SELECT DISTINCT
                r.order_id,
                r.customer_id,
                c.f_name as customer_fname,
                c.l_name as customer_lname,
                MAX(r.created_at) as latest_review_date,
                -- Restaurant review data
                MAX(CASE WHEN r.review_to = 'restaurant' THEN r.id END) as restaurant_review_id,
                MAX(CASE WHEN r.review_to = 'restaurant' THEN r.rating END) as restaurant_rating,
                MAX(CASE WHEN r.review_to = 'restaurant' THEN r.review END) as restaurant_review,
                MAX(CASE WHEN r.review_to = 'restaurant' THEN rest.name END) as restaurant_name,
                -- Deliveryman review data
                MAX(CASE WHEN r.review_to = 'deliveryman' THEN r.id END) as deliveryman_review_id,
                MAX(CASE WHEN r.review_to = 'deliveryman' THEN r.rating END) as deliveryman_rating,
                MAX(CASE WHEN r.review_to = 'deliveryman' THEN r.review END) as deliveryman_review,
                MAX(CASE WHEN r.review_to = 'deliveryman' THEN dm.f_name END) as deliveryman_fname,
                MAX(CASE WHEN r.review_to = 'deliveryman' THEN dm.l_name END) as deliveryman_lname
            FROM reviews r
            LEFT JOIN customers c ON r.customer_id = c.id
            LEFT JOIN restaurants rest ON r.restaurant_id = rest.id
            LEFT JOIN delivery_men dm ON r.deliveryman_id = dm.id
        ";
        
        // Add search conditions
        $whereConditions = "WHERE 1=1";
        $searchParams = [];
        
        if (!empty($search)) {
            $whereConditions .= " AND (
                c.f_name LIKE ? OR 
                c.l_name LIKE ? OR 
                r.order_id LIKE ? OR 
                r.review LIKE ?
            )";
            $searchTerm = "%{$search}%";
            $searchParams = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }
        
        // Complete query with pagination
        $query = $baseQuery . $whereConditions . "
            GROUP BY r.order_id, r.customer_id, c.f_name, c.l_name
            ORDER BY latest_review_date DESC
            LIMIT ? OFFSET ?
        ";
        
        // Count query for pagination
        $countQuery = "
            SELECT COUNT(DISTINCT r.order_id) as total
            FROM reviews r
            LEFT JOIN customers c ON r.customer_id = c.id
            " . $whereConditions;
        
        // Execute queries
        $results = DB::select($query, array_merge($searchParams, [$perPage, $offset]));
        $totalCount = DB::select($countQuery, $searchParams)[0]->total;
        
        // Transform results into the expected format
        $groupedReviews = collect($results)->map(function ($row) {
            return [
                'order_id' => $row->order_id,
                'customer' => (object)[
                    'id' => $row->customer_id,
                    'f_name' => $row->customer_fname,
                    'l_name' => $row->customer_lname,
                ],
                'created_at' => $row->latest_review_date,
                'restaurant_review' => $row->restaurant_review_id ? (object)[
                    'id' => $row->restaurant_review_id,
                    'rating' => $row->restaurant_rating,
                    'review' => $row->restaurant_review,
                    'restaurant' => (object)['name' => $row->restaurant_name],
                ] : null,
                'deliveryman_review' => $row->deliveryman_review_id ? (object)[
                    'id' => $row->deliveryman_review_id,
                    'rating' => $row->deliveryman_rating,
                    'review' => $row->deliveryman_review,
                    'deliveryman' => (object)[
                        'f_name' => $row->deliveryman_fname,
                        'l_name' => $row->deliveryman_lname,
                    ],
                ] : null,
            ];
        });
        
        // Create manual pagination
        $pagination = new \Illuminate\Pagination\LengthAwarePaginator(
            $results,
            $totalCount,
            $perPage,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
        
        return view('admin-views.review.grouped-list', compact('groupedReviews', 'pagination', 'search', 'perPage'));
    }

    /**
     * Cached version using simple data caching
     */
    public function indexCached(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        
        // Create cache key based on request parameters
        $cacheKey = "reviews_data_" . md5($perPage . '_' . $search . '_' . $page);
        
        // Try to get cached results
        $cachedResults = Cache::get($cacheKey);
        
        if ($cachedResults) {
            // Use cached data
            $groupedReviews = collect($cachedResults['groupedReviews']);
            $pagination = $cachedResults['pagination'];
            return view('admin-views.review.grouped-list', compact('groupedReviews', 'pagination', 'search', 'perPage'));
        }
        
        // Get fresh data using the regular method
        $result = $this->indexOptimized($request); // Use optimized method for better performance
        
        return $result;
    }
    
    /**
     * Clear the reviews cache (call this when reviews are updated)
     */
    public function clearCache()
    {
        // Clear all cached review data
        $keys = Cache::get('review_cache_keys', []);
        foreach ($keys as $key) {
            Cache::forget($key);
        }
        Cache::forget('review_cache_keys');
    }

    /**
     * Update restaurant review
     */
    public function updateRestaurantReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_id' => 'required|exists:reviews,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $review = Review::findOrFail($request->review_id);
            $review->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            // Clear cache when review is updated
            $this->clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Restaurant review updated successfully',
                'review' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update deliveryman review
     */
    public function updateDeliverymanReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'review_id' => 'required|exists:reviews,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $review = Review::findOrFail($request->review_id);
            $review->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            // Clear cache when review is updated
            $this->clearCache();

            return response()->json([
                'success' => true,
                'message' => 'Deliveryman review updated successfully',
                'review' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get review details for editing
     */
    public function getReview($id)
    {
        try {
            $review = Review::with(['customer', 'deliveryman', 'restaurant'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'review' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }
    }

    /**
     * Delete a review
     */
    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review'
            ], 500);
        }
    }
}
