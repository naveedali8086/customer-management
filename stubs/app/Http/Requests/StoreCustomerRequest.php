<?php

namespace App\Http\Requests;


class StoreCustomerRequest extends CustomerRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
