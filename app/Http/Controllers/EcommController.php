<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcommUpdateUserRequest;
use App\Models\Cart;
use App\Models\Cat;
use App\Models\EcommUser;
use App\Models\Product;
use App\Models\UserMessage;
use App\Models\Whilist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EcommController extends Controller
{
    public function display_home()
    {
        $products = Product::latest()->with('image', 'cat')->get();
        $best_products_sold = Product::orderByDesc('sold_quantity')->with('cat', 'image')->take(8)->get();

        $wishlistedIds = Auth::guard('ecomm')->check()
            ? Whilist::where('user_id', Auth::guard('ecomm')->id())->pluck('product_id')->toArray()
            : [];

        $all_categories = Cat::where("num_products" , ">" , 0)->get();

        return view('Ecommerce.pages.index', compact('products', 'wishlistedIds', 'best_products_sold', 'all_categories'));
    }

    public function product_details(int $id)
    {
        $product = Product::with('image', 'cat')->find($id);
        $relatedProducts = Product::where('cat_id', $product->cat_id)
            ->where('id', '!=', $id)->with('image', 'cat')
            ->get();

        $wishlistedIds = Auth::guard('ecomm')->check()
            ? Whilist::where('user_id', Auth::guard('ecomm')->id())->pluck('product_id')->toArray()
            : [];

        return view('Ecommerce.pages.product-details', compact('product', 'relatedProducts', 'wishlistedIds'));
    }

    public function quick_view(string $id)
    {
        $product = Product::with('image', 'cat')->find((int) $id);

        if (! $product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $discountedPrice = $product->price - ($product->price * $product->discount / 100);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'category' => $product->cat->name ?? '',
            'description' => $product->description,
            'price' => round($discountedPrice, 2),
            'old_price' => $product->discount > 0 ? (float) $product->price : null,
            'discount' => (int) $product->discount,
            'stock' => (int) $product->count,
            'image' => asset('storage/images/products/'.$product->image[0]['name']),
            'url' => route('product.details', $product->id),
        ]);
    }

    public function show_message()
    {
        return view('Ecommerce.pages.contact');
    }

    public function store_message(Request $request)
    {
        UserMessage::create($request->except('_token'));
    }

    public function store_cart(Request $request)
    {
        $user_id = Auth::guard('ecomm')->user()->id;
        $product_id = $request->product_id;
        $count = max(1, (int) $request->count);

        $product = Product::find($product_id);

        if (! $product) {
            return response()->json(['status' => 'error', 'message' => 'Product not found.'], 404);
        }

        if ($product->count < $count) {
            return response()->json([
                'status' => 'error',
                'message' => "Only {$product->count} item(s) available in stock.",
            ], 422);
        }

        $cart_first = Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();

        if (! $cart_first) {
            Cart::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'count' => $count,
            ]);
        } else {
            if ($product->count < ($cart_first->count + $count)) {
                $remaining = $product->count - $cart_first->count;

                return response()->json([
                    'status' => 'error',
                    'message' => $remaining > 0
                        ? "Only {$remaining} more item(s) can be added (already {$cart_first->count} in your cart)."
                        : 'You already have the maximum available quantity in your cart.',
                ], 422);
            }

            $cart_first->increment('count', $count);
        }

        $product->decrement('count', $count);
        $product->increment('sold_quantity', $count);

        return response()->json(['status' => 'success', 'message' => "{$product->name} added to cart."]);
    }

    public function show_cart()
    {

        if (! Auth::guard('ecomm')->check()) {
            return to_route('ecomm.show.loginPage');
        }

        $user_id = Auth::guard('ecomm')->user()->id;
        $cart_products = Cart::where('user_id', $user_id)->with('product.image', 'product.cat')->get();

        return view('Ecommerce.pages.cart', compact('cart_products'));
    }

    public function remove_item_cart(Request $request)
    {
        $cart_id = $request->cart_id;
        $cart = Cart::find($cart_id);

        if ($cart) {
            $product = Product::find($cart->product_id);
            if ($product) {
                $product->decrement('sold_quantity', $cart->count);
                $product->increment('count', $cart->count);
            }
            $cart->delete();
        }
    }

    public function clear_all_cart()
    {
        $user_id = Auth::guard('ecomm')->user()->id;
        $cart_products = Cart::where('user_id', $user_id)->with('product')->get();

        if ($cart_products) {
            foreach ($cart_products as $cart) {
                $product = Product::find($cart->product_id);
                if ($product) {
                    $product->decrement('sold_quantity', $cart->count);
                    $product->increment('count', $cart->count);
                }
                $cart->delete();
            }
        }

        return to_route('show.cart');
    }

    public function show_account_page()
    {
        $user_id = Auth::guard('ecomm')->user()->id;
        $subTotal = 0;

        $all_user_carts = Cart::where('user_id', $user_id)->get();
        foreach ($all_user_carts as $cart) {
            $product = Product::find($cart->product_id);
            if ($product) {
                $subTotal += $product->price * $cart->count;
            }
        }
        $num_carts_in_account = Cart::where('user_id', $user_id)->count();
        $num_whilists_in_account = Whilist::where('user_id', $user_id)->count();

        return view('Ecommerce.pages.myAccount', compact('num_carts_in_account', 'subTotal', 'num_whilists_in_account'));
    }

    public function show_profile_and_edit_page()
    {
        $user_id = Auth::guard('ecomm')->user()->id;
        $client = EcommUser::find($user_id);

        return view('Ecommerce.pages.editProfile', compact('client'));
    }

    public function update_eccomUser(EcommUpdateUserRequest $request)
    {
        $user_id = Auth::guard('ecomm')->user()->id;

        if ($request->hasFile('image')) {
            $client = EcommUser::find($user_id);
            if ($client->image !== 'user-1.png') {
                unlink(storage_path('app/public/images/clients/'.$client->image)); // remove old image
            }

            $img_extension = $request->image->extension();
            $tmp_name = $_FILES['image']['tmp_name'];
            $new_image_name = md5(uniqid()).'.'.$img_extension;
            move_uploaded_file($tmp_name, storage_path("app/public/images/clients/$new_image_name"));

            EcommUser::where('id', $user_id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'image' => $new_image_name,
            ]);

        } else {
            EcommUser::where('id', $user_id)->update($request->except('_token', 'image'));
        }

        return to_route('ecomm.show.accountPage');
    }

    public function show_whilist_page()
    {
        $user_id = Auth::guard('ecomm')->user()->id;
        $whilists = Whilist::where('user_id', $user_id)->with('product.image')->get();

        return view('Ecommerce.pages.whilist', compact('whilists'));
    }

    public function store_whilist(Request $request)
    {
        $user_id = Auth::guard('ecomm')->user()->id;

        $product = Product::find($request->product_id);
        $productName = $product ? $product->name : 'Item';

        $whilist_first = Whilist::where('user_id', $user_id)->where('product_id', $request->product_id)->first();

        if (! $whilist_first) {
            Whilist::create([
                'user_id' => $user_id,
                'product_id' => $request->product_id,
            ]);

            return response()->json(['status' => 'added', 'name' => $productName]);
        }

        $whilist_first->delete();

        return response()->json(['status' => 'removed', 'name' => $productName]);
    }

    public function store_all_whilist_items_in_cart(Request $request)
    {
        $user_id = Auth::guard('ecomm')->user()->id;
        $_token = $request->_token;

        $whilist_products = Whilist::where('user_id', $user_id)->with('product')->get();

        foreach ($whilist_products as $whilist) {

            $store_request = new Request;
            $store_request->merge([
                '_token' => $_token,
                'product_id' => $whilist->product_id,
            ]);

            $this->store_cart($store_request);
        }

        return to_route('ecomm.whilistPage')->with('whilist', 'All items are added successfully in your cart !');
    }

    public function clear_all_whilists(Request $request)
    {
        $user_id = Auth::guard('ecomm')->user()->id;
        Whilist::where('user_id', $user_id)->delete();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'cleared', 'count' => 0]);
        }

        return to_route('ecomm.whilistPage');
    }

    public function show_shop_page(Request $request)
    {
        $query = Product::with('image', 'cat');

        if ($request->filled('cat')) {
            $query->where('cat_id', $request->cat);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $sortOptions = [
            'newest' => fn ($q) => $q->latest(),
            'price_asc' => fn ($q) => $q->orderBy('price'),
            'price_desc' => fn ($q) => $q->orderByDesc('price'),
            'popular' => fn ($q) => $q->orderByDesc('sold_quantity'),
        ];

        $sort = $request->get('sort', 'newest');
        $sortCallback = $sortOptions[$sort] ?? $sortOptions['newest'];
        $sortCallback($query);

        $products = $query->paginate(12)->withQueryString();

        $categories = Cat::orderBy('name')->get();

        $wishlistedIds = Auth::guard('ecomm')->check()
            ? Whilist::where('user_id', Auth::guard('ecomm')->id())->pluck('product_id')->toArray()
            : [];

        return view('Ecommerce.pages.shop', compact('products', 'categories', 'wishlistedIds', 'sort'));
    }

    public function show_category_page(Request $request)
    {
        $query = Product::with('image', 'cat');

        if ($request->filled('cat')) {
            $query->where('cat_id', $request->cat);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $sortOptions = [
            'newest' => fn ($q) => $q->latest(),
            'price_asc' => fn ($q) => $q->orderBy('price'),
            'price_desc' => fn ($q) => $q->orderByDesc('price'),
            'popular' => fn ($q) => $q->orderByDesc('sold_quantity'),
        ];

        $sort = $request->get('sort', 'newest');
        $sortCallback = $sortOptions[$sort] ?? $sortOptions['newest'];
        $sortCallback($query);

        $products = $query->paginate(12)->withQueryString();

        $categories = Cat::orderBy('name')->get();

        $wishlistedIds = Auth::guard('ecomm')->check()
            ? Whilist::where('user_id', Auth::guard('ecomm')->id())->pluck('product_id')->toArray()
            : [];

        return view('Ecommerce.pages.allCategoryPage', compact('products', 'categories', 'wishlistedIds', 'sort'));
    }

    public function show_about_page()
    {
        return view('Ecommerce.pages.about');
    }

    public function show_FAQ_page()
    {
        return view('Ecommerce.pages.FAQ');
    }

    public function show_privacy_page()
    {
        return view('Ecommerce.pages.privacy_policy');
    }

    public function show_terms_page()
    {
        return view('Ecommerce.pages.terms_of_Service');
    }

    public function show_error_page()
    {
        return view('Ecommerce.pages.error404');
    }
}
