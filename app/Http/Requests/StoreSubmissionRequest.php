<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest {
    public function authorize(): bool { return true; }

    public function rules(): array {
        return [
            'submitter_name'  => 'required|string|max:100',
            'submitter_email' => 'required|email',
            'category_id'     => 'required|exists:categories,id',
            'title'           => 'required|string|max:150',
            'description'     => 'required|string|min:50',
            'address'         => 'required|string',
            'district'        => 'required|string',
            'lat'             => 'nullable|numeric',
            'lng'             => 'nullable|numeric',
            'hero'            => 'required|image|mimes:jpg,jpeg,png,webp',
            'gallery'         => 'nullable|array|max:8',
            'gallery.*'       => 'image|mimes:jpg,jpeg,png,webp',
        ];
    }
}