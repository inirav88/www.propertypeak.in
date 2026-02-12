@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">CRM Leads</h4>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="stage" class="form-select" onchange="this.form.submit()">
                        <option value="">All stages</option>
                        @foreach($stages as $stage)
                            <option value="{{ $stage }}" @selected(request('stage') === $stage)>{{ ucfirst(str_replace('_', ' ', $stage)) }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Stage</th>
                            <th>Assigned To</th>
                            <th>Follow Up</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                            <tr>
                                <td>{{ $lead->id }}</td>
                                <td>{{ $lead->name }}</td>
                                <td>{{ $lead->email }}</td>
                                <td>{{ $lead->phone }}</td>
                                <td>{{ $lead->pipeline_stage ?: 'new' }}</td>
                                <td>{{ $lead->assigned_to ?: '-' }}</td>
                                <td>{{ $lead->follow_up_at?->toDateTimeString() ?: '-' }}</td>
                                <td>{{ $lead->created_at?->toDateTimeString() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No leads found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $leads->links() }}
        </div>
    </div>
@endsection
