<?php

namespace App\Http\Requests\Distributor; // Assuming your requests are under 'Distributor' subdirectory

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDistributorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Assuming you have proper authorization logic in place
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:distributors,email,' . $this->distributor->id, // Exclude current distributor from email uniqueness check
            'region' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'type' => 'required|string|in:Rider,Agent', // Restrict allowed types
            'address' => 'required|string',
            'photo' => [
                // Rule for checking if a file is uploaded (optional)
                //'required', // Comment out if allowing updates without changing image
                'image', // Rule for ensuring it's an image
                'max:2048', // Maximum file size in kilobytes (adjust as needed)
                'mimetypes:image/jpeg,image/png', // Allowed MIME types (JPEG & PNG)
            ],
        ];
    }

    /**
     * Get custom validation messages for attributes.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'photo.image' => 'The uploaded file must be an image.',
            'photo.max' => 'The image file size must be less than 2 MB.',
            'photo.mimetypes' => 'The image must be a JPEG or PNG file.',
            // Add custom messages for other validation rules (optional)
        ];
    }
}
