<?php
namespace App\Services;

use App\Models\Destination;
use App\Enums\DestinationStatus;
use Illuminate\Support\Str;

class DestinationService {
    public function __construct(private PhotoService $photoService) {}

    public function approve(Destination $destination): void {
        $destination->update([
            'status'         => DestinationStatus::Approved,
            'rejection_note' => null,
            'published_at'   => now(),
        ]);
    }

    public function reject(Destination $destination, string $note): void {
        $destination->update([
            'status'         => DestinationStatus::Rejected,
            'rejection_note' => $note,
        ]);
    }

    public function store(array $data): Destination {
        $destination = Destination::create([
            'category_id'     => $data['category_id'],
            'submitter_name'  => 'Admin',
            'submitter_email' => 'admin@jelajahjogja.com',
            'title'           => $data['title'],
            'slug'            => Str::slug($data['title']),
            'description'     => $data['description'],
            'address'         => $data['address'],
            'district'        => $data['district'],
            'lat'             => $data['lat'] ?? null,
            'lng'             => $data['lng'] ?? null,
            'is_featured'     => $data['is_featured'] ?? false,
            'status'          => DestinationStatus::Approved,
            'published_at'    => now(),
        ]);

        if (!empty($data['hero'])) {
            $this->photoService->storeHero($destination, $data['hero']);
        }

        if (!empty($data['gallery'])) {
            $this->photoService->storeGallery($destination, $data['gallery']);
        }

        return $destination;
    }

    public function update(Destination $destination, array $data): void {
        $destination->update([
            'category_id' => $data['category_id'],
            'title'       => $data['title'],
            'slug'        => Str::slug($data['title']),
            'description' => $data['description'],
            'address'     => $data['address'],
            'district'    => $data['district'],
            'lat'         => $data['lat'] ?? null,
            'lng'         => $data['lng'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
        ]);

        if (!empty($data['hero'])) {
            $this->photoService->storeHero($destination, $data['hero']);
        }

        if (!empty($data['gallery'])) {
            $this->photoService->storeGallery($destination, $data['gallery']);
        }
    }

    public function delete(Destination $destination): void {
        $this->photoService->deleteAll($destination);
        $destination->delete();
    }
}
