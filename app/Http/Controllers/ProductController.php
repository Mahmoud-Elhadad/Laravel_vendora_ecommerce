<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductAddRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Cat;
use App\Models\Image;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $products = Product::with('cat', 'image')->get();
        $num_products = Product::count();
        $cats = Cat::all();

        return view('Dashboard.pages.products.view_product', compact('products', 'cats', 'num_products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cats = Cat::all();

        return view('Dashboard.pages.products.add_product', compact('cats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductAddRequest $request)
    {
        $product = Product::create($request->except('_token', '_img'));
        Image::saveImg($product->id);

        $num_products_cat = Cat::where('id', $request->cat_id)->get('num_products');
        $count = $num_products_cat[0]->num_products;
        $count++;
        Cat::where('id', $request->cat_id)->update([
            'num_products' => $count,
        ]);

        return to_route('product.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $single_product = Product::where('id', $product->id)->with('image', 'cat')->get();
        $cats = Cat::all();

        return view('Dashboard.pages.products.edit_product', compact('single_product', 'cats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        if ($request->hasFile('img')) {

            $data = Product::where('id', $product->id)->update($request->except('_token', '_method', 'img'));
            Image::deleteImg($product->id);
            Image::saveImg($product->id);
        } else {
            Product::where('id', $product->id)->update($request->except('_token', '_method'));
        }

        return to_route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

        $num_products_cat = Cat::where('id', $product->cat_id)->get('num_products');
        $count = $num_products_cat[0]->num_products;
        $count--;
        Cat::where('id', $product->cat_id)->update([
            'num_products' => $count,
        ]);

        Image::deleteImg($product->id);
        Product::where('id', $product->id)->delete();

        return to_route('product.index');
    }
}
