@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('title', 'Pending Developers')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Pending Developers</h1>
        <a href="{{ route('admin.developers.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to All
        </a>
    </div>

    <!-- Bulk Actions -->
    <form action="{{ route('admin.developers.bulk-approve') }}" method="POST" id="bulkForm">
        @csrf
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">Select All</label>
                    </div>
                    <button type="submit" class="btn btn-success" onclick="return confirm('Approve selected developers?')">
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
                                <th>Developer</th>
                                <th>Contact</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($developers as $developer)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="ids[]" value="{{ $developer->id }}" class="form-check-input developer-checkbox">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $developer->logo_url }}" alt="" class="rounded" width="50" height="50">
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $developer->company_name }}</h6>
                                                <small class="text-muted">{{ Str::limit($developer->description, 60) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $developer->email }}</div>
                                        <small class="text-muted">{{ $developer->phone }}</small>
                                    </td>
                                    <td>{{ $developer->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.developers.show', $developer->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            <form action="{{ route('admin.developers.approve', $developer->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $developer->id }}">
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                        <p class="mb-0">No pending developers. All caught up!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>

    {{ $developers->links() }}
</div>

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.developer-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endpush
@endsection
