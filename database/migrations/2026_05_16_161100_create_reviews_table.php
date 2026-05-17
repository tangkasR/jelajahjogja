<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->string('reviewer_name');
            $table->decimal('rating', 2, 1); // support 0.5 - 5.0
            $table->text('comment');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('reviews');
    }
};
