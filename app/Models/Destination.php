<?php
namespace App\Models;

use App\Enums\DestinationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Destination extends Model {
    protected $fillable = [
        'category_id', 'submitter_name', 'submitter_email',
        'title', 'slug', 'description', 'address', 'district',
        'lat', 'lng', 'status', 'rejection_note', 'is_featured', 'published_at',
    ];

    protected $casts = [
        'status'       => DestinationStatus::class,
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function photos(): HasMany {
        return $this->hasMany(Photo::class)->orderBy('order');
    }

    public function cover(): HasOne {
        return $this->hasOne(Photo::class)->where('is_cover', true);
    }

    public function hero(): HasOne {
        return $this->hasOne(Photo::class)->where('type', 'hero');
    }

    public function gallery(): HasMany {
        return $this->hasMany(Photo::class)->where('type', 'gallery');
    }

    public function reviews(): HasMany {
        return $this->hasMany(Review::class)->latest();
    }

    public function averageRating(): float {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function reviewCount(): int {
        return $this->reviews()->count();
    }

    // distribusi rating untuk tampilan admin
    public function ratingDistribution(): array {
        $dist = [];
        for ($i = 5; $i >= 1; $i--) {
            $dist[$i] = $this->reviews()
                ->whereBetween('rating', [$i - 0.4, $i + 0.5])
                ->count();
        }
        return $dist;
    }

    public function scopeApproved($query) {
        return $query->where('status', DestinationStatus::Approved);
    }

    public function scopePending($query) {
        return $query->where('status', DestinationStatus::Pending);
    }

    public function scopeFeatured($query) {
        return $query->where('is_featured', true);
    }
}
