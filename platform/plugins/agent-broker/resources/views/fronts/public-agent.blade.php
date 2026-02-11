@extends(Theme::getThemeNamespace('layouts.base'))

@section('content')
<section class="container py-5">
    <h1 class="mb-3">{{ $profile->account?->name }}</h1>
    <p>{{ $profile->bio }}</p>
    <p><strong>{{ __('Phone') }}:</strong> {{ $profile->contact_phone }}</p>
</section>
@endsection
