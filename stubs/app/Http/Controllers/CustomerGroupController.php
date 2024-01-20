<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerGroupRequest;
use App\Http\Requests\UpdateCustomerGroupRequest;
use App\Http\Resources\CustomerGroupCollection;
use App\Http\Resources\CustomerGroupResource;
use App\Models\CustomerGroup;

class CustomerGroupController extends Controller
{

    /**
     * Get a list of CustomerGroups.
     */
    public function index()
    {
        $customerGroups = CustomerGroup::paginate(10);

        return new CustomerGroupCollection($customerGroups);
    }

    /**
     * Store a CustomerGroup in storage.
     */
    public function store(StoreCustomerGroupRequest $request)
    {
        $customerGroup = CustomerGroup::create($request->validated());

        if ($customerGroup) {
            return (new CustomerGroupResource($customerGroup))
                ->response()
                ->setStatusCode(201);
        } else {
            abort(500, 'Failed to store the customerGroup');
        }
    }

    /**
     * Get the specified CustomerGroup.
     */
    public function show(CustomerGroup $customerGroup)
    {
        return new CustomerGroupResource($customerGroup);
    }

    /**
     * Update the specified CustomerGroup in storage.
     */
    public function update(UpdateCustomerGroupRequest $request, CustomerGroup $customerGroup)
    {
        $updated = $customerGroup->update($request->validated());

        if ($updated) {
            return new CustomerGroupResource($customerGroup);
        } else {
            abort(500, 'Failed to update the customerGroup');
        }
    }

    /**
     * Remove the specified CustomerGroup from storage.
     */
    public function destroy(CustomerGroup $customerGroup)
    {
        if ($customerGroup->delete()) {
            return response(null, 204);
        } else {
            abort(500, 'Failed to delete the customerGroup');
        }
    }

}
