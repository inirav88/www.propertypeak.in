@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="card">
        <div class="card-header"><h4>Developer Leads</h4></div>
        <div class="table-responsive">
            <table class="table card-table">
                <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Profile</th><th>Status</th></tr></thead>
                <tbody>
                @foreach($leads as $lead)
                    <tr>
                        <td>{{ $lead->id }}</td>
                        <td>{{ $lead->name }}</td>
                        <td>{{ $lead->email }}</td>
                        <td>{{ $lead->developerProfile?->company_name }}</td>
                        <td>{{ $lead->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $leads->links() }}</div>
    </div>
@endsection
