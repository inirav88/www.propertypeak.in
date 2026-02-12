@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h4>Profile Revisions</h4></div>
                <ul class="list-group list-group-flush">
                    @foreach($profileRevisions as $revision)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>#{{ $revision->id }} - {{ $revision->status }}</span>
                            <span>
                                <form method="POST" action="{{ route('developer.approvals.profiles.approve', $revision->id) }}" class="d-inline">@csrf<button class="btn btn-success btn-sm">Approve</button></form>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h4>Project Revisions</h4></div>
                <ul class="list-group list-group-flush">
                    @foreach($projectRevisions as $revision)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>#{{ $revision->id }} - {{ $revision->status }}</span>
                            <span>
                                <form method="POST" action="{{ route('developer.approvals.projects.approve', $revision->id) }}" class="d-inline">@csrf<button class="btn btn-success btn-sm">Approve</button></form>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
