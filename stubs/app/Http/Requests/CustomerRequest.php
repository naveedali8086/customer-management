<?php

namespace App\Http\Requests;

use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CustomerRequest extends FormRequest
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
            'first_name' => 'required|max:255',
            'middle_name' => 'sometimes|nullable|max:255',
            'last_name' => 'required|max:255',
            'customer_code' => 'sometimes|nullable|max:255|unique:customers,customer_code',
            //'customer_code' => ['sometimes', 'nullable', 'max:255', 'unique:customers,customer_code'],
            //'customer_code' => ['sometimes', 'nullable', 'max:255',
            //    Rule::unique('customers', 'customer_code')
            //],
            'enable_notification' => 'sometimes|nullable|boolean',
            'date_of_birth' => 'sometimes|nullable|date_format:Y-m-d|before_or_equal:today',
            'gender' => ['sometimes', 'nullable', 'in:M,F,Other'],
            'gender_other' => 'required_if:gender,Other|max:255',
            'note' => 'sometimes|nullable|max:255',
            'contact_channel' => ['required', 'max:255'],
            'contact_channel_value' => ['required', 'max:255'],
        ];
    }

    public function after()
    {
        return [
            function (Validator $validator) {
                $contact_channel_value = $validator->getValue('contact_channel_value');

                // checking if the 'channel_value' is unique or not.
                // If it is then raise validation error
                $contactAlreadyExists = Contact::query()
                    ->where('channel_value', $contact_channel_value)
                    ->where('contactable_type', Customer::class)
                    ->exists();

                if ($contactAlreadyExists) {
                    $validator->errors()->add(
                        "contact_channel_value",
                        "A customer with '$contact_channel_value' already exist"
                    );
                }
            }
        ];
    }
}
