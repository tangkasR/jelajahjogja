<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller {
    public function index(Request $request) {
        $query = Review::with('destination');

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('reviewer_name', 'like', '%'.$request->q.'%')
                  ->orWhere('comment', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('destination_id')) {
            $query->where('destination_id', $request->destination_id);
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => Review::count(),
            'avg'   => round(Review::avg('rating') ?? 0, 1),
            'this_month' => Review::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function destroy(Review $review) {
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
