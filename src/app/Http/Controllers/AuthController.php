<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function redirectTo()
    {
        $user = Auth::user();

        if($user->role === 1){
            return '/admin/attendance/list';
        }else{
            return '/attendance';
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function createAdmin()
    {
        return view('admin.login');
    }

    public function storeAdmin(Request $request)
    { 
        $credentials = $request->only('email','password');
        if(Auth::guard('admin')->attempt($credentials)){
            return redirect()->route('admin.attendance.daily');
        }
        return back()->withError([
            'email' => 'ログイン情報が正しくありません',
        ])->withInput();
    }
}
