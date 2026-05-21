<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationRequest;
use App\Http\Requests\UpdateDestinationRequest;
use App\Models\Category;
use App\Models\Destination;
use App\Services\DestinationService;
use Illuminate\Http\Request;

class DestinationController extends Controller {
    public function __construct(private DestinationService $destinationService) {}

    public function index(Request $request) {
        $query = Destination::with(['category', 'hero']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        // - default (advance_field = "all"): cari ke beberapa kolom (title, district, submitter_name)
        // - advance search: hanya cari di kolom yang dipilih
        if ($request->filled('q')) {
            $q = $request->q;
            $field = $request->input('advance_field', 'all');

            $query->where(function ($query) use ($q, $field) {
                $like = '%' . $q . '%';

                $allowedFields = ['title', 'district', 'submitter_name'];
                if (!in_array($field, $allowedFields, true)) {
                    $field = 'all';
                }

                if ($field === 'all') {
                    $query->where('title', 'like', $like)
                        ->orWhere('district', 'like', $like)
                        ->orWhere('submitter_name', 'like', $like);
                } else {
                    $query->where($field, 'like', $like);
                }
            });
        }

        $destinations = $query->latest()->paginate(15)->withQueryString();
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create() {
        $categories = Category::all();
        return view('admin.destinations.create', compact('categories'));
    }

    public function store(StoreDestinationRequest $request) {
        $data            = $request->validated();
        $data['hero']    = $request->file('hero');
        $data['gallery'] = $request->file('gallery') ?? [];
        $this->destinationService->store($data);
        return redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil ditambahkan.');
    }

    public function show(Destination $destination) {
        $destination->load(['photos', 'category', 'hero', 'gallery']);
        return view('admin.destinations.show', compact('destination'));
    }

    public function edit(Destination $destination) {
        $categories = Category::all();
        $destination->load(['photos', 'hero', 'gallery']);
        return view('admin.destinations.edit', compact('destination', 'categories'));
    }

    public function update(UpdateDestinationRequest $request, Destination $destination) {
        $data            = $request->validated();
        $data['hero']    = $request->file('hero');
        $data['gallery'] = $request->file('gallery') ?? [];
        $this->destinationService->update($destination, $data);
        return redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil diperbarui.');
    }

    public function destroy(Destination $destination) {
        $this->destinationService->delete($destination);
        return redirect()->route('admin.destinations.index')->with('success', 'Destinasi berhasil dihapus.');
    }

    public function approve(Destination $destination) {
        $this->destinationService->approve($destination);
        return back()->with('success', 'Destinasi disetujui dan sekarang tayang.');
    }

    public function reject(Request $request, Destination $destination) {
        $request->validate(['rejection_note' => 'required|string']);
        $this->destinationService->reject($destination, $request->rejection_note);
        return back()->with('success', 'Destinasi ditolak.');
    }
}