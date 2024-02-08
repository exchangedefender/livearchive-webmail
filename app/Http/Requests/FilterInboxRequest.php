<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterInboxRequest extends FormRequest
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
            'filter' => 'in:on,off',
            'sender' => 'nullable|string',
            'subject' => 'nullable|string',
            'date' => 'nullable|string',
        ];
    }
}
