@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'Complete Your Profile')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white py-4">
                    <h3 class="mb-0">Complete Your Agent Profile</h3>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-tie fa-4x text-primary mb-3"></i>
                        <h4>Welcome to PropertyPeak Agent Portal</h4>
                        <p class="text-muted">Complete your profile to start listing properties.</p>
                    </div>

                    <form action="{{ route('agent-portal.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Designation</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g., Senior Property Consultant">
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
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="4" placeholder="Tell clients about yourself and your experience..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="avatar" class="form-control" accept="image/*">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" name="experience_years" class="form-control" min="0">
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
