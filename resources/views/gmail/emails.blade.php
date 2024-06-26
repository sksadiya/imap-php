<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emails</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- jQuery -->
</head>

<body>
    <div class="container mt-5">
        <nav class="nav nav-pills nav-fill" id="folderTabs">
            @foreach($folders as $index => $folder)

                <a class="nav-link {{ $index === 0 ? 'active' : '' }}"
                    id="{{ Illuminate\Support\Str::slug($folder['name']) }}-tab" data-bs-toggle="tab"
                    href="#{{ Illuminate\Support\Str::slug($folder['name']) }}-content" role="tab"
                    aria-controls="{{ Illuminate\Support\Str::slug($folder['name']) }}-content"
                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                    {{ $folder['name'] }}
                </a>
            @endforeach
        </nav>

        <div class="tab-content py-4" id="folderContent">
            @foreach($folders as $index => $folder)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                    id="{{ Illuminate\Support\Str::slug($folder['name']) }}-content" role="tabpanel"
                    aria-labelledby="{{ Illuminate\Support\Str::slug($folder['name']) }}-tab">
                    <div class="card">
                        <div class="card-body">
                            <!-- Place your messages content here -->
                            <h4>{{ $folder['name'] }} Messages</h4>
                            <ul class="list-group">
                                @foreach($messages[$folder['name']] as $index => $message)
                                    <li class="list-group-item">
                                        <a class="btn btn-primary bg-transparent border-0 text-dark shadow-none" data-bs-toggle="collapse"
                                            href="#email-{{ Illuminate\Support\Str::slug($folder['name']) }}-{{ $index }}"
                                            role="button" aria-expanded="false"
                                            aria-controls="email-{{ Illuminate\Support\Str::slug($folder['name']) }}-{{ $index }}">
                                           
                                           <span class="badge bg-secondary">{{  $message['folder'] }}</span>

                                            {{ $message['subject'] }} - {{ $message['from'] }}
                                            -{{ $message['date']->format('d-M-Y H:i:s') }}
                                        </a>
                                        <div class="collapse" id="email-{{ Illuminate\Support\Str::slug($folder['name']) }}-{{ $index }}">
                                            <div class="card card-body py-2">
                                            {!! $message['body'] !!}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
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
