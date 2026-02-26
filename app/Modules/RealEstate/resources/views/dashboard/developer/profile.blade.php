@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'Edit Profile - Developer Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Profile</h1>
        <a href="{{ route('developer-portal.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Company Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('developer-portal.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company Name *</label>
                        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" 
                            value="{{ old('company_name', $developer->company_name ?? '') }}" required>
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tagline</label>
                        <input type="text" name="tagline" class="form-control @error('tagline') is-invalid @enderror" 
                            value="{{ old('tagline', $developer->tagline ?? '') }}" placeholder="e.g., Building Dreams Since 1990">
                        @error('tagline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                            value="{{ old('email', $developer->email ?? '') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                            value="{{ old('phone', $developer->phone ?? '') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" 
                            value="{{ old('website', $developer->website ?? '') }}" placeholder="https://">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Established Year</label>
                        <input type="number" name="established_year" class="form-control @error('established_year') is-invalid @enderror" 
                            value="{{ old('established_year', $developer->established_year ?? '') }}" min="1800" max="{{ date('Y') }}">
                        @error('established_year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">RERA ID</label>
                        <input type="text" name="rera_id" class="form-control @error('rera_id') is-invalid @enderror" 
                            value="{{ old('rera_id', $developer->rera_id ?? '') }}">
                        @error('rera_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Registration Number</label>
                        <input type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror" 
                            value="{{ old('registration_number', $developer->registration_number ?? '') }}">
                        @error('registration_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Office Address</label>
                    <textarea name="office_address" class="form-control @error('office_address') is-invalid @enderror" rows="2">{{ old('office_address', $developer->office_address ?? '') }}</textarea>
                    @error('office_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">About Company</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $developer->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company Logo</label>
                        @if($developer && $developer->logo)
                            <div class="mb-2">
                                <img src="{{ $developer->logo_url }}" alt="Current Logo" class="img-thumbnail" width="100">
                            </div>
                        @endif
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Max 2MB. Recommended: 400x400px</small>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Banner Image</label>
                        @if($developer && $developer->banner_image)
                            <div class="mb-2">
                                <img src="{{ $developer->banner_url }}" alt="Current Banner" class="img-thumbnail" width="200">
                            </div>
                        @endif
                        <input type="file" name="banner_image" class="form-control @error('banner_image') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Max 5MB. Recommended: 1920x400px</small>
                        @error('banner_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">SEO Settings</h5>

                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror" 
                        value="{{ old('meta_title', $developer->meta_title ?? '') }}">
                    @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="2">{{ old('meta_description', $developer->meta_description ?? '') }}</textarea>
                    @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('developer-portal.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
