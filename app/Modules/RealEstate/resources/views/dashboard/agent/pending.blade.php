@extends(Theme::getThemeNamespace('layouts.default'))

@section('title', 'Account Pending Approval')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="card border-0 shadow">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-5x text-warning mb-3"></i>
                        <h2 class="h3">Account Pending Approval</h2>
                    </div>
                    
                    <p class="text-muted mb-4">
                        Your agent account is currently under review. Our admin team will verify your information and approve your account shortly.
                    </p>
                    
                    <div class="alert alert-info" role="alert">
                        <h5 class="alert-heading">What happens next?</h5>
                        <ul class="list-unstyled mb-0 text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Our team will review your profile</li>
                            <li><i class="fas fa-check text-success me-2"></i>You'll receive an email notification once approved</li>
                            <li><i class="fas fa-check text-success me-2"></i>You can then start listing properties</li>
                        </ul>
                    </div>
                    
                    <p class="text-muted">
                        This usually takes 24-48 hours. If you have any questions, please contact our support team.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
