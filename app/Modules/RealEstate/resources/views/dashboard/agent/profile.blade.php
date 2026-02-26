@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'Edit Profile - Agent Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Profile</h1>
        <a href="{{ route('agent-portal.dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <div class="row">
        <!-- Profile Picture Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="{{ $agent->avatar_url }}" alt="{{ $agent->full_name }}" class="rounded-circle mb-3" width="150" height="150">
                    <h5>{{ $agent->full_name }}</h5>
                    <p class="text-muted">{{ $agent->designation ?? 'Real Estate Agent' }}</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('agents.show', $agent->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>View Public Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('agent-portal.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                                    value="{{ old('first_name', $agent->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                                    value="{{ old('last_name', $agent->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Designation</label>
                                <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror" 
                                    value="{{ old('designation', $agent->designation) }}" placeholder="e.g., Senior Property Consultant">
                                @error('designation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" name="experience_years" class="form-control @error('experience_years') is-invalid @enderror" 
                                    value="{{ old('experience_years', $agent->experience_years) }}" min="0">
                                @error('experience_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $agent->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                    value="{{ old('phone', $agent->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">WhatsApp</label>
                            <input type="tel" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" 
                                value="{{ old('whatsapp', $agent->whatsapp) }}">
                            @error('whatsapp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" 
                                value="{{ old('website', $agent->website) }}" placeholder="https://">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Office Address</label>
                            <textarea name="office_address" class="form-control @error('office_address') is-invalid @enderror" rows="2">{{ old('office_address', $agent->office_address) }}</textarea>
                            @error('office_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4">{{ old('bio', $agent->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">License Number</label>
                                <input type="text" name="license_number" class="form-control @error('license_number') is-invalid @enderror" 
                                    value="{{ old('license_number', $agent->license_number) }}">
                                @error('license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">RERA ID</label>
                                <input type="text" name="rera_id" class="form-control @error('rera_id') is-invalid @enderror" 
                                    value="{{ old('rera_id', $agent->rera_id) }}">
                                @error('rera_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profile Photo</label>
                                @if($agent->avatar)
                                    <div class="mb-2">
                                        <img src="{{ $agent->avatar_url }}" alt="Current Avatar" class="img-thumbnail" width="100">
                                    </div>
                                @endif
                                <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                <small class="text-muted">Max 2MB. Recommended: 400x400px</small>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Banner Image</label>
                                @if($agent->banner_image)
                                    <div class="mb-2">
                                        <img src="{{ $agent->banner_url }}" alt="Current Banner" class="img-thumbnail" width="200">
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
                                value="{{ old('meta_title', $agent->meta_title) }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="2">{{ old('meta_description', $agent->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('agent-portal.dashboard') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
