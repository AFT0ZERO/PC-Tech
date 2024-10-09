<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{

    public function index($id)
    {
        $Product = Product::findOrFail($id);
        $ProductImage = ProductImage::where('product_id',$id)->get();
        return view('admin.product.productImage.index',['ProductImages'=>$ProductImage , 'product'=>$Product]);
    }



    public function store(Request $request,$id)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:png,jpg,jpeg,webp'
        ]);

        $product = Product::findOrFail($id);

        $imageData = [];
        if($files = $request->file('images')){

            foreach($files as $key => $file){
                $extension = $file->getClientOriginalExtension();
                $filename = $key.'-'.time(). '.' .$extension;

                $path = "uploads/ProductImage/";

                $file->move($path, $filename);

                $imageData[] = [
                    'product_id' => $product->id,
                    'image' => $path.$filename,
                ];
            }
        }

        ProductImage::insert($imageData);

        return redirect()->back()->with('status', 'Uploaded Successfully');
    }


    public function destroy(ProductImage $productImage ,$id)
    {
        $product= request()->product_id;

        $ProductImage = ProductImage::findOrFail($id);
        if(File::exists($ProductImage->path)){
            File::delete($ProductImage->path);
        }
        $ProductImage->delete();
        return to_route('product.upload.images',$product)->with('status', 'Image Deleted');
    }
}
