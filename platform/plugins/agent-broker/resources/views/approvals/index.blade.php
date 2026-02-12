@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="card">
        <div class="card-header"><h4>Agent Profile Revisions</h4></div>
        <ul class="list-group list-group-flush">
            @foreach($revisions as $revision)
                <li class="list-group-item d-flex justify-content-between">
                    <span>#{{ $revision->id }} - {{ $revision->status }}</span>
                    <span>
                        <form method="POST" action="{{ route('agent-broker.approvals.approve', $revision->id) }}" class="d-inline">@csrf<button class="btn btn-success btn-sm">Approve</button></form>
                    </span>
                </li>
            @endforeach
        </ul>
        <div class="card-footer">{{ $revisions->links() }}</div>
    </div>
@endsection
