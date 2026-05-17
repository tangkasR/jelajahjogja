<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;

class HomeController extends Controller {
    public function index() {
        $featured   = Destination::approved()->featured()->with(['hero', 'category'])->take(3)->get();
        $categories = Category::withCount(['destinations' => fn($q) => $q->approved()])->get();
        $latest     = Destination::approved()->with(['hero', 'category'])->latest('published_at')->take(6)->get();
        return view('public.home', compact('featured', 'categories', 'latest'));
    }
}
