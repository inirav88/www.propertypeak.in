@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'Complete Your Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white py-4">
                    <h3 class="mb-0">Complete Your Developer Profile</h3>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-building fa-4x text-primary mb-3"></i>
                        <h4>Welcome to PropertyPeak Developer Portal</h4>
                        <p class="text-muted">Complete your profile to start listing your projects and properties.</p>
                    </div>

                    <form action="{{ route('developer-portal.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name *</label>
                                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" required>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Established Year</label>
                                <input type="number" name="established_year" class="form-control" min="1800" max="{{ date('Y') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Office Address</label>
                            <textarea name="office_address" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">About Company</label>
                            <textarea name="description" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Logo</label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Banner Image</label>
                                <input type="file" name="banner_image" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Create Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
