<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Review;

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'total'    => Destination::count(),
            'pending'  => Destination::pending()->count(),
            'approved' => Destination::approved()->count(),
            'rejected' => Destination::where('status', 'rejected')->count(),
        ];

        $reviewStats = [
            'total'      => Review::count(),
            'avg'        => round(Review::avg('rating') ?? 0, 1),
            'this_month' => Review::whereMonth('created_at', now()->month)->count(),
        ];

        $pending = Destination::pending()->with(['category', 'hero'])->latest()->take(5)->get();

        $topRated = Destination::approved()
            ->withCount('reviews as review_count')
            ->withAvg('reviews as avg_rating', 'rating')
            ->having('review_count', '>', 0)
            ->orderByDesc('avg_rating')
            ->take(5)
            ->get();

        $recentReviews = Review::with('destination')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'reviewStats', 'pending', 'topRated', 'recentReviews'
        ));
    }
}
