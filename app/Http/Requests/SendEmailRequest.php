<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You can add authentication logic here if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'to' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'from' => 'nullable|email',
            'from_name' => 'nullable|string|max:255',
            'is_html' => 'nullable|boolean',
            'attachments' => 'nullable|array',
            'attachments.*.path' => 'nullable|string',
            'attachments.*.name' => 'nullable|string',
            'attachments.*.mime' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'to.required' => 'The recipient email address is required.',
            'to.email' => 'The recipient email address must be a valid email.',
            'subject.required' => 'The email subject is required.',
            'subject.max' => 'The email subject may not be greater than 255 characters.',
            'body.required' => 'The email body is required.',
            'from.email' => 'The sender email address must be a valid email.',
            'from_name.max' => 'The sender name may not be greater than 255 characters.',
            'attachments.array' => 'Attachments must be an array.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'to' => 'recipient email',
            'subject' => 'email subject',
            'body' => 'email body',
            'from' => 'sender email',
            'from_name' => 'sender name',
            'is_html' => 'HTML format flag',
            'attachments' => 'email attachments',
        ];
    }
}
