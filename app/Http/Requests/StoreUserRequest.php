<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        'name' => 'required|string|min:2',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'phone' => 'required|string|regex:/^01[0-9]{9}$/',
        'image' => 'nullable',
        'profile_description' => 'nullable|string',
        'role_id' => 'required|integer|exists:roles,id',
        'address' => 'string', 'max:255',
        'gender' => 'nullable|string|in:Male,Female',
        'date_of_birth' => ['nullable','date','before_or_equal:today',],        ];
    }
    
}
