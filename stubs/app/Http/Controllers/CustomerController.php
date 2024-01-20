<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Get a list of the Customers.
     */
    public function index()
    {
        $customers = Customer::paginate(10);

        return new CustomerCollection($customers);
    }

    /**
     * Store a Customer in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $failedMsg = 'Failed to store the customer';
        $failedCode = 500;
        try {
            $input = $request->validated();

            $customerData = Arr::except($input, ['contact_channel', 'contact_channel_value']);

            $contactData = [
                'channel' => $input['contact_channel'],
                'channel_value' => $input['contact_channel_value']
            ];

            DB::beginTransaction();

            $customer = Customer::create($customerData);
            throw_unless($customer, new Exception($failedMsg, $failedCode));

            $contact = $customer->contacts()->create($contactData);
            throw_unless($contact, new Exception($failedMsg, $failedCode));

            DB::commit();

            return (new CustomerResource($customer))
                ->response()
                ->setStatusCode(201);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("An exception occurred during customer creation:\n" . $e->getMessage());
            abort($failedCode, $failedMsg);
        }
    }

    /**
     * Get the specified Customer.
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Update the specified Customer in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $updated = $customer->update($request->validated());

        if ($updated) {
            return new CustomerResource($customer);
        } else {
            abort(500, 'Failed to update the customer');
        }
    }

    /**
     * Remove the specified Customer from storage.
     */
    public function destroy(Customer $customer)
    {
        // Show prompt at client side when a customer is deleted:
        // "Deleting a customer would also delete its associations i.e. Contacts, Addresses, CustomFields...
        //  However, the deleted data would remain in database if it needs to be recovered"

        try {
            DB::beginTransaction();

            $customer->load('contacts');
            $contactsCount = $customer->contacts->count();

            throw_unless($contactsCount === $customer->contacts()->delete());

            if ($customer->delete()) {
                DB::commit();
                return response(null, 204);
            } else {
                throw new Exception();
            }

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("An exception occurred during customer creation:\n" . $e->getMessage());
            abort(500, 'Failed to delete the customer');
        }
    }

    /**
     * Add customers to a specified CustomerGroup.
     * code = 201
     */
    public function addCustomersToCustomerGroup(Customer $customer)
    {
    }

    /**
     * Delete customers from a specified CustomerGroup.
     * code = 204
     */
    public function deleteCustomersFromCustomerGroup(Customer $customer)
    {
        //
    }

}
