<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminAddRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\Admin;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::all();

        return view('Dashboard.pages.admins.view', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Dashboard.pages.admins.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminAddRequest $request)
    {
        $img_extension = $request->img->extension();
        $tmp_name = $_FILES['img']['tmp_name'];
        $new_img_name = md5(uniqid()).'.'.$img_extension;
        move_uploaded_file($tmp_name, storage_path("app/public/images/admins/$new_img_name"));

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'gender' => $request->gender,
            'phone' => $request->phone,
            'location' => $request->location,
            'age' => $request->age,
            'img' => $new_img_name,
        ]);

        return to_route('admin.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {

        return view('Dashboard.pages.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUpdateRequest $request, Admin $admin)
    {
        if ($request->hasFile('img')) {
            if (file_exists(storage_path("app/public/images/admins/$admin->img"))) {
                unlink(storage_path("app/public/images/admins/$admin->img"));
            }
            $img_extension = $request->img->extension();
            $tmp_name = $_FILES['img']['tmp_name'];
            $new_img_name = md5(uniqid()).'.'.$img_extension;
            move_uploaded_file($tmp_name, storage_path("app/public/images/admins/$new_img_name"));

            Admin::where('id', $admin->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'location' => $request->location,
                'age' => $request->age,
                'img' => $new_img_name,
            ]);
        } else {
            Admin::where('id', $admin->id)->update($request->except('_token', '_method'));
        }

        return to_route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        if (file_exists(storage_path("app/public/images/admins/$admin->img"))) {
            unlink(storage_path("app/public/images/admins/$admin->img"));
        }

        $adminName = $admin->name;
        Admin::where('id', $admin->id)->delete();

        return to_route('admin.index')->with('success', "Staff member '{$adminName}' has been deleted successfully.");
    }
}
