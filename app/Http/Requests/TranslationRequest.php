<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslationRequest extends FormRequest
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
            'locale' => $this->isMethod('post') ? 'required|string|max:5' : 'sometimes|required|string|max:5',
            'translation_key' => $this->isMethod('post') ? 'required|string' : 'sometimes|required|string',
            'content' => $this->isMethod('post') ? 'required|string' : 'sometimes|required|string',
            'tags' => 'array',
            'tags.*' => 'string'
        ];
    }
}
