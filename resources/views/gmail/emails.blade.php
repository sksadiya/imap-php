@extends('layouts.extend')
@section('title') Emails @endsection
@section('content')

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
                                                <li class="list-inline-item me-3 text-secondary" data-bs-toggle="popover" data-bs-content="Report Spam"><i class="fa-solid fa-circle-exclamation"></i></li>
                                                <li class="list-inline-item me-3 text-secondary" data-bs-toggle="popover" data-bs-content="Move to Bin"><i class="fa-solid fa-trash"></i></li>
                                                <li class="list-inline-item me-3 text-secondary" data-bs-toggle="popover" data-bs-content="Mark as read"><i class="fa-solid fa-envelope-circle-check"></i></li>
                                                <li class="list-inline-item me-3 text-secondary" data-bs-toggle="popover" data-bs-content="Move to"><i class="fa-solid fa-folder-closed"></i></li>
                                                <li class="list-inline-item me-3 text-secondary" data-bs-toggle="popover" data-bs-content="Archive"><i class="fa-solid fa-box-archive"></i></li>
                                            </ul>
                                            <div class="dropdown">
                                                <a class="btn bg-transparent  text-dark dropdown-toggle" href="#" role="button"
                                                    id="mailInfo" data-bs-toggle="dropdown" aria-expanded="false">
                                                </a>

                                                <ul class="dropdown-menu" aria-labelledby="mailInfo">
                                                    <li class="dropdown-item">from :{{$message['from'] }}</li>
                                                    <li class="dropdown-item">to :{{ $message['to'] }}</li>
                                                    <li class="dropdown-item">date :{{ $message['date']->format('d-M-Y H:i:s')  }}</li>
                                                    <li class="dropdown-item">subject :{{ $message['subject'] }}</li>
                                                </ul>
                                            </div>
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
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('#folderTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $(function () {
        $('[data-bs-toggle="popover"]').popover({
            trigger: 'hover'
        });
    });
    });
   
</script>
@endsection