@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="card">
        <div class="card-header"><h4>Developer Profiles</h4></div>
        <div class="table-responsive">
            <table class="table card-table">
                <thead><tr><th>ID</th><th>Company</th><th>Slug</th><th>Status</th><th></th></tr></thead>
                <tbody>
                @foreach($profiles as $profile)
                    <tr>
                        <td>{{ $profile->id }}</td>
                        <td>{{ $profile->company_name }}</td>
                        <td>{{ $profile->slug }}</td>
                        <td>{{ $profile->status }}</td>
                        <td><a href="{{ route('developer.profiles.edit', $profile->id) }}" class="btn btn-sm btn-primary">Edit</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $profiles->links() }}</div>
    </div>
@endsection
