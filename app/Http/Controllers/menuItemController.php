<?php

namespace App\Http\Controllers;

use App\Models\menuItem;
use Illuminate\Http\Request;

class menuItemController extends Controller
{
   
    public function saveMenu(Request $request)
{
    $menuId = $request->input('menu_id');
    $items = $request->input('items'); // Assuming items is already an array

    // Remove existing menu items before saving new structure
    MenuItem::where('menu_id', $menuId)->delete();

    foreach ($items as $index => $item) {
        $this->saveMenuItem($item, null, $menuId, $index + 1);
    }

    return response()->json(['message' => 'Menu items saved successfully']);
}

private function saveMenuItem($item, $parentId = null, $menuId, $order)
{
    $menuItem = new MenuItem();
    $menuItem->menu_id = $menuId;
    $menuItem->page_id = $item['page_id']; // Use 'page_id' instead of 'id'
    $menuItem->parent_id = $parentId;
    $menuItem->order = $order;
    $menuItem->title = $item['title']; 
    $menuItem->save();

    if (!empty($item['children'])) {
        foreach ($item['children'] as $childIndex => $child) {
            $this->saveMenuItem($child, $menuItem->id, $menuId, $childIndex + 1);
        }
    }
}

public function getMenuItems($menuId)
    {
        $menuItems = MenuItem::where('menu_id', $menuId)->with('children')->orderBy('order')->get();

        return response()->json($menuItems);
    }
}
