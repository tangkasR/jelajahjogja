<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDestinationRequest extends FormRequest {
    public function authorize(): bool { return true; }

    public function rules(): array {
        return [
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:150',
            'description' => 'required|string|min:50',
            'address'     => 'required|string',
            'district'    => 'required|string',
            'lat'         => 'nullable|numeric',
            'lng'         => 'nullable|numeric',
            'is_featured' => 'boolean',
            'hero'        => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'gallery'     => 'nullable|array|max:8',
            'gallery.*'   => 'image|mimes:jpg,jpeg,png,webp',
        ];
    }
}