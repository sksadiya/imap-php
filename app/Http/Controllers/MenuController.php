<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;
class MenuController extends Controller
{
    public function list() {
        $menus = Menu::all() ;
        return view('auth.menu.list',compact('menus'));
    }
    public function store(Request $request) {
        $validator = Validator::make($request->all() ,[
          'title' => 'required|min:3',
          'location' => 'required|string'
        ] );
        if($validator->passes()) { 
          $menu = new Menu();
          $menu->title = $request->title;
          $menu->slug = Str::slug($request->title);
          $menu->location = $request->location;
          $menu->save();
    
          return redirect()->back()->with('success' , 'menu created successfully');
    
        } else {
          return redirect()->route('menu.list')->withErrors($validator)->withInput($request->all());
        }
    
      }
      public function edit($id) {
        $menu = Menu::find($id);
        if($menu == null) {
            return redirect()->route('menu.list')->with('error','Record Not found');
        }
        return view('auth.menu.edit',compact('menu'));
    }
    public function update(Request $request ,$id) {
        $menu = Menu::find($id);
        if($menu == null) {
            return redirect()->route('menu.list')->with('error','Record Not found');
        }
        $validator = Validator::make($request->all() ,[
          'title' => 'required|min:3',
          'location' => 'required|string'
        ] );
        
        if($validator->fails()) {
            return redirect()->route('menu.edit',$id)->withErrors($validator->errors())->withInput();
        }
        $menu->title = $request->title;
        $menu->slug = Str::slug($request->content);
        $menu->location = $request->location;
        $menu->save();
       
        return redirect()->route('menu.list')->with('success','menu updated successfull');
    }
    public function delete($id) {
        $menu = Menu::find($id);
        if($menu == null) {
            return redirect()->route('menu.list')->with('error','Record Not found');
        }
        $menu->delete();
        return redirect()->route('menu.list')->with('success','menu deleted successfull');
    }
    public function settings(Request $request) {
        $menus = Menu::all();
        $pages = Page::all();
    
        // Fetch the selected menu ID from the request or default to the first menu
        $selectedMenuId = $request->input('menu_id', $menus->first()->id ?? null);
    
        // Fetch menu items for the selected menu
        $menuItems = MenuItem::where('menu_id', $selectedMenuId)->orderBy('order')->get();
    
        return view('auth.menu.settings', compact('menus', 'pages', 'menuItems', 'selectedMenuId'));
    }    public function addPages(Request $request)
    {
        $menuId = $request->input('menu_id');
        $pageIds = $request->input('page_ids');
    
        foreach ($pageIds as $pageId) {
            MenuItem::create([
                'menu_id' => $menuId,
                'page_id' => $pageId,
                'parent_id' => null, // or handle nesting logic
                'order' => 0, // or handle ordering logic
            ]);
        }
    
        return redirect()->back()->with('success', 'Pages added to menu successfully.');
    }
    
    public function saveOrder(Request $request)
    {
        $orderedMenuItems = $request->input('orderedMenuItems');
    
        foreach ($orderedMenuItems as $index => $itemId) {
            MenuItem::where('id', $itemId)->update(['order' => $index]);
        }
    
        return response()->json(['success' => true]);
    }
    
 
}
