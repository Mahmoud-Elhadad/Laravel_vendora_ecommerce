<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\EcommAddUserRequest;
use App\Models\EcommUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthEcommController extends Controller
{
    public function show_register_page()
    {
        if (Auth::guard('ecomm')->check()) {
            return to_route('display.home');
        }

        return view('Ecommerce.pages.register');
    }

    public function store_ecommUser(EcommAddUserRequest $request)
    {
        $new_img_name = '';
        if ($request->hasFile('img')) {
            $img_extension = $request->img->extension();
            $tmp_name = $_FILES['img']['tmp_name'];
            $new_img_name = md5(uniqid()).'.'.$img_extension;
            move_uploaded_file($tmp_name, storage_path("app/public/images/clients/$new_img_name"));

        } else {
            $new_img_name = 'user-1.png';
        }
        EcommUser::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'image' => $new_img_name,
        ]);
        $user = ["image" => $new_img_name , "name" => $request->first_name , "email" => $request->email];
        event(new UserRegistered($user));

        return view('Ecommerce.pages.login');
    }

    public function show_login_page()
    {
        if (Auth::guard('ecomm')->check()) {
            return to_route('display.home');
        }

        return view('Ecommerce.pages.login');
    }

    public function check_login_ecomm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:ecomm_users,email',
            'password' => 'required|string',
        ]);
        if (Auth::guard('ecomm')->attempt($request->except('_token'))) {
            return to_route('display.home');
        } else {
            return to_route('ecomm.show.loginPage')->with('unvalid', 'E-mail or Password is not valid !');
        }
    }

    public function ecomm_logout()
    {
        Auth::guard('ecomm')->logout();

        return to_route('ecomm.show.loginPage');
    }

    public function change_ecomm_user_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|same:confirm',
        ]);

        $client = Auth::guard('ecomm')->user();

        if (! Hash::check($request->current_password, $client->password)) {
            return to_route('ecomm.showAndEdit.profilePage')->with('password', 'Current password is not true');
        } else {
            EcommUser::where('id', $client->id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            return to_route('ecomm.showAndEdit.profilePage')->with('password', 'your password changed successfully');
        }
    }
}
