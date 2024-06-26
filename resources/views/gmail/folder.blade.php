<!-- resources/views/gmail/folder.blade.php -->

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
                <ul class="nav flex-column mb-2">
                    @foreach ($folders as $folder)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('fetchFolder', ['folderName' => $folder->full_name]) }}">
                                {{ $folder->name }}
                            </a>
                            @if ($folder->children)
                                <ul class="nav flex-column ml-3">
                                    @foreach ($folder->children as $child)
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('fetchFolder', ['folderName' => $child->full_name]) }}">
                                                {{ $child->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>

        <!-- Main content area -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="pt-3 pb-2 mb-3 border-bottom">
                <h1>Your Mailbox - {{ $mailbox['folder']['folder_name'] }}</h1>
            </div>

            <!-- Tab content for emails -->
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="emails" role="tabpanel" aria-labelledby="emails-tab">
                    @if (!empty($mailbox['folder']['emails']))
                        @foreach ($mailbox['folder']['emails'] as $email)
                            <div class="card mb-3">
                                <div class="card-header">
                                    {{ $email['subject'] }}
                                </div>
                                <div class="card-body">
                                    <p>From: {{ $email['from'] }}</p>
                                    <p>Date: {{ $email['date']->format('d-M-Y H:i:s') }}</p>
                                    <hr>
                                    {!! $email['body'] !!}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No messages found in this folder.</p>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JavaScript and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
