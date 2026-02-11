@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
<form method="POST" action="{{ route('public.account.agent.profile.update') }}">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-header"><h4>Agent Profile</h4></div>
        <div class="card-body row">
            <div class="col-md-6 mb-3"><label>Slug</label><input name="slug" class="form-control" value="{{ old('slug', $profile->slug) }}"></div>
            <div class="col-md-6 mb-3"><label>Designation</label><input name="designation" class="form-control" value="{{ old('designation', $profile->designation) }}"></div>
            <div class="col-md-6 mb-3"><label>Contact Email</label><input name="contact_email" class="form-control" value="{{ old('contact_email', $profile->contact_email) }}"></div>
            <div class="col-md-6 mb-3"><label>Contact Phone</label><input name="contact_phone" class="form-control" value="{{ old('contact_phone', $profile->contact_phone) }}"></div>
            <div class="col-md-12 mb-3"><label>Bio</label><textarea name="bio" class="form-control">{{ old('bio', $profile->bio) }}</textarea></div>
        </div>
        <div class="card-footer"><button class="btn btn-primary">Submit</button></div>
    </div>
</form>
@endsection
