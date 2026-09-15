<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcommAddUserRequest;
use App\Models\Cart;
use App\Models\EcommUser;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $customers = EcommUser::withCount(['carts', 'wishlists'])
            ->with('carts.product')
            ->get()
            ->map(function (EcommUser $customer) {
                $customer->total_spent = $customer->carts->sum(
                    fn (Cart $cart) => $cart->count * ($cart->product?->price ?? 0)
                );

                return $customer;
            });

        $total_spent = $customers->sum('total_spent');
        $num_customers = $customers->count();

        return view('Dashboard.pages.customers.customers', compact('customers', 'num_customers', 'total_spent'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("Dashboard.pages.customers.add_customer");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EcommAddUserRequest $request)
    {
         $new_img_name = 'user-1.png';
         EcommUser::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'image' => $new_img_name,
        ]);

        return to_route("customer.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $img = EcommUser::where("id" , $id)->get("image");
        $img_name = $img[0]->image;

        if($img_name !== "user-1.png"){
            unlink(storage_path("app/public/images/clients/$img_name"));
        }

        EcommUser::where("id" , $id)->delete();

        return to_route("customer.index");
    }
}
