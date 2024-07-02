@extends('layouts.extend')

<!-- navbar.blade.php -->

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
                <div class="col-md-4 py-2">
                    <div class="card">
                        <div class="card-header bg-primary text-white">Pages</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h4>Pages</h4>
                                <div class="form-check" id="pages-list">
                                    @foreach ($pages as $page)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            value="{{ $page->id }}" name="select-pages[]"
                                            id="page_id_{{ $page->id }}">
                                        <label class="form-check-label"
                                            for="page_id_{{ $page->id }}">
                                            {{ $page->title }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input"
                                            id="select-all-pages">
                                        <label class="form-check-label"
                                            for="select-all-pages">
                                            Select All
                                        </label>
                                    </div>
                                    <button class="btn btn-primary btn-sm"
                                        type="button" id="add-to-menu">Add to Menu</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        $('#select-all-pages').click(function () {
            $('#pages-list :checkbox').prop('checked', this.checked);
        });

        $('#add-to-menu').click(function () {
            $('#pages-list :checkbox:checked').each(function () {
                var pageId = $(this).val();
                var pageTitle = $(this).next('label').text().trim();

                $('#nestable > .dd-list').append(`
                    <li class="dd-item" data-id="${pageId}" data-title="${pageTitle}">
                        <div class="dd-handle">${pageTitle}</div>
                        <button class="btn btn-danger btn-sm remove-item"><i class="fa-regular fa-circle-xmark"></i></button>
                    </li>
                `);
            });

            $('#select-all-pages').prop('checked', false);
            $('#pages-list :checkbox').prop('checked', false);
            $('#nestable').nestable('reload');
            updateMenuOutput($('#nestable'));
        });

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
                <li class="dd-item" data-id="${item.id}" data-title="${item.title}">
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

        // Attach click event handler for dynamically added remove buttons
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
                    page_id: item.id,
                    title: item.title,
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
    // Existing code for managing menu items...

    // Fetch and render navbar menu items
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


