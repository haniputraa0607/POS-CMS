<?php

namespace App\Http\Requests\Browses\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "id" => "required|string",
            "name" => "required|string|max:255",
            "status" => "nullable|string|in:1,0",
            "fature_ids" => "nullable|array",
            "fature_ids.*" => "nullable|integer"
        ];
    }

    public function attributes()
    {
        return[
            "fature_ids" => "fitur",
            "fature_ids.*" => "fitur"
        ];

    }
}
