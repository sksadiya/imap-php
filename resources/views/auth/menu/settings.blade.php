@extends('layouts.extend')
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
                <label for="title" class="form-label">Menu</label>
                <select class="form-control" name="menu_id" id="menu_id" placeholder="Select Menu">
                  @if ($menus)
                    @foreach ($menus as $menu)
                      <option value="{{ $menu->id }}">{{ $menu->title }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <button class="btn btn-primary" id="save-menu-btn">Save Menu</button> <!-- Add Save button -->
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
                  @if ($pages)
                    @foreach ($pages as $page)
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{ $page->id }}" name="select-pages[]"
                          id="page_id_{{ $page->id }}">
                        <label class="form-check-label" for="page_id_{{ $page->id }}">
                          {{ $page->title }}
                        </label>
                      </div>
                    @endforeach
                  @endif
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="select-all-pages">
                    <label class="form-check-label" for="select-all-pages">
                      Select All
                    </label>
                  </div>
                  <button class="btn btn-primary btn-sm" type="button" id="add-to-menu">Add to Menu</button>
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
@endsection

@section('script')
<script src="{{ asset('assets/js/jquery.nestable.js') }}"></script>
<script>
  $(document).ready(function () {
    $('#select-all-pages').click(function (event) {
      if (this.checked) {
        $('#pages-list :checkbox').prop('checked', true);
      } else {
        $('#pages-list :checkbox').prop('checked', false);
      }
    });

    // Handle adding pages to the Nestable menu
    $('#add-to-menu').click(function () {
      $('#pages-list :checkbox:checked').each(function () {
        var pageId = $(this).val();
        var pageTitle = $(this).next('label').text();

        // Append the new item to the Nestable structure
        $('#nestable > .dd-list').append(`
          <li class="dd-item" data-id="${pageId}" data-title="${pageTitle}">
            <div class="dd-handle">${pageTitle}</div>
          </li>
        `);
      });

      // Uncheck all checkboxes after adding items
      $('#select-all-pages').prop('checked', false);
      $('#pages-list :checkbox').prop('checked', false);

      // After adding items, update Nestable
      $('#nestable').nestable('reload');
      updateMenuOutput($('#nestable'));
    });

    // Handle Save Menu button click
    $('#save-menu-btn').click(function () {
      var menuId = $('#menu_id').val();
      var items = serializeNestable($('#nestable').nestable('serialize'));

      // Log menuId and items before sending AJAX request
      console.log('Menu ID:', menuId);
      console.log('Items:', items);

      // Send AJAX request to save menu items
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
          // Optionally, you can redirect or perform additional actions here
        },
        error: function (xhr, status, error) {
          alert('Failed to save menu items: ' + error);
          console.error(xhr.responseText); // Log the detailed error message
        }
      });
    });

    // Initialize Nestable for menu structure
    $('#nestable').nestable({
      group: 1,
      maxDepth: 3, // Adjust maxDepth as per your needs
      callback: function (l, e) {
        updateMenuOutput(l);
      }
    });

    // Function to serialize Nestable output
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
          children: children // Include only children without parent_id
        });
      });
      return serialized;
    };

    // Function to update menu output
    var updateMenuOutput = function (e) {
      var list = e.length ? e : $(e.target),
        output = list.data('output');
      if (window.JSON) {
        output.val(window.JSON.stringify(list.nestable('serialize')));
      } else {
        output.val('JSON browser support required for this demo.');
      }
    };

    // Optionally, handle collapse and expand actions
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
@endsection





