<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class adminLoginController extends Controller
{
  public function index() {
    return view('auth.login');
  }
  public function authenticate(Request $request) {
    $validator = Validator::make($request->all(),[
       'email'=> 'required|email',
       'password' => 'required' 
    ]);

    if($validator->passes()) {

        if(Auth::guard('admin')
        ->attempt(['email'=>$request->email, 'password'=>$request->password],
        $request->get('remember'))) {

            $admin = Auth::guard('admin')->user();

            if($admin->role == 1) {
            return redirect()->route('admin.dashboard');
            } else {
                Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('error' ,'You are not authorize to access admin');

            }
        } else {
            return redirect()->route('admin.login')->with('error' ,'User Credentials are invalid');

        }


    } else {
        return redirect()->route('admin.login')->withErrors($validator)->withInput($request->all());
    }
}
  public function dashboard() {
    return view('auth.dashboard');
  }

  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
  }
}
