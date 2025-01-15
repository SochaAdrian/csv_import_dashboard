<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file' => 'required|mimes:csv,txt|max:4096',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
