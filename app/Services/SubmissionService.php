<?php
namespace App\Services;

use App\Models\Destination;
use Illuminate\Support\Str;

class SubmissionService {
    public function __construct(private PhotoService $photoService) {}

    public function store(array $data): Destination {
        $destination = Destination::create([
            'category_id'     => $data['category_id'],
            'submitter_name'  => $data['submitter_name'],
            'submitter_email' => $data['submitter_email'],
            'title'           => $data['title'],
            'slug'            => Str::slug($data['title']) . '-' . Str::random(5),
            'description'     => $data['description'],
            'address'         => $data['address'],
            'district'        => $data['district'],
            'lat'             => $data['lat'] ?? null,
            'lng'             => $data['lng'] ?? null,
            'status'          => 'pending',
        ]);

        if (!empty($data['hero'])) {
            $this->photoService->storeHero($destination, $data['hero']);
        }

        if (!empty($data['gallery'])) {
            $this->photoService->storeGallery($destination, $data['gallery']);
        }

        return $destination;
    }
}
