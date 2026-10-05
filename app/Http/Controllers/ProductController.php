<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductAddRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Cat;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = collect();
        $num_products = 0;

        if (auth('dashboard')->check()) {
            $products = Product::with('cat', 'image', 'merchent.user')->get();
            $num_products = Product::count();
        } elseif (auth('ecomm')->user()?->merchent?->status === 'approved') {
            $merchantId = auth('ecomm')->user()->merchent->id;
            $products = Product::where('merchent_id', $merchantId)->with('cat', 'image')->get();
            $num_products = Product::where('merchent_id', $merchantId)->count();
        }
        $cats = Cat::all();

        return view('Dashboard.pages.products.view_product', compact('products', 'cats', 'num_products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth('dashboard')->check()) {

            Gate::forUser(auth('dashboard')->user())->authorize('create', Product::class);
        }
        $cats = Cat::all();

        return view('Dashboard.pages.products.add_product', compact('cats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductAddRequest $request)
    {

        if (auth('dashboard')->check()) {

            Gate::forUser(auth('dashboard')->user())->authorize('create', Product::class);
        }

        $product = Product::create($request->except('_token', '_img'));

        if (auth('ecomm')->user()?->merchent?->status === 'approved') {
            Product::where('id', $product->id)->update([
                'merchent_id' => auth('ecomm')->user()->merchent->id,
            ]);
        }
        Image::saveImg($product->id);

        $num_products_cat = Cat::where('id', $request->cat_id)->get('num_products');
        $count = $num_products_cat[0]->num_products;
        $count++;
        Cat::where('id', $request->cat_id)->update([
            'num_products' => $count,
        ]);

        Cache::forget('products.latest');
        Cache::forget('best_products_sold');
        Cache::forget('all_categories');

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
        if (auth('dashboard')->check()) {

            Gate::forUser(auth('dashboard')->user())->authorize('update', $product);
        } elseif (auth('ecomm')->user()?->merchent?->status === 'approved') {
            $merchent = auth('ecomm')->user()->merchent;
            Gate::forUser($merchent)->authorize('merchant-update-product', $product);
        }

        $single_product = Product::where('id', $product->id)->with('image', 'cat')->get();
        $cats = Cat::all();

        return view('Dashboard.pages.products.edit_product', compact('single_product', 'cats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        if (auth('dashboard')->check()) {
            Gate::forUser(auth('dashboard')->user())->authorize('update', $product);
        } elseif (auth('ecomm')->user()?->merchent?->status === 'approved') {
            $merchent = auth('ecomm')->user()->merchent;
            Gate::forUser($merchent)->authorize('merchant-update-product', $product);
        }

        if ($request->hasFile('img')) {
            Product::where('id', $product->id)->update($request->except('_token', '_method', 'img'));
            Image::deleteImg($product->id);
            Image::saveImg($product->id);
        } else {
            Product::where('id', $product->id)->update($request->except('_token', '_method'));
        }

        Cache::forget('products.latest');
        Cache::forget('best_products_sold');
        Cache::forget('all_categories');

        return to_route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (auth('dashboard')->check()) {
            Gate::forUser(auth('dashboard')->user())->authorize('delete', $product);
        } elseif (auth('ecomm')->user()?->merchent?->status === 'approved') {
            $merchent = auth('ecomm')->user()->merchent;
            Gate::forUser($merchent)->authorize('merchant-update-product', $product);
        }

        $num_products_cat = Cat::where('id', $product->cat_id)->get('num_products');
        $count = $num_products_cat[0]->num_products;
        $count--;
        Cat::where('id', $product->cat_id)->update([
            'num_products' => $count,
        ]);

        Image::deleteImg($product->id);
        Product::where('id', $product->id)->delete();

        Cache::forget('products.latest');
        Cache::forget('best_products_sold');
        Cache::forget('all_categories');

        return to_route('product.index');
    }
}
