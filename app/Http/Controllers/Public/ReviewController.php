<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller {
    public function store(Request $request, string $slug) {
        $request->validate([
            'reviewer_name' => 'required|string|max:100',
            'rating'        => 'required|numeric|min:0.5|max:5',
            'comment'       => 'required|string|min:10|max:1000',
        ], [
            'reviewer_name.required' => 'Nama wajib diisi.',
            'rating.required'        => 'Rating wajib dipilih.',
            'comment.required'       => 'Ulasan wajib diisi.',
            'comment.min'            => 'Ulasan minimal 10 karakter.',
        ]);

        $destination = Destination::approved()->where('slug', $slug)->firstOrFail();

        Review::create([
            'destination_id' => $destination->id,
            'reviewer_name'  => $request->reviewer_name,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);

        return back()->with('review_success', 'Ulasan kamu berhasil dikirim. Terima kasih!');
    }
}
