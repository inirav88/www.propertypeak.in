@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <form method="POST" action="{{ $profile->exists ? route('developer.profiles.update', $profile->id) : route('developer.profiles.store') }}">
        @csrf
        @if($profile->exists)
            @method('PUT')
        @endif
        <div class="card">
            <div class="card-header"><h4>{{ $profile->exists ? 'Edit Developer' : 'Create Developer' }}</h4></div>
            <div class="card-body row">
                <div class="col-md-6 mb-3"><label>Account ID</label><input name="account_id" class="form-control" value="{{ old('account_id', $profile->account_id) }}"></div>
                <div class="col-md-6 mb-3"><label>Slug</label><input name="slug" class="form-control" value="{{ old('slug', $profile->slug) }}"></div>
                <div class="col-md-6 mb-3"><label>Company Name</label><input name="company_name" class="form-control" value="{{ old('company_name', $profile->company_name) }}"></div>
                <div class="col-md-6 mb-3"><label>Status</label><input name="status" class="form-control" value="{{ old('status', $profile->status ?: 'draft') }}"></div>
            </div>
            <div class="card-footer"><button class="btn btn-primary">Save</button></div>
        </div>
    </form>
@endsection
