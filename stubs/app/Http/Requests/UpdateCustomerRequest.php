<?php

namespace App\Http\Requests;


class UpdateCustomerRequest extends CustomerRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $customer = $this->route('customer');

        $rules = parent::rules();

        // overriding unique rule on "customer_code" to ignore unique rule
        $customer_code_rules = get_all_rules_except_unique_rule($rules['customer_code']);
        $rules['customer_code'] = $customer_code_rules . "|unique:customers,customer_code," . $customer->id;

        // validation rules on following two attributes are not needed in update case
        // because Contact's update will be handled vai Contact's API
        unset($rules['contact_channel']);
        unset($rules['contact_channel_value']);

        return $rules;
    }

}
