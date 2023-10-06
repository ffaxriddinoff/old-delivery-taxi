<?php


namespace App\Http\Services;

use App\Models\Category;
use App\Models\Product;


class ProductService extends CRUDService
{
    public function __construct(Product $model = null) {
        parent::__construct($model ?? new Product());
    }

    public function index($request) {
        $category = Category::query()->findOrFail($request->category_id);
        return $this->success(['products' => $category->products]);
    }

    public function categoryProducts($partner) {
        return $this->success([
            'categories' => Category::query()->with('products')->where('partner_id', $partner)->get()
        ]);
    }

    public function save($request) {
        $data = $request->validated();
        if ($request->hasFile('img')) {
            $data['img'] = $this->saveImage($request->file('img'), 'products');
        }
        return parent::store($data);
    }

    public function upgrade($partner, $request) {
        $data = $request->validated();
        if ($request->hasFile('img')) {
            $data['img'] = $this->saveImage($request->file('img'), 'products');
            $this->deleteFile($partner->img, 'products');
        }
        return parent::update($partner, $data);
    }
}
