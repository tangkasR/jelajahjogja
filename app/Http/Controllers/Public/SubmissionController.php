<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubmissionRequest;
use App\Models\Category;
use App\Services\SubmissionService;

class SubmissionController extends Controller {
    public function __construct(private SubmissionService $submissionService) {}

    public function create() {
        $categories = Category::all();
        return view('public.submit', compact('categories'));
    }

    public function store(StoreSubmissionRequest $request) {
        $data            = $request->validated();
        $data['hero']    = $request->file('hero');
        $data['gallery'] = $request->file('gallery') ?? [];
        $this->submissionService->store($data);
        return redirect()->route('submit')->with('success', 'Destinasi berhasil dikirim! Menunggu review admin.');
    }
}
