<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Services\ProductImageService;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function __construct(private ProductImageService $productImageService)
    {
    }

    public function index($id)
    {
        $data = $this->productImageService->getIndexData($id);

        return view('admin.product.productImage.index', $data);
    }



    public function store(Request $request, $id)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:png,jpg,jpeg,webp'
        ]);

        $this->productImageService->uploadImages($id, $request->file('images') ?? []);

        return redirect()->back()->with('status', 'Uploaded Successfully');
    }


    public function destroy(ProductImage $productImage, $id)
    {
        $product = request()->product_id;

        $this->productImageService->deleteImage($id, $product);

        return to_route('product.upload.images', $product)->with('status', 'Image Deleted');
    }
}
