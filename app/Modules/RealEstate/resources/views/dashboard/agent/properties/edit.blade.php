@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'Edit Property - Agent Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Property: {{ Str::limit($property->title, 40) }}</h1>
        <a href="{{ route('agent-portal.properties.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Properties
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('agent-portal.properties.update', $property->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h5 class="mb-3">Basic Information</h5>

                <div class="mb-3">
                    <label class="form-label">Property Title *</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                        value="{{ old('title', $property->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Listing Type *</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="sale" {{ old('type', $property->type) === 'sale' ? 'selected' : '' }}>For Sale</option>
                            <option value="rent" {{ old('type', $property->type) === 'rent' ? 'selected' : '' }}>For Rent</option>
                            <option value="pg" {{ old('type', $property->type) === 'pg' ? 'selected' : '' }}>PG/Hostel</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Property Type *</label>
                        <select name="property_type" class="form-select @error('property_type') is-invalid @enderror" required>
                            <option value="apartment" {{ old('property_type', $property->property_type) === 'apartment' ? 'selected' : '' }}>Apartment</option>
                            <option value="house" {{ old('property_type', $property->property_type) === 'house' ? 'selected' : '' }}>House</option>
                            <option value="villa" {{ old('property_type', $property->property_type) === 'villa' ? 'selected' : '' }}>Villa</option>
                            <option value="plot" {{ old('property_type', $property->property_type) === 'plot' ? 'selected' : '' }}>Plot/Land</option>
                            <option value="commercial" {{ old('property_type', $property->property_type) === 'commercial' ? 'selected' : '' }}>Commercial</option>
                            <option value="office" {{ old('property_type', $property->property_type) === 'office' ? 'selected' : '' }}>Office Space</option>
                        </select>
                        @error('property_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price (₹) *</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                            value="{{ old('price', $property->price) }}" min="0" step="0.01" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required>{{ old('description', $property->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Location</h5>

                <div class="mb-3">
                    <label class="form-label">Address *</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" required>{{ old('address', $property->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">City *</label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                            value="{{ old('city', $property->city) }}" required>
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">State *</label>
                        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" 
                            value="{{ old('state', $property->state) }}" required>
                        @error('state')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Property Features</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Bedrooms</label>
                        <input type="number" name="bedrooms" class="form-control @error('bedrooms') is-invalid @enderror" 
                            value="{{ old('bedrooms', $property->bedrooms) }}" min="0">
                        @error('bedrooms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Bathrooms</label>
                        <input type="number" name="bathrooms" class="form-control @error('bathrooms') is-invalid @enderror" 
                            value="{{ old('bathrooms', $property->bathrooms) }}" min="0">
                        @error('bathrooms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Carpet Area (sq.ft)</label>
                        <input type="number" name="carpet_area" class="form-control @error('carpet_area') is-invalid @enderror" 
                            value="{{ old('carpet_area', $property->carpet_area) }}" min="0" step="0.01">
                        @error('carpet_area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Current Images</h5>
                @if($property->images && count($property->images) > 0)
                    <div class="row mb-3">
                        @foreach($property->images as $image)
                            <div class="col-md-3 col-sm-4 mb-3">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image) }}" alt="" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No images uploaded yet.</p>
                @endif

                <div class="mb-3">
                    <label class="form-label">Add More Images</label>
                    <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror" 
                        accept="image/*" multiple>
                    <small class="text-muted">Select multiple images. Max 2MB each.</small>
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('agent-portal.properties.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Property</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
