@extends(Theme::getThemeNamespace('layouts.base'))

@section('content')
<section class="container py-5">
    <h1 class="mb-3">{{ $profile->company_name }}</h1>
    <p>{{ $profile->about }}</p>

    <h3 class="mt-4">Projects</h3>
    <ul>
        @foreach($profile->projects as $project)
            <li>{{ $project->name }} ({{ $project->project_status }})</li>
        @endforeach
    </ul>
</section>
@endsection
