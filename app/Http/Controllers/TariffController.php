<?php

namespace App\Http\Controllers;

use App\Http\Requests\TariffRequest;
use App\Models\Tariff;
use Illuminate\Http\Request;


class TariffController extends Controller {

    public function index(Request $request) {
        return $this->success([
            'tariffs' => Tariff::query()->byDistrict($request->get('district_id'))->get()
        ]);
    }

    public function show(Tariff $tariff) {
        return $this->success($tariff);
    }

    public function store(TariffRequest $request) {
        $data = $request->validated();
        if ($request->hasFile('img'))
            $data['img'] = $this->saveImage($request->file('img'), 'tariffs');

        $tariff = Tariff::query()->create($data);
        return $this->success(['tariff' => $tariff]);
    }

    public function update(TariffRequest $request, Tariff $tariff) {
        $data = $request->validated();
        if ($request->hasFile('img')) {
            $data['img'] = $this->saveImage($request->file('img'), 'tariffs');
            $this->deleteFile($tariff->img, 'tariffs');
        }

        $tariff->update($data);
        return $this->success(['tariff' => $tariff]);
    }

    public function destroy(Tariff $tariff) {
        $tariff->delete();
        return $this->success([]);
    }
}
