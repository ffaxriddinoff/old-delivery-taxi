<?php

namespace App\Http\Controllers;

use App\Http\Requests\DistrictRequest;
use App\Models\District;


class DistrictController extends Controller {
    public function index() {
        return $this->success(District::all());
    }

    public function show(District $district) {
        return $this->success($district);
    }

    public function store(DistrictRequest $request) {
        $data = $request->validated();
        $district = District::query()->create($data);
        return $this->success($district);
    }

    public function update(DistrictRequest $request, District $district) {
        $data = $request->validated();

        $district->update($data);
        return $this->success($district);
    }

    public function destroy(District $district) {
        $district->delete();
        return $this->success([]);
    }
}
