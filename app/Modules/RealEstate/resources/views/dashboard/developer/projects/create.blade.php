@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'Create Project - Developer Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Create New Project</h1>
        <a href="{{ route('developer-portal.projects.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Projects
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('developer-portal.projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <h5 class="mb-3">Basic Information</h5>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Project Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Project Type *</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="">Select Type</option>
                            <option value="residential" {{ old('type') === 'residential' ? 'selected' : '' }}>Residential</option>
                            <option value="commercial" {{ old('type') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="mixed_use" {{ old('type') === 'mixed_use' ? 'selected' : '' }}>Mixed Use</option>
                            <option value="industrial" {{ old('type') === 'industrial' ? 'selected' : '' }}>Industrial</option>
                            <option value="retail" {{ old('type') === 'retail' ? 'selected' : '' }}>Retail</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Project Status *</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">Select Status</option>
                            <option value="upcoming" {{ old('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">RERA Number</label>
                        <input type="text" name="rera_number" class="form-control @error('rera_number') is-invalid @enderror" 
                            value="{{ old('rera_number') }}">
                        @error('rera_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Short Description</label>
                    <input type="text" name="short_description" class="form-control @error('short_description') is-invalid @enderror" 
                        value="{{ old('short_description') }}" placeholder="Brief summary of the project (max 500 characters)">
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Description *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Location</h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Address *</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" required>{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">City *</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                            value="{{ old('city') }}" required>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">State *</label>
                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" 
                            value="{{ old('state') }}" required>
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Project Details</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Launch Date</label>
                        <input type="date" name="launch_date" class="form-control @error('launch_date') is-invalid @enderror" 
                            value="{{ old('launch_date') }}">
                        @error('launch_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Possession Date</label>
                        <input type="date" name="possession_date" class="form-control @error('possession_date') is-invalid @enderror" 
                            value="{{ old('possession_date') }}">
                        @error('possession_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total Units</label>
                        <input type="number" name="total_units" class="form-control @error('total_units') is-invalid @enderror" 
                            value="{{ old('total_units') }}" min="1">
                        @error('total_units')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price Starting From (₹)</label>
                        <input type="number" name="price_starting_from" class="form-control @error('price_starting_from') is-invalid @enderror" 
                            value="{{ old('price_starting_from') }}" min="0" step="0.01">
                        @error('price_starting_from')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Amenities</label>
                    <div class="row">
                        @php
                            $amenities = ['Swimming Pool', 'Gym', 'Club House', 'Parking', 'Garden', 'Security', 'Power Backup', 'Lift', 'Children Play Area', 'Sports Facilities'];
                        @endphp
                        @foreach($amenities as $amenity)
                            <div class="col-md-3 col-sm-6">
                                <div class="form-check">
                                    <input type="checkbox" name="amenities[]" value="{{ $amenity }}" 
                                        class="form-check-input" id="amenity_{{ $loop->index }}"
                                        {{ in_array($amenity, old('amenities', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amenity_{{ $loop->index }}">{{ $amenity }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('amenities')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Media</h5>

                <div class="mb-3">
                    <label class="form-label">Gallery Images</label>
                    <input type="file" name="gallery_images[]" class="form-control @error('gallery_images') is-invalid @enderror" 
                        accept="image/*" multiple>
                    <small class="text-muted">You can select multiple images. Max 2MB each.</small>
                    @error('gallery_images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('gallery_images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Project Brochure (PDF)</label>
                    <input type="file" name="brochure" class="form-control @error('brochure') is-invalid @enderror" 
                        accept=".pdf">
                    <small class="text-muted">Max 10MB</small>
                    @error('brochure')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('developer-portal.projects.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
