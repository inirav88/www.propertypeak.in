@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
<form method="POST" action="{{ route('public.account.developer.profile.update') }}">
    @csrf
    @method('PUT')
    <div class="card">
        <div class="card-header"><h4>Developer Profile</h4></div>
        <div class="card-body row">
            <div class="col-md-6 mb-3"><label>Slug</label><input name="slug" class="form-control" value="{{ old('slug', $profile->slug) }}"></div>
            <div class="col-md-6 mb-3"><label>Company Name</label><input name="company_name" class="form-control" value="{{ old('company_name', $profile->company_name) }}"></div>
            <div class="col-md-6 mb-3"><label>Contact Email</label><input name="contact_email" class="form-control" value="{{ old('contact_email', $profile->contact_email) }}"></div>
            <div class="col-md-6 mb-3"><label>Contact Phone</label><input name="contact_phone" class="form-control" value="{{ old('contact_phone', $profile->contact_phone) }}"></div>
            <div class="col-md-12 mb-3"><label>About</label><textarea name="about" class="form-control">{{ old('about', $profile->about) }}</textarea></div>
        </div>
        <div class="card-footer"><button class="btn btn-primary">Submit</button></div>
    </div>
</form>
@endsection
