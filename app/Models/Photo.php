<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model {
    protected $fillable = ['destination_id', 'path', 'is_cover', 'type', 'order'];

    public function destination(): BelongsTo {
        return $this->belongsTo(Destination::class);
    }

    public function url(): string {
        return asset('storage/' . $this->path);
    }
}
