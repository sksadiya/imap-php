<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emails</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Bootstrap Bundle (JS and Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <nav class="nav nav-pills nav-fill" id="folderTabs">
            @foreach($folders as $key => $folder)

                <a class="nav-link {{ $key === 0 ? 'active' : '' }}"
                    id="{{ Illuminate\Support\Str::slug($folder['name']) }}-tab" data-toggle="tab"
                    href="#{{ Illuminate\Support\Str::slug($folder['name']) }}-content" role="tab"
                    aria-controls="{{ Illuminate\Support\Str::slug($folder['name']) }}-content"
                    aria-selected="{{ $key === 0 ? 'true' : 'false' }}">
                    {{ $folder['name'] }}
                </a>
            @endforeach
        </nav>

        <div class="tab-content py-4" id="folderContent">
            @foreach($folders as $key => $folder)
                <div class="tab-pane fade {{ $key === 0 ? 'show active' : '' }}"
                    id="{{ Illuminate\Support\Str::slug($folder['name']) }}-content" role="tabpanel"
                    aria-labelledby="{{ Illuminate\Support\Str::slug($folder['name']) }}-tab">
                    <div class="card">
                        <div class="card-body">
                            <!-- Place your messages content here -->
                            <h4>{{ $folder['name'] }} Messages</h4>
                            <ul class="list-group">
                                @foreach($messages[$folder['name']] as $message)
                                    <li class="list-group-item">{{ $message['subject'] }} - {{ $message['from'] }} -
                                        {{ $message['date']->format('d-M-Y H:i:s') }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

    <!-- JavaScript to handle tab switching -->
    <script>
        $(document).ready(function () {
            $('#folderTabs a').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
        });
    </script>
</body>

</html>