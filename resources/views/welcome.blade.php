<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nestable Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <style>
        .nested-menu {
            list-style: none;
            padding: 0;
        }
        .nested-menu .nested-item {
            margin: 5px 0;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
            cursor: move;
        }
        .nested-menu .nested-item > .nested-menu {
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Nested Menu</h2>
        <ul class="nested-menu" id="nestedMenu">
            <li class="nested-item" data-id="1">
                Item 1
                <ul class="nested-menu">
                    <li class="nested-item" data-id="2">Subitem 1</li>
                    <li class="nested-item" data-id="3">Subitem 2</li>
                </ul>
            </li>
            <li class="nested-item" data-id="4">
                Item 2
                <ul class="nested-menu"></ul>
            </li>
            <li class="nested-item" data-id="5">
                Item 3
                <ul class="nested-menu"></ul>
            </li>
        </ul>
        <button id="saveMenu" class="btn btn-primary mt-3">Save Menu</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nestedMenu = document.getElementById('nestedMenu');

            const sortableOptions = {
                group: {
                    name: 'nested',
                    pull: true,
                    put: true
                },
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                handle: '.nested-item',
                ghostClass: 'sortable-ghost',
                onEnd: function (evt) {
                    // Logic to handle saving the new order, if needed
                    console.log('Drag ended');
                }
            };

            // Initialize Sortable for the main list
            const sortable = new Sortable(nestedMenu, sortableOptions);

            // Function to recursively initialize Sortable for nested lists
            function initializeNestedSortables(container) {
                container.querySelectorAll('.nested-item').forEach(function (item) {
                    const nestedList = item.querySelector(':scope > .nested-menu');
                    if (nestedList) {
                        const sortableNested = new Sortable(nestedList, sortableOptions);
                        initializeNestedSortables(nestedList);
                    }
                });
            }

            // Initialize Sortable for all nested lists
            initializeNestedSortables(nestedMenu);

            document.getElementById('saveMenu').addEventListener('click', function () {
                const menuItems = getNestedMenuItems(nestedMenu);
                console.log(menuItems);

                // Here, you can send menuItems to your server using AJAX, fetch, or any other method
            });

            function getNestedMenuItems(container) {
                const items = [];

                container.querySelectorAll(':scope > .nested-item').forEach(item => {
                    const id = item.getAttribute('data-id');
                    const childrenContainer = item.querySelector(':scope > .nested-menu');
                    const children = childrenContainer ? getNestedMenuItems(childrenContainer) : [];
                    items.push({ id, children });
                });

                return items;
            }
        });
    </script>
</body>
</html>
