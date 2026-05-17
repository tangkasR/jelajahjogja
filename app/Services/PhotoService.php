<?php
namespace App\Services;

use App\Models\Destination;
use Illuminate\Support\Facades\Storage;

class PhotoService {
    public function storeHero(Destination $destination, $file): void {
        if ($destination->hero) {
            Storage::disk('public')->delete($destination->hero->path);
            $destination->hero->delete();
        }
        $path = $file->store('destinations', 'public');
        $destination->photos()->create([
            'path'     => $path,
            'is_cover' => true,
            'type'     => 'hero',
            'order'    => 0,
        ]);
    }

    public function storeGallery(Destination $destination, array $files): void {
        $startOrder = $destination->gallery()->count();
        foreach ($files as $index => $file) {
            $path = $file->store('destinations', 'public');
            $destination->photos()->create([
                'path'     => $path,
                'is_cover' => false,
                'type'     => 'gallery',
                'order'    => $startOrder + $index,
            ]);
        }
    }

    public function deleteAll(Destination $destination): void {
        foreach ($destination->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }
    }
}
