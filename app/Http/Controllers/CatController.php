<?php

namespace App\Http\Controllers;

use App\Models\Cat;
use Illuminate\Http\Request;

class CatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cats = Cat::all();

        return view('Dashboard.pages.cats.view_cats', compact('cats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Dashboard.pages.cats.add_cats');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2|unique:cats,name',
            'img' => 'required|image',
            'status' => 'required|in:active,inactive',
        ]);

        $img_extension = $request->img->extension();
        $tmp_name = $_FILES['img']['tmp_name'];
        $new_img_name = md5(uniqid()).'.'.$img_extension;
        move_uploaded_file($tmp_name, storage_path("app/public/images/cats/$new_img_name"));

        Cat::create([
            'name' => $request->name,
            'img' => $new_img_name,
            'status' => $request->status,
            'description' => $request->description,
        ]);

        return to_route('cat.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cat $cat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cat $cat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cat $cat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cat $cat)
    {
        unlink(storage_path("app/public/images/cats/$cat->img"));

        Cat::where('id', $cat->id)->delete();

        return to_route('cat.index');
    }
}
