<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Mailbox</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                <h5 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                    Folders
                </h5>
                <ul class="nav flex-column mb-2" id="folderTab" role="tablist">
                    @foreach ($folders as $folder)
                        @if ($folder->children)
                            @foreach ($folder->children as $child)
                                <li class="nav-item">
                                    <a class="nav-link" id="{{ Illuminate\Support\Str::slug($child->full_name) }}-tab" data-toggle="tab" href="#{{ Illuminate\Support\Str::slug($child->full_name) }}" role="tab" aria-controls="{{ Illuminate\Support\Str::slug($child->full_name) }}" aria-selected="true">
                                        {{ $child->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                </ul>
            </div>
        </nav>

        <!-- Main content area -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1>Your Mailbox</h1>
            </div>

            <!-- Tab content for emails -->
            <div class="tab-content" id="folderTabContent">
                @foreach ($folders as $folder)
                    @if ($folder->children)
                        @foreach ($folder->children as $child)
                            <div class="tab-pane fade" id="{{ Illuminate\Support\Str::slug($child->full_name) }}" role="tabpanel" aria-labelledby="{{ Illuminate\Support\Str::slug($child->full_name) }}-tab">
                                @if (!empty($mailbox[$child->full_name]))
                                    <ul class="list-group">
                                        @foreach ($mailbox[$child->full_name] as $email)
                                            <li class="list-group-item">
                                                <a href="#email-{{ Illuminate\Support\Str::slug($child->full_name) }}-{{ $loop->index }}" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="email-{{ Illuminate\Support\Str::slug($child->full_name) }}-{{ $loop->index }}">
                                                    {{ $email['subject'] }} - From: {{ $email['from'] }} - Date: {{ $email['date']->format('d-M-Y H:i:s') }}
                                                </a>
                                                <div class="collapse mt-2" id="email-{{ Illuminate\Support\Str::slug($child->full_name) }}-{{ $loop->index }}">
                                                    <div class="card card-body">
                                                        {!! $email['body'] !!}
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>No messages found in this folder.</p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JavaScript and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Activate first tab and show corresponding content
    $(document).ready(function() {
        $('#folderTab a:first').tab('show');
    });

    // Handle tab show event to dynamically update active state
    $('#folderTab a').on('shown.bs.tab', function (e) {
        var targetTab = $(e.target).attr('href'); // activated tab
        $(targetTab).find('.collapse').collapse('hide'); // hide all collapses in the activated tab
        $(targetTab).find('.collapse:first').collapse('show'); // show the first collapse in the activated tab
    });
</script>

</body>
</html>
