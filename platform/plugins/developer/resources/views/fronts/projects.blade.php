@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
<div class="mb-3"><a class="btn btn-primary" href="{{ route('public.account.developer.projects.create') }}">Create Project</a></div>
<div class="card">
    <div class="card-header"><h4>My Projects</h4></div>
    <table class="table card-table">
        <thead><tr><th>ID</th><th>Name</th><th>Status</th><th>Approval</th><th></th></tr></thead>
        <tbody>
            @foreach($projects as $project)
            <tr>
                <td>{{ $project->id }}</td><td>{{ $project->name }}</td><td>{{ $project->status }}</td><td>{{ $project->approval_status }}</td>
                <td><a href="{{ route('public.account.developer.projects.edit', $project->id) }}" class="btn btn-sm btn-primary">Edit</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">{{ $projects->links() }}</div>
</div>
@endsection
