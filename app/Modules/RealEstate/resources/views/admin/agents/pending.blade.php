@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('title', 'Pending Agents')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Pending Agents</h1>
        <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to All
        </a>
    </div>

    <!-- Bulk Actions -->
    <form action="{{ route('admin.agents.bulk-approve') }}" method="POST" id="bulkForm">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">Select All</label>
                    </div>
                    <button type="submit" class="btn btn-success" onclick="return confirm('Approve selected agents?')">
                        <i class="fas fa-check me-2"></i>Bulk Approve
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="40">
                                    <span class="visually-hidden">Select</span>
                                </th>
                                <th>Agent</th>
                                <th>Contact</th>
                                <th>Experience</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agents as $agent)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $agent->id }}" class="form-check-input agent-checkbox">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $agent->avatar_url }}" alt="" class="rounded-circle" width="50" height="50">
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $agent->full_name }}</h6>
                                                <small class="text-muted">{{ $agent->designation ?? 'Agent' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $agent->email }}</div>
                                        <small class="text-muted">{{ $agent->phone }}</small>
                                    </td>
                                    <td>
                                        {{ $agent->experience_years }} years
                                    </td>
                                    <td>{{ $agent->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <form action="{{ route('admin.agents.approve', $agent->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $agent->id }}">
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <p class="mb-0">No pending agents. All caught up!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>

    {{ $agents->links() }}
</div>

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.agent-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endpush
@endsection
