<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcommAddUserRequest;
use App\Models\Cart;
use App\Models\EcommUser;
use App\Models\Merchent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::forUser(auth('dashboard')->user())->authorize('show-dashboard');
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
        Gate::forUser(auth('dashboard')->user())->authorize('show-dashboard');

        return view('Dashboard.pages.customers.add_customer');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EcommAddUserRequest $request)
    {
        Gate::forUser(auth('dashboard')->user())->authorize('show-dashboard');
        $new_img_name = 'user-1.png';
        $this_user = EcommUser::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'role' => $request->role,
            'image' => $new_img_name,
        ]);
        if ($request->role === 'merchent') {
            Merchent::create([
                'user_id' => $this_user->id,
            ]);
        }

        return to_route('customer.index');
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
        Gate::forUser(auth('dashboard')->user())->authorize('show-dashboard');

        $customer = EcommUser::findOrFail($id);

        if ($customer->image !== 'user-1.png') {
            $imagePath = storage_path("app/public/images/clients/{$customer->image}");
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $customer->delete();

        return to_route('customer.index');
    }
}
