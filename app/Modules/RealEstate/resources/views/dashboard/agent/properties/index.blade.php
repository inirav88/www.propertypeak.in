@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'My Properties')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">My Properties</h1>
        <a href="{{ route('agent-portal.properties.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Property
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            @if($properties->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Property</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Approval</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($properties as $property)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $property->main_image_url }}" alt="" class="rounded" width="60" height="60" style="object-fit: cover;">
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ Str::limit($property->title, 40) }}</h6>
                                                <small class="text-muted">{{ $property->location }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $property->type === 'sale' ? 'success' : ($property->type === 'rent' ? 'info' : 'secondary') }}">
                                            {{ ucfirst($property->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $property->formatted_price }}</td>
                                    <td>
                                        <span class="badge bg-{{ $property->status === 'available' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $property->approval_status === 'approved' ? 'success' : ($property->approval_status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($property->approval_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('agent-portal.properties.edit', $property->slug) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $properties->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-home fa-3x text-muted mb-3"></i>
                    <h4>No properties yet</h4>
                    <p class="text-muted">Start adding properties to your portfolio.</p>
                    <a href="{{ route('agent-portal.properties.create') }}" class="btn btn-primary">Add Property</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
