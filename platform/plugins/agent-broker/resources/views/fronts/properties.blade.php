@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
<div class="card">
    <div class="card-header"><h4>My Property Linkage</h4></div>
    <table class="table card-table">
        <thead><tr><th>ID</th><th>Name</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($properties as $property)
                <tr><td>{{ $property->id }}</td><td>{{ $property->name }}</td><td>{{ $property->status }}</td></tr>
            @endforeach
        </tbody>
    </table>
    <div class="card-footer">{{ $properties->links() }}</div>
</div>
@endsection
