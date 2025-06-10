<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all logged-in users, restrict if needed
    }

    public function rules()
    {
        return [
          'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:contacts,email'],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:Male,Female'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'additional_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,txt', 'max:4096'],
            'custom_fields'     => 'nullable|array',
            'custom_fields.*.key'   => 'required|string|max:255',
            'custom_fields.*.value' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'profile_image.max' => 'Profile image file should not be greater then 2MB.',
            'additional_file.max' => 'Additional file should not be greater then 4MB.',
            'custom_fields.*.key.required' => 'Custom field key is required.',
            'custom_fields.*.value.required' => 'Custom field value is required.',
        ];
    }
}
