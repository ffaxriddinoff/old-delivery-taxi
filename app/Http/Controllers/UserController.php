<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function index(Request $request) {
        return $this->success(
            User::query()->where('role',  $request->get('role') ?? 2)->get()
        );
    }

    public function show(User $user){
        return $user;
    }

    public function store(UserRequest $request) {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::query()->create($data);

        return $this->success($user);
    }

    public function update(UserRequest $request, User $user) {
        $data = $request->validated();
        if ($request->get('password'))
            $data['password'] = bcrypt($data['password']);

        $user->update($data);
        return $this->success($user);
    }

    public function destroy(User $user) {
        $user->delete();
        return $this->success();
    }
}
