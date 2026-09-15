<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthDashController extends Controller
{
    // start Authintication
    public function login_form_dash()
    {
        if (Auth::guard('dashboard')->check()) {
            return to_route('vendora.index');
        } else {

            return view('Dashboard.pages.login');
        }
    }

    public function check_dash(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::guard('dashboard')->attempt($request->except(('_token')))) {

            return to_route('vendora.index');
        } else {
            return to_route('dash.loginForm')->with('error', 'E-mail or Password are not valid');
        }
    }

    public function logout_dash()
    {
        if (Auth::guard('dashboard')->check()) {

            Auth::guard('dashboard')->logout();

            return to_route('dash.loginForm');
        } else {
            return to_route('dash.loginForm');
        }
    }

    // End Authintication

    public function view_profile()
    {
        return view('Dashboard.pages.myProfile');
    }

    public function change_password(Request $request)
    {
        $user = Auth::guard('dashboard')->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return to_route('view.myProfile')->with('password', 'Current password is not true');
        } else {
            Admin::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password),
            ]);

            return to_route('view.myProfile')->with('password', 'your password changed successfully');
        }
    }

    public function change_image(Request $request)
    {
        $request->validate([
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::guard('dashboard')->user();

        if ($request->hasFile('img')) {
            // حذف الصورة القديمة إذا كانت موجودة
            $oldImagePath = public_path('storage/images/admins/'.$user->img);
            if (file_exists($oldImagePath) && $user->img !== 'default.png') {
                unlink($oldImagePath);
            }

            // رفع الصورة الجديدة
            $image = $request->file('img');
            $imageName = time().'_'.$user->id.'.'.$image->getClientOriginalExtension();
            $image->move(public_path('storage/images/admins'), $imageName);

            // تحديث قاعدة البيانات
            Admin::where('id', $user->id)->update([
                'img' => $imageName,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Image updated successfully',
                'image_url' => asset('storage/images/admins/'.$imageName),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No image file uploaded',
        ], 400);
    }
}
