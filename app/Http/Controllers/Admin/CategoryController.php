<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller {
    public function index() {
        $categories = Category::withCount('destinations')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:50|unique:categories']);
        Category::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function destroy(Category $category) {
        $category->delete();
        return back()->with('success', 'Kategori dihapus.');
    }
}
