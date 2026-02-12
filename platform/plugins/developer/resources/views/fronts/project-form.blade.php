@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
<form method="POST" action="{{ $project->exists ? route('public.account.developer.projects.update', $project->id) : route('public.account.developer.projects.store') }}">
    @csrf
    @if($project->exists) @method('PUT') @endif
    <div class="card">
        <div class="card-header"><h4>{{ $project->exists ? 'Edit Project' : 'Create Project' }}</h4></div>
        <div class="card-body row">
            <div class="col-md-6 mb-3"><label>Name</label><input name="name" class="form-control" value="{{ old('name', $project->name) }}"></div>
            <div class="col-md-6 mb-3"><label>Slug</label><input name="slug" class="form-control" value="{{ old('slug', $project->slug) }}"></div>
            <div class="col-md-4 mb-3"><label>Project Status</label><input name="project_status" class="form-control" value="{{ old('project_status', $project->project_status ?: 'upcoming') }}"></div>
            <div class="col-md-4 mb-3"><label>Status</label><input name="status" class="form-control" value="{{ old('status', $project->status ?: 'draft') }}"></div>
            <div class="col-md-4 mb-3"><label>Location</label><input name="location" class="form-control" value="{{ old('location', $project->location) }}"></div>
        </div>
        <div class="card-footer"><button class="btn btn-primary">Submit</button></div>
    </div>
</form>
@endsection
