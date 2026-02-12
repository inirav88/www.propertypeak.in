@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <form method="POST" action="{{ $agent->exists ? route('agent-broker.profiles.update', $agent->id) : route('agent-broker.profiles.store') }}">
        @csrf
        @if($agent->exists)
            @method('PUT')
        @endif
        <div class="card">
            <div class="card-header"><h4>{{ $agent->exists ? 'Edit Agent' : 'Create Agent' }}</h4></div>
            <div class="card-body row">
                <div class="col-md-6 mb-3"><label>Account ID</label><input name="account_id" class="form-control" value="{{ old('account_id', $agent->account_id) }}"></div>
                <div class="col-md-6 mb-3"><label>Slug</label><input name="slug" class="form-control" value="{{ old('slug', $agent->slug) }}"></div>
                <div class="col-md-6 mb-3"><label>Status</label><input name="status" class="form-control" value="{{ old('status', $agent->status ?: 'draft') }}"></div>
            </div>
            <div class="card-footer"><button class="btn btn-primary">Save</button></div>
        </div>
    </form>
@endsection
