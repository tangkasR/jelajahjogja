<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model {
    protected $fillable = [
        'destination_id',
        'reviewer_name',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'float',
    ];

    public function destination(): BelongsTo {
        return $this->belongsTo(Destination::class);
    }
}
