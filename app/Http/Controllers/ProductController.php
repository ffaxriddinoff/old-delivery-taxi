<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductService;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductController extends Controller {

    /**
     * @var ProductService
     */
    private ProductService $service;

    public function __construct(ProductService $service) {
        $this->service = $service;
    }

    public function index(Request $request) {
        return $this->service->index($request);
    }

    public function categoryProducts($partner) {
        return $this->service->categoryProducts($partner);
    }

    public function store(ProductRequest $request) {
        return $this->service->save($request);
    }

    public function update(ProductRequest $request, Product $product) {
        return $this->service->upgrade($product, $request);
    }

    public function destroy(Product $product) {
        return $this->service->destroy($product);
    }
}
