<?php

namespace App\Http\Controllers;

use App\Http\Requests\PointRequest;
use App\Models\Point;

class PointController extends Controller {

    public function index() {
        return $this->success(Point::all());
    }

    public function store(PointRequest $request) {
        return $this->success(Point::query()->create($request->validated()));
    }

    public function update(PointRequest $request, Point $point) {
        $point->update($request->validated());
        return $this->success($point);
    }

    public function destroy(Point $point) {
        $point->delete();
        return $this->success([]);
    }
}
