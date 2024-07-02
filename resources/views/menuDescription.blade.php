<!-- this is how you create menu_items  -->
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained();
            $table->unsignedBigInteger('item_id');
            $table->string('item_type');
            $table->foreignId('parent_id')->nullable()->constrained('menu_items');
            $table->integer('order');
            $table->string('title');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
this is how you declared menus table

id
name
slug   {if you want}
location
created_at
updated_at


this is how you pass data to view file for menu setting page

use App\Models\Page;
use App\Models\Category;
use App\Models\Post;

public function showMenuForm() {
    $menus = Menu::all();
    $pages = Page::all();
    $categories = Category::all();
    $posts = Post::all();

    return view('your.view.file', compact('menus', 'pages', 'categories', 'posts'));
}



this is your view 


@extends('layouts.extend')

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav" id="menu-navbar">
                <!-- Menu items will be dynamically added here -->
            </ul>
        </div>
    </div>
</nav>

@section('content')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('auth.sidebar')
        </div>
        <div class="col-md-9">
            @include('layout.message')

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">Menus</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="menu_id" class="form-label">Menu</label>
                                <select class="form-control" name="menu_id" id="menu_id">
                                    <option value="">select menu</option>
                                    @foreach ($menus as $menu)
                                    <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-primary" id="save-menu-btn">Save Menu</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Pages Section -->
                <div class="col-md-4 py-2">
                    <div class="card">
                        <div class="card-header bg-primary text-white">Pages</div>
                        <div class="card-body">
                            <h4>Pages</h4>
                            <div class="form-check" id="pages-list">
                                @foreach ($pages as $page)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $page->id }}" name="select-pages[]" id="page_id_{{ $page->id }}">
                                    <label class="form-check-label" for="page_id_{{ $page->id }}">
                                        {{ $page->title }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="select-all-pages">
                                    <label class="form-check-label" for="select-all-pages">
                                        Select All
                                    </label>
                                </div>
                                <button class="btn btn-primary btn-sm" type="button" id="add-pages-to-menu">Add to Menu</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Section -->
                <div class="col-md-4 py-2">
                    <div class="card">
                        <div class="card-header bg-primary text-white">Categories</div>
                        <div class="card-body">
                            <h4>Categories</h4>
                            <div class="form-check" id="categories-list">
                                @foreach ($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $category->id }}" name="select-categories[]" id="category_id_{{ $category->id }}">
                                    <label class="form-check-label" for="category_id_{{ $category->id }}">
                                        {{ $category->title }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="select-all-categories">
                                    <label class="form-check-label" for="select-all-categories">
                                        Select All
                                    </label>
                                </div>
                                <button class="btn btn-primary btn-sm" type="button" id="add-categories-to-menu">Add to Menu</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posts Section -->
                <div class="col-md-4 py-2">
                    <div class="card">
                        <div class="card-header bg-primary text-white">Posts</div>
                        <div class="card-body">
                            <h4>Posts</h4>
                            <div class="form-check" id="posts-list">
                                @foreach ($posts as $post)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $post->id }}" name="select-posts[]" id="post_id_{{ $post->id }}">
                                    <label class="form-check-label" for="post_id_{{ $post->id }}">
                                        {{ $post->title }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="select-all-posts">
                                    <label class="form-check-label" for="select-all-posts">
                                        Select All
                                    </label>
                                </div>
                                <button class="btn btn-primary btn-sm" type="button" id="add-posts-to-menu">Add to Menu</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menu Items Section -->
                <div class="col-md-8 py-2">
                    <div class="dd list-group w-100" id="nestable">
                        <ol class="dd-list">
                            <!-- Menu items will be dynamically added here -->
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .dd-item .remove-item {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: black;
        cursor: pointer;
    }
</style>
@endsection

@section('script')
<script src="{{ asset('assets/js/jquery.nestable.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script>
    $(document).ready(function () {
        // Select all functionality for pages, categories, and posts
        $('#select-all-pages').click(function () {
            $('#pages-list :checkbox').prop('checked', this.checked);
        });
        $('#select-all-categories').click(function () {
            $('#categories-list :checkbox').prop('checked', this.checked);
        });
        $('#select-all-posts').click(function () {
            $('#posts-list :checkbox').prop('checked', this.checked);
        });

        // Adding pages, categories, and posts to the menu
        $('#add-pages-to-menu').click(function () {
            addItemsToMenu('pages-list', 'page');
        });
        $('#add-categories-to-menu').click(function () {
            addItemsToMenu('categories-list', 'category');
        });
        $('#add-posts-to-menu').click(function () {
            addItemsToMenu('posts-list', 'post');
        });

        function addItemsToMenu(listId, itemType) {
            $('#' + listId + ' :checkbox:checked').each(function () {
                var itemId = $(this).val();
                var itemTitle = $(this).next('label').text().trim();

                $('#nestable > .dd-list').append(`
                    <li class="dd-item" data-id="${itemType}-${itemId}" data-title="${itemTitle}" data-type="${itemType}">
                        <div class="dd-handle">${itemTitle}</div>
                        <button class="btn btn-danger btn-sm remove-item"><i class="fa-regular fa-circle-xmark"></i></button>
                    </li>
                `);
            });

            $('#' + listId + ' :checkbox').prop('checked', false);
            $('#nestable').nestable('reload');
            updateMenuOutput($('#nestable'));
        }

        $('#save-menu-btn').click(function () {
            var menuId = $('#menu_id').val();
            var items = serializeNestable($('#nestable').nestable('serialize'));

            $.ajax({
                url: '{{ route('menu.save') }}',
                method: 'POST',
                data: {
                    menu_id: menuId,
                    items: items,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    alert('Menu items saved successfully!');
                },
                error: function (xhr, status, error) {
                    alert('Failed to save menu items: ' + error);
                    console.error(xhr.responseText);
                }
            });
        });

        $('#menu_id').change(function () {
            var menuId = $('#menu_id').val();
            const fetchRoute = "{{ route('menu-items.get', 'ID') }}".replace('ID', menuId);

            $.ajax({
                url: fetchRoute,
                method: 'GET',
                data: { _token: '{{ csrf_token() }}' },
                success: function (response) {
                    renderMenuItems(response);
                },
                error: function (xhr, status, error) {
                    alert('Failed to fetch menu items: ' + error);
                    console.error(xhr.responseText);
                }
            });
        });

        function renderMenuItems(menuItems) {
            var menuItemsList = $('#nestable > .dd-list');
            menuItemsList.empty();

            menuItems.forEach(function (item) {
                renderMenuItem(item, menuItemsList);
            });

            $('#nestable').nestable('reload');
            updateMenuOutput($('#nestable'));
        }

        function renderMenuItem(item, parent) {
            var listItem = $(`
                <li class="dd-item" data-id="${item.id}" data-title="${item.title}" data-type="${item.type}">
                    <div class="dd-handle">${item.title}</div>
                    <button class="btn btn-danger btn-sm remove-item"><i class="fa-regular fa-circle-xmark"></i></button>
                </li>
            `);

            if (item.children && item.children.length > 0) {
                var nestedList = $('<ol class="dd-list"></ol>');
                item.children.forEach(function (child) {
                    renderMenuItem(child, nestedList);
                });
                listItem.append(nestedList);
            }

            parent.append(listItem);
        }

        $('#nestable').nestable({
            group: 1,
            maxDepth: 3,
            callback: function (l, e) {
                updateMenuOutput(l);
            }
        });

        $(document).on('click', '.remove-item', function (e) {
            e.preventDefault();
            $(this).closest('.dd-item').remove();
            updateMenuOutput($('#nestable'));
        });

        var serializeNestable = function (items) {
            var serialized = [];
            $(items).each(function () {
                var item = this;
                var children = [];
                if (item.children) {
                    children = serializeNestable(item.children);
                }
                serialized.push({
                    id: item.id,
                    title: item.title,
                    type: item.type,
                    children: children
                });
            });
            return serialized;
        };

        var updateMenuOutput = function (e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };

        $('#nestable-menu').on('click', function (e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('#nestable').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('#nestable').nestable('collapseAll');
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
    fetchMenuItems('header');

    function fetchMenuItems(location) {
        const fetchRoute = '{{ route("items", ":location") }}'.replace(':location', location);

        $.ajax({
            url: fetchRoute,
            method: 'GET',
            success: function (response) {
                renderNavbarMenuItems(response, $('#menu-navbar'));
            },
            error: function (xhr, status, error) {
                console.error('Failed to fetch menu items:', error);
                console.error(xhr.responseText);
            }
        });
    }

    function renderNavbarMenuItems(items, parent) {
        parent.empty();
        items.forEach(item => {
            const pageRouteTemplate = '{{ route('front.page', ':slug') }}';
            const pageUrl = pageRouteTemplate.replace(':slug', item.slug);
            const listItem = $('<li>').addClass('nav-item');

            if (item.children && item.children.length > 0) {
                listItem.addClass('dropdown');
                listItem.html(`
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown-${item.id}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        ${item.title}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown-${item.id}"></ul>
                `);
                renderNavbarMenuItems(item.children, listItem.find('.dropdown-menu'));
            } else {
                listItem.html(`<a class="nav-link" href="${pageUrl}">${item.title}</a>`);
            }

            parent.append(listItem);
        });
    }
});
</script>
@endsection



javascript

$(document).ready(function () {
    // Select all functionality for pages, categories, and posts
    $('#select-all-pages').click(function () {
        $('#pages-list :checkbox').prop('checked', this.checked);
    });
    $('#select-all-categories').click(function () {
        $('#categories-list :checkbox').prop('checked', this.checked);
    });
    $('#select-all-posts').click(function () {
        $('#posts-list :checkbox').prop('checked', this.checked);
    });

    // Adding pages, categories, and posts to the menu
    $('#add-pages-to-menu').click(function () {
        addItemsToMenu('pages-list', 'page');
    });
    $('#add-categories-to-menu').click(function () {
        addItemsToMenu('categories-list', 'category');
    });
    $('#add-posts-to-menu').click(function () {
        addItemsToMenu('posts-list', 'post');
    });

    function addItemsToMenu(listId, itemType) {
        $('#' + listId + ' :checkbox:checked').each(function () {
            var itemId = $(this).val();
            var itemTitle = $(this).next('label').text().trim();

            $('#nestable > .dd-list').append(`
                <li class="dd-item" data-id="${itemType}-${itemId}" data-title="${itemTitle}" data-type="${itemType}">
                    <div class="dd-handle">${itemTitle}</div>
                    <button class="btn btn-danger btn-sm remove-item"><i class="fa-regular fa-circle-xmark"></i></button>
                </li>
            `);
        });

        $('#' + listId + ' :checkbox').prop('checked', false);
        $('#nestable').nestable('reload');
        updateMenuOutput($('#nestable'));
    }

    // Other existing code...

    var serializeNestable = function (items) {
        var serialized = [];
        $(items).each(function () {
            var item = this;
            var children = [];
            if (item.children) {
                children = serializeNestable(item.children);
            }
            serialized.push({
                id: item.id,
                title: item.title,
                type: item.type,
                children: children
            });
        });
        return serialized;
    };

    var updateMenuOutput = function (e) {
        var list = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // Other existing code...
});


//menu controller to save or update menu items 

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Log;

class MenuController extends Controller
{
    public function saveMenu(Request $request)
    {
        $menuId = $request->input('menu_id');
        $items = $request->input('items');

        // Remove existing menu items before saving new structure
        $deleted = MenuItem::where('menu_id', $menuId)->delete();

        // Debugging: Log the result of the deletion operation
        Log::info("Menu items deleted for menu_id: $menuId, count: $deleted");

        foreach ($items as $index => $item) {
            $this->saveMenuItem($item, null, $menuId, $index + 1);
        }

        return response()->json(['message' => 'Menu items saved successfully']);
    }

    private function saveMenuItem($item, $parentId = null, $menuId, $order)
    {
        $itemId = $item['item_id'];
        $itemType = $item['item_type'];

        $itemExists = $this->checkItemExists($itemId, $itemType);

        if (!$itemExists) {
            Log::error("Item with ID {$itemId} of type {$itemType} does not exist.");
            throw new \Exception("Item with ID {$itemId} of type {$itemType} does not exist.");
        }

        $menuItem = new MenuItem();
        $menuItem->menu_id = $menuId;
        $menuItem->item_id = $itemId;
        $menuItem->item_type = $itemType;
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

    private function checkItemExists($itemId, $itemType)
    {
        switch ($itemType) {
            case 'page':
                return Page::where('id', $itemId)->exists();
            case 'category':
                return Category::where('id', $itemId)->exists();
            case 'post':
                return Post::where('id', $itemId)->exists();
            default:
                return false;
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
            'id' => $item->item_id, // Use item_id instead of menu item id
            'title' => $item->title,
            'type' => $item->item_type,
            'children' => $item->children->map(function($child) {
                return $this->transformMenuItem($child);
            })->toArray()
        ];

        return $transformed;
    }
}





$('#add-to-menu').click(function () {
    $('#pages-list :checkbox:checked').each(function () {
        var itemId = $(this).val();
        var itemType = $(this).data('type'); // Assuming you have data-type attribute
        var itemTitle = $(this).next('label').text().trim();

        $('#nestable > .dd-list').append(`
            <li class="dd-item" data-id="${itemId}" data-type="${itemType}" data-title="${itemTitle}">
                <div class="dd-handle">${itemTitle}</div>
                <button class="btn btn-danger btn-sm remove-item"><i class="fa-regular fa-circle-xmark"></i></button>
            </li>
        `);
    });

    $('#select-all-pages').prop('checked', false);
    $('#pages-list :checkbox').prop('checked', false);
    $('#nestable').nestable('reload');
    updateMenuOutput($('#nestable'));
});

var serializeNestable = function (items) {
    var serialized = [];
    $(items).each(function () {
        var item = this;
        var children = [];
        if (item.children) {
            children = serializeNestable(item.children);
        }
        serialized.push({
            item_id: item.id,
            item_type: item.type,
            title: item.title,
            children: children
        });
    });
    return serialized;
};
