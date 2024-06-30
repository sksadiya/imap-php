<?php

namespace App\Http\Controllers;

use App\Models\menuItem;
use App\Models\Page;
use Illuminate\Http\Request;

class menuItemController extends Controller
{
   
    public function saveMenu(Request $request)
    {
        $menuId = $request->input('menu_id');
        $items = $request->input('items');

        // Remove existing menu items before saving new structure
        $deleted = MenuItem::where('menu_id', $menuId)->delete();

        // Debugging: Log the result of the deletion operation
        \Log::info("Menu items deleted for menu_id: $menuId, count: $deleted");


        foreach ($items as $index => $item) {
            $this->saveMenuItem($item, null, $menuId, $index + 1);
        }

        return response()->json(['message' => 'Menu items saved successfully']);
    }

    private function saveMenuItem($item, $parentId = null, $menuId, $order)
    {
        $pageId = $item['page_id'];

        $pageExists = Page::where('id', $pageId)->exists();

        if (!$pageExists) {
            \Log::error("Page with ID {$pageId} does not exist.");
            throw new \Exception("Page with ID {$pageId} does not exist.");
        }

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menuId;
        $menuItem->page_id = $pageId;
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
    $menuItems = MenuItem::where('menu_id', $menuId)
        ->whereNull('parent_id') // Get only top-level items
        ->with('children')
        ->orderBy('order')
        ->get()
        ->map(function($item) {
            return $this->transformMenuItem($item);
        });

    return response()->json($menuItems);
}

private function transformMenuItem($item)
{
    $transformed = [
        'id' => $item->page_id, // Use page_id instead of menu item id
        'title' => $item->title,
        'children' => $item->children->map(function($child) {
            return $this->transformMenuItem($child);
        })->toArray()
    ];

    return $transformed;
}

}