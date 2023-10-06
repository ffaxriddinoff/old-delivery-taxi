<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\MessageService;
use App\Models\Client;
use App\Http\Requests\DriverLoginRequest;
use App\Http\Requests\VerifyRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Services\AuthService;
use App\Jobs\SendMessageJob;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Partner;
use App\Models\User;


class AuthController extends Controller {

    private AuthService $service;
    public function __construct() {
        $this->service = new AuthService();
    }

    /* Authenticate */
    function login(LoginRequest $request) {
        list($token, $model) = $this->service->login(new User(), $request->validated());
        if ($token) {
            return $this->success([
                'token' => $token,
                'model' => $model
            ]);
        }

        return $this->fail();
    }

    // Driver login
    public function driver(DriverLoginRequest $request) {
        $driver = Driver::query()->where('phone',
            $request->get('phone'))->first();


        if ($driver) {
            $driver->password = rand(1000, 9999);
            $driver->update();
            SendMessageJob::dispatch($driver, $request->get('token'));
            return $this->success([
                'code' => $driver->password
            ]);
        }

        return $this->fail([], 'Driver not found');
    }

    // Driver verify
    public function verify(VerifyRequest $request) {
        $data = $request->validated();
        $driver = Driver::query()->where('phone', $data['phone'])
            //->where('password', $data['code'])
            ->firstOrFail();

        if ($driver) {
            $driver->password = '';
            $driver->update();
            return $this->success([
                'id' => $driver->id,
                'token' => $driver->createToken('auth_token')->plainTextToken
            ]);
        }

        return $this->fail([]);
    }

    // Partner
    public function partner(LoginRequest $request) {
        list($token, $model) = $this->service->loginOnlyUsername(new Partner(), $request->validated());
        if ($token) {
            return $this->success([
                'token' => $token,
                'partner' => $model
            ]);
        }

        return $this->fail();
    }

    // Customer
    public function customer(DriverLoginRequest $request) {
        $phone = $request->get('phone');
        $customer = Customer::query()->where('phone', $phone)
            ->firstOrCreate(['phone' => $phone]);

        $customer->password = rand(1000, 9999);
        $customer->update();

        dispatch(new SendMessageJob($customer, $request->get('token')));
        return $this->success([
            'code' => $customer->password
        ]);
    }

    public function verifyCustomer(VerifyRequest $request) {
        $data = $request->validated();
        $customer = Customer::query()
            ->where('phone', $data['phone'])
            ->where('password', $data['code'])
            ->first();
        if ($customer) {
            $customer->password = '';
            $customer->update();
            return $this->success([
                'model' => $customer,
                'token' => $customer->createToken('auth_token')->plainTextToken
            ]);
        }

        return $this->fail([]);
    }

    public function client(DriverLoginRequest $request) {
        $phone = $request->get('phone');
        $client = Client::query()->where('phone', $phone)
            ->firstOrCreate(['phone' => $phone], ['name' => $request->name]);

        $client->password = rand(1000, 9999);
        $client->update(['name' => $request->name]);

        dispatch(new SendMessageJob($client, $request->get('token')));
        return $this->success([
            'code' => $client->password
        ]);
    }

    public function verifyClient(VerifyRequest $request) {
        $data = $request->validated();
        $client = Client::query()->where('phone', $data['phone'])
            ->where('password', $data['code'])
            ->first();
        if ($client) {
            $client->password = '';
            $client->update();
            return $this->success([
                'model' => $client,
                'token' => $client->createToken('auth_token')->plainTextToken
            ]);
        }

        return $this->fail([]);
    }


    public function receive(Request $request) {// EXPIRED
        (new MessageService())->receive($request);
    }
}
