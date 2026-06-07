<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'product'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('comment', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('first_name', 'like', "%{$search}%")
                                   ->orWhere('last_name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('product', function ($productQuery) use ($search) {
                          $productQuery->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->rating, function ($query, $rating) {
                $query->where('rating', $rating);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate statistics
        $totalReviews = Review::count();
        $pendingReviews = Review::where('status', 'pending')->count();
        $approvedReviews = Review::where('status', 'approved')->count();
        $rejectedReviews = Review::where('status', 'rejected')->count();
        $averageRating = Review::where('status', 'approved')->avg('rating');

        return view('admin.reviews.index', compact('reviews', 'totalReviews', 'pendingReviews', 'approvedReviews', 'rejectedReviews', 'averageRating'));
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);

        return back()->with('success', 'Review approved successfully.');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);

        return back()->with('success', 'Review rejected successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }

    public function bulkApprove(Request $request)
    {
        $reviewIds = $request->input('reviews', []);
        
        if (empty($reviewIds)) {
            return back()->with('error', 'No reviews selected.');
        }

        Review::whereIn('id', $reviewIds)->update(['status' => 'approved']);

        return back()->with('success', 'Selected reviews approved successfully.');
    }

    public function bulkReject(Request $request)
    {
        $reviewIds = $request->input('reviews', []);
        
        if (empty($reviewIds)) {
            return back()->with('error', 'No reviews selected.');
        }

        Review::whereIn('id', $reviewIds)->update(['status' => 'rejected']);

        return back()->with('success', 'Selected reviews rejected successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $reviewIds = $request->input('reviews', []);
        
        if (empty($reviewIds)) {
            return back()->with('error', 'No reviews selected.');
        }

        Review::whereIn('id', $reviewIds)->delete();

        return back()->with('success', 'Selected reviews deleted successfully.');
    }
}
