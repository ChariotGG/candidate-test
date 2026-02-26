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
            'import_file'       => ['required', 'file', 'mimes:json', 'max:10240'],
            'conflict_strategy' => ['sometimes', 'in:reject,skip,overwrite,duplicate'],
            'dry_run'           => ['sometimes', 'nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'import_file.required' => 'File import wajib diunggah.',
            'import_file.mimes'    => 'File harus berekstensi .json.',
            'import_file.max'      => 'Ukuran file maksimal 10MB.',
        ];
    }
}