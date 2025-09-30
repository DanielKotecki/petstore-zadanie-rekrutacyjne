<?php

namespace App\Http\Requests;

use App\Enums\PetStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(PetStatus::toArray())],
            'category.id' => ['nullable', 'integer', 'min:1', 'required_with:category.name'],
            'category.name' => ['nullable', 'string', 'max:255', 'required_with:category.id'],
            'photoUrls' => ['required', 'array', 'min:1'],
            'photoUrls.*' => ['required', 'string', 'url'],
            'tags' => ['nullable', 'array'],
            'tags.*.id' => ['nullable', 'integer', 'min:1', 'required_with:tags.*.name', 'distinct'],
            'tags.*.name' => ['nullable', 'string', 'max:255', 'required_with:tags.*.id'],
        ];
    }
}
