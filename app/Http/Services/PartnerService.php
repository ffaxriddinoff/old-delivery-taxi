<?php


namespace App\Http\Services;


use App\Models\Partner;

class PartnerService extends CRUDService
{
    public function __construct(Partner $model = null) {
        parent::__construct($model ?? new Partner());
    }

    public function all($district_id) {
        return $this->success([
            'partners' => Partner::query()->byDistrict($district_id)->get()
        ]);
    }

    public function save($request) {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        if ($request->hasFile('img')) {
            $data['img'] = $this->saveImage($request->file('img'), 'partners');
        }
        return parent::store($data);
    }

    public function upgrade($partner, $request) {
        $data = $request->validated();
        if ($request->get('password')) {
            $data['password'] = bcrypt($data['password']);
        }

        if ($request->hasFile('img')) {
            $data['img'] = $this->saveImage($request->file('img'), 'partners');
            $this->deleteFile($partner->img, 'partners');
        }
        return parent::update($partner, $data);
    }
}
