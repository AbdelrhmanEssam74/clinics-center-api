<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [ 'required','string', 'email', Rule::unique('users', 'email')],
            'phone' => ['string', 'max:11', 'nullable'],
            'address' => ['string', 'max:255'],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'address' => ['string', 'nullable', 'max:255'],
            'profile_description' => ['string','nullable'],
            'specialty_id' => ['required','exists:specialties,id'],
            'experience_years' =>['integer', 'min:0'],
            'appointment_fee' => ['required', 'numeric', 'min:0'], // Validation for appointment fee => ahmed abdelhalim
        ];
    }
     public function messages(): array
    {
        return
            [
                'name.required' => 'The name field is required.',
                'name.min' => 'The name must be at least 3 characters.',
                'email.required' => 'The email field is required.',
                'email.unique' => 'This email is already exists, it must be unique.',
                'phone.max' => 'The phone number must be 11 digits',
                'gender.in' => 'Gender must be either Male or Female.',
                'appointment_fee.required' => 'The appointment fee is required.', // Validation message for appointment fee => ahmed abdelhalim
                'appointment_fee.numeric' => 'The appointment fee must be a number.',
                'appointment_fee.min' => 'The appointment fee must be at least 0.',
         
            ];
    }
}
