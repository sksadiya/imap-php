@extends('layouts.extend')
@section('content')

<div class="container mt-5">
    @if (Session::has('success'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Hurrah!</strong> {{ Session::get('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
    @endif

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
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#composemodal">
Compose
</button>
        
    </nav>

    <div class="tab-content py-4" id="folderContent">
        @foreach($folders as $index => $folder)
            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                 id="{{ Illuminate\Support\Str::slug($folder['name']) }}-content" role="tabpanel"
                 aria-labelledby="{{ Illuminate\Support\Str::slug($folder['name']) }}-tab">
                <div class="card">
                    <div class="card-body">
                        <h4>{{ $folder['name'] }} Messages</h4>
                        <ul class="list-group">
                            @foreach($messages[$folder['name']] as $index => $message)
                                <li class="list-group-item">
                                    <a class="btn btn-primary bg-transparent border-0 text-dark shadow-none"
                                       data-bs-toggle="collapse"
                                       href="#email-{{ Illuminate\Support\Str::slug($folder['name']) }}-{{ $index }}"
                                       role="button" aria-expanded="false"
                                       aria-controls="email-{{ Illuminate\Support\Str::slug($folder['name']) }}-{{ $index }}">
                                        <span class="badge bg-secondary">{{  $message['folder'] }}</span>
                                        {{ $message['subject'] }} - {{ $message['from'] }}
                                        -{{ $message['date']->format('d-M-Y H:i:s') }}
                                    </a>
                                    <div class="collapse"
                                         id="email-{{ Illuminate\Support\Str::slug($folder['name']) }}-{{ $index }}">
                                        <div class="card card-body py-2">
                                            <ul class="list-inline">
                                                <li class="list-inline-item me-3 text-secondary email-action"
                                                    data-action="moveToSpam" data-message-id="{{ $message['id'] }}"
                                                   data-folder="{{$message['folder'] }}" data-bs-toggle="popover" data-bs-content="Report Spam">
                                                    <i class="fa-solid fa-circle-exclamation"></i>
                                                </li>
                                                <li class="list-inline-item me-3 text-secondary email-action"
                                                    data-action="delete" data-message-id="{{ $message['id'] }}"
                                                    data-folder="{{$message['folder'] }}" data-bs-toggle="popover" data-bs-content="Move to Bin">
                                                    <i class="fa-solid fa-trash"></i>
                                                </li>
                                                <li class="list-inline-item me-3 text-secondary email-action"
                                                    data-action="markAsRead" data-message-id="{{ $message['id'] }}"
                                                    data-folder="{{$message['folder'] }}"   data-bs-toggle="popover" data-bs-content="Mark as read">
                                                    <i class="fa-solid fa-envelope-circle-check"></i>
                                                </li>
                                                <li class="list-inline-item me-3 text-secondary email-action" data-action="move"
                                                    data-message-id="{{ $message['id'] }}" data-bs-toggle="popover"
                                                    data-folder="{{$message['folder'] }}"    data-bs-content="Move to">
                                                    <i class="fa-solid fa-folder-closed"></i>
                                                </li>
                                                <li class="list-inline-item me-3 text-secondary email-action"
                                                    data-action="archive" data-message-id="{{ $message['id'] }}"
                                                    data-folder="{{$message['folder'] }}"   data-bs-toggle="popover" data-bs-content="Archive">
                                                    <i class="fa-solid fa-box-archive"></i>
                                                </li>
                                                @if ($folder['name'] == 'Bin')
                                                <li class="list-inline-item me-3 text-secondary email-action text-white bg-secondary"
                                                    data-action="deleteForever" data-message-id="{{$message['id'] }}"
                                                    data-folder="{{$message['folder'] }}"  data-bs-toggle="popover" data-bs-content="delete forever">
                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                </li>
                                                @endif
                                            </ul>
                                            <div class="dropdown">
                                                <a class="btn bg-transparent text-dark dropdown-toggle" href="#" role="button"
                                                   id="mailInfo" data-bs-toggle="dropdown" aria-expanded="false">
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="mailInfo">
                                                    <li class="dropdown-item">from :{{$message['from'] }}</li>
                                                    <li class="dropdown-item">to :{{ $message['to'] }}</li>
                                                    <li class="dropdown-item">date :{{ $message['date']->format('d-M-Y H:i:s') }}</li>
                                                    <li class="dropdown-item">subject :{{ $message['subject'] }}</li>
                                                    <li class="dropdown-item">subject :{{ $message['id'] }}</li>
                                                </ul>
                                            </div>
                                            {!! $message['body'] !!}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="pagination-wrapper">
                            {{ $messages[$folder['name']]->appends(['page' => $page])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


<div class="modal fade" id="composemodal" tabindex="-1" role="dialog" aria-labelledby="composemodalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header p-3 bg-light">
                <h5 class="modal-title" id="composemodalTitle">New Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="composeForm" method="POST" action="{{ route('emails.send') }}">
                @csrf
                <div class="modal-body">
                    <div>
                        <div class="mb-3 position-relative">
                            <input type="text" class="form-control email-compose-input" name="to" data-choices data-choices-limit="15" data-choices-removeItem placeholder="To">
                            <div class="position-absolute top-0 end-0">
                                <div class="d-flex">
                                    <button class="btn btn-link text-reset fw-semibold px-2" type="button" data-bs-toggle="collapse" data-bs-target="#CcRecipientsCollapse" aria-expanded="false" aria-controls="CcRecipientsCollapse">
                                        Cc
                                    </button>
                                    <button class="btn btn-link text-reset fw-semibold px-2" type="button" data-bs-toggle="collapse" data-bs-target="#BccRecipientsCollapse" aria-expanded="false" aria-controls="BccRecipientsCollapse">
                                        Bcc
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="collapse" id="CcRecipientsCollapse">
                            <div class="mb-3">
                                <label>Cc:</label>
                                <input type="text" class="form-control" name="cc" data-choices data-choices-limit="15" data-choices-removeItem placeholder="Cc recipients">
                            </div>
                        </div>
                        <div class="collapse" id="BccRecipientsCollapse">
                            <div class="mb-3">
                                <label>Bcc:</label>
                                <input type="text" class="form-control" name="bcc" data-choices data-choices-limit="15" data-choices-removeItem placeholder="Bcc recipients">
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="subject" placeholder="Subject">
                        </div>
                        <div class="ck-editor-reverse">
                            <textarea class="form-control summernote" name="message" placeholder="Message"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-ghost-danger" data-bs-dismiss="modal">Discard</button>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">Send</button>
                        <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="ri-timer-line text-muted me-1 align-bottom"></i> Schedule Send</a></li>
                        </ul>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function () {
        new Choices('.email-compose-input[data-choices]');
            new Choices('#cc[data-choices]');
            new Choices('#bcc[data-choices]');
    $('#folderTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#composemodal').modal({
        backdrop: 'static',
        keyboard: false
    });
    $(function () {
        $('[data-bs-toggle="popover"]').popover({
            trigger: 'hover'
        });
    });

    $('.email-action').on('click', function () {
        var action = $(this).data('action');
        var messageId = $(this).data('message-id');
        var folder = $(this).data('folder');

        console.log('Action:', action);
        console.log('Message ID:', messageId);

        $.ajax({
            url: '{{ route('mailAction') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                action: action,
                message_id: messageId,
                folder : folder
            },
            success: function (response) {
                if (response.success) {
                    alert('Action performed successfully');
                    location.reload();  // or use AJAX to fetch the updated messages for the current tab
                } else {
                    alert('Failed to perform action');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error details:', xhr, status, error);
                alert('An error occurred: ' + xhr.responseText);
                console.log(xhr.responseText);
            }
        });
    });
});

</script>
@endsection
