<?php

namespace App\Http\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Http\Interfaces\IAuthService;

class AuthService implements IAuthService {
    use Response;

    public function login(Model $model, $data) {
        $model = $model::query()
            ->where('username', $data['username'])
            ->firstOrFail(); // ->where('role', $data['role'] ? '=' : '>', $data['role'])

        $token = null;
        if ($model && $this->check($data['password'], $model->password))
            $token = $model->createToken('auth_token')->plainTextToken;
        if (!($model->role % 2 == $data['role'] % 2))
            $token = null;
            

        return [$token, $model];
    }

    public function loginOnlyUsername(Model $model, $data) {
        $model = $model::query()
            ->where('username', $data['username'])
            ->firstOrFail();

        $token = null;
        if ($model && $this->check($data['password'], $model->password))
            $token = $model->createToken('auth_token')->plainTextToken;

        return [$token, $model];
    }

    public function sendCode(Model $model, $data) {

    }

    private function check($val, $hashed) {
        return Hash::check($val, $hashed);
    }
}
