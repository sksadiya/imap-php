<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
    $pages = Page::all();
    return view('auth.dashboard' ,compact('pages'));
  }

  public function store(Request $request) {
    $validator = Validator::make($request->all() ,[
      'title' => 'required|min:3',
      'content' => 'min:10|nullable',
      'status' => 'required',
      'parent_page' => 'nullable'
    ] );
    if($validator->passes()) { 
      $page = new Page();
      $page->title = $request->title;
      $page->content = $request->content;
      $page->status = $request->status;
      $page->slug =  Str::slug($request->title);
      $page->parent_id =$request->parent_page;
      $page->save();

      return redirect()->back()->with('success' , 'page created successfully');

    } else {
      return redirect()->route('admin.dashboard')->withErrors($validator)->withInput($request->all());
    }

  }
  public function edit($id) {
    $page = Page::find($id);
    $pages = Page::all();
    if($page == null) {
        return redirect()->route('admin.dashboard')->with('error','Record Not found');
    }
    return view('auth.editPage',compact('page','pages'));
}
public function update(Request $request ,$id) {
  $page = Page::find($id);
    if($page == null) {
        return redirect()->route('admin.dashboard')->with('error','Record Not found');
    }
    $validator = Validator::make($request->all() ,[
      'title' => 'required|min:3',
      'content' => 'min:10|nullable',
      'status' => 'required',
      'parent_page' => 'nullable'
    ] );
    
    if($validator->fails()) {
        return redirect()->route('page.edit',$id)->withErrors($validator->errors())->withInput();
    }
    $page->title = $request->title;
    $page->content = $request->content;
    $page->status = $request->status;
    $page->slug =  Str::slug($request->title);
    $page->parent_id =$request->parent_page;
    $page->save();
   
    return redirect()->route('admin.dashboard')->with('success','menu updated successfull');
}
public function delete($id) {
    $page = Page::find($id);
    if($page == null) {
        return redirect()->route('admin.dashboard')->with('error','Record Not found');
    }
    $page->delete();
    return redirect()->route('admin.dashboard')->with('success','page deleted successfull');
}
  public function logout()
  {
    Auth::guard('admin')->logout();
    return redirect()->route('admin.login');
  }
}
