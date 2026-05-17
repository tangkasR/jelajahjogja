<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller {
    public function index(Request $request) {
        $query = Destination::approved()->with(['hero', 'category']);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }
        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $destinations = $query->latest('published_at')->paginate(12)->withQueryString();
        $categories   = Category::all();
        $districts    = Destination::approved()->distinct()->pluck('district');

        return view('public.destinations.index', compact('destinations', 'categories', 'districts'));
    }

    public function show(string $slug) {
        $destination = Destination::approved()
            ->where('slug', $slug)
            ->with(['hero', 'gallery', 'category'])
            ->firstOrFail();

        $reviews = $destination->reviews()->latest()->get();
        $related = Destination::approved()
            ->where('category_id', $destination->category_id)
            ->where('id', '!=', $destination->id)
            ->with('hero')
            ->take(3)
            ->get();

        return view('public.destinations.show', compact('destination', 'related', 'reviews'));
    }
}
