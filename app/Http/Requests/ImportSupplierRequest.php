<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'import_file' => ['required', 'file', 'mimetypes:application/json,text/plain', 'mimes:json']
        ];
    }

    public function messages(): array
    {
        return [
            'import_file.required' => 'File import wajib diunggah.',
            'import_file.mimes'    => 'File harus berupa format JSON.',
        ];
    }
}