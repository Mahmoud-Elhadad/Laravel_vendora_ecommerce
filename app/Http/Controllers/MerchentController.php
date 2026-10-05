<?php

namespace App\Http\Controllers;

use App\Events\MerchantApproved;
use App\Models\EcommUser;
use App\Models\Merchent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MerchentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::forUser(auth("dashboard")->user())->authorize("show-dashboard");
        $customers = EcommUser::with('merchent')->has('merchent')->get();

        $num_customers = $customers->count();

        return view('Dashboard.pages.merchants.merchant', compact('customers', 'num_customers'));
    }

    public function approved(int $id)
    {
        Gate::forUser(auth("dashboard")->user())->authorize("show-dashboard");
        Merchent::where('id', $id)->update([
            'approved_at' => now(),
            'status' => 'approved',
        ]);

         $merchant = Merchent::with("user")->findOrFail($id);

        event(new MerchantApproved($merchant));
        return to_route('merchents.index');
    }

    public function rejected(int $id)
    {
        Gate::forUser(auth("dashboard")->user())->authorize("show-dashboard");
        Merchent::where('id', $id)->update([
            'rejected_at' => now(),
            'status' => 'rejected',
        ]);

        return to_route('merchents.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Merchent $merchent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Merchent $merchent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Merchent $merchent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Merchent $merchent)
    {
        Gate::forUser(auth("dashboard")->user())->authorize("delete-access");
        EcommUser::where("id" , $merchent->user_id)->update([
            "role" => "customer"
        ]);
        $merchent->delete();
        return to_route("merchents.index");
    }
}
