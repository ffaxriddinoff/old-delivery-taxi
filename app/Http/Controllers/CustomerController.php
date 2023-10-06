<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomerCardRequest;
use App\Http\Requests\CustomerRequest;
use App\Http\Services\CustomerService;
use App\Models\Customer;

class CustomerController extends Controller {

    /**
     * @var CustomerService
     */
    private CustomerService $service;

    public function __construct(CustomerService $service) {
        $this->service = $service;
    }

    public function index() {
        return $this->service->all();
    }

    public function store(CustomerRequest $request) {
        return $this->service->save($request);
    }

    public function store_card(CustomerCardRequest $request, Customer $customer) {
        return $this->service->update($customer, $request->validated());
    }

    public function show(Customer $customer) {
        return $this->success($customer);
    }

    public function update(CustomerRequest $request, Customer $customer) {
        return $this->service->update($customer, $request->validated());
    }

    public function card(Request $request, Customer $customer) {
        return $this->service->addCard($customer, $request->all());
    }

    public function destroy(Customer $customer) {
        return $this->service->destroy($customer);
    }
}
