@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="card">
        <div class="card-header"><h4>Agent Profiles</h4></div>
        <div class="table-responsive">
            <table class="table card-table">
                <thead><tr><th>ID</th><th>Slug</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($agents as $agent)
                    <tr>
                        <td>{{ $agent->id }}</td>
                        <td>{{ $agent->slug }}</td>
                        <td>{{ $agent->status }}</td>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('agent-broker.profiles.edit', $agent->id) }}">Edit</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $agents->links() }}</div>
    </div>
@endsection
