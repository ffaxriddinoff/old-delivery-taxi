<?php

namespace App\Http\Controllers;

use App\Http\Services\PartnerService;
use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller {

    /**
     * @var PartnerService
     */
    private PartnerService $service;

    public function __construct(PartnerService $service) {
        $this->service = $service;
    }

    public function index(Request $request) {
        return $this->service->all($request->get('district_id'));
    }

    public function store(PartnerRequest $request) {
        return $this->service->save($request);
    }

    public function show(Partner $partner) {
        return $this->success(['partner' => $partner]);
    }

    public function update(PartnerRequest $request, Partner $partner) {
        return $this->service->upgrade($partner, $request);
    }

    public function destroy(Partner $partner) {
        return $this->service->destroy($partner);
    }
}
