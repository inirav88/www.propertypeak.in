<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $builder->name }} - {{ __('Builder Microsite') }}</title>

    <style>
        :root {
            --primary-color:
                {{ $builder->microsite_primary_color ?? '#db1d23' }}
            ;
            --secondary-color:
                {{ $builder->microsite_secondary_color ?? '#161e2d' }}
            ;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .microsite-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .microsite-logo {
            max-width: 200px;
            margin-bottom: 20px;
        }

        .microsite-banner {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .about-section {
            padding: 60px 20px;
            background: #f8f9fa;
        }

        .projects-section,
        .properties-section {
            padding: 60px 20px;
        }

        .section-title {
            font-size: 32px;
            margin-bottom: 30px;
            color: var(--secondary-color);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-content {
            padding: 20px;
        }

        .card-title {
            font-size: 20px;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }

        .card-price {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .social-links {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }

        .social-links a {
            color: white;
            font-size: 24px;
            transition: opacity 0.3s;
        }

        .social-links a:hover {
            opacity: 0.8;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .verified-badge {
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    @if($builder->microsite_banner)
        <img src="{{ RvMedia::getImageUrl($builder->microsite_banner) }}" alt="{{ $builder->name }}"
            class="microsite-banner">
    @endif

    <div class="microsite-header">
        <div class="container">
            @if($builder->microsite_logo)
                <img src="{{ RvMedia::getImageUrl($builder->microsite_logo) }}" alt="{{ $builder->name }}"
                    class="microsite-logo">
            @endif

            <h1>{{ $builder->name }} {!! $builder->badge !!}</h1>
            <p>{{ $builder->description }}</p>

            @if($builder->microsite_website || $builder->microsite_address)
                <div class="contact-info" style="margin-top: 20px; font-size: 16px;">
                    @if($builder->microsite_website)
                        <p><i class="fas fa-globe"></i> <a href="{{ $builder->microsite_website }}" target="_blank"
                                style="color: white; text-decoration: underline;">{{ $builder->microsite_website }}</a></p>
                    @endif
                    @if($builder->microsite_address)
                        <p><i class="fas fa-map-marker-alt"></i> {{ $builder->microsite_address }}</p>
                    @endif
                </div>
            @endif

            @if($builder->microsite_social_links)
                <div class="social-links">
                    @php
                        $socialLinks = is_string($builder->microsite_social_links)
                            ? json_decode($builder->microsite_social_links, true)
                            : $builder->microsite_social_links;
                    @endphp
                    @if(isset($socialLinks['facebook']))
                        <a href="{{ $socialLinks['facebook'] }}" target="_blank"><i class="fab fa-facebook"></i></a>
                    @endif
                    @if(isset($socialLinks['twitter']))
                        <a href="{{ $socialLinks['twitter'] }}" target="_blank"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if(isset($socialLinks['instagram']))
                        <a href="{{ $socialLinks['instagram'] }}" target="_blank"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(isset($socialLinks['linkedin']))
                        <a href="{{ $socialLinks['linkedin'] }}" target="_blank"><i class="fab fa-linkedin"></i></a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($builder->microsite_about)
        <div class="about-section">
            <div class="container">
                <h2 class="section-title">{{ __('About Us') }}</h2>
                <p>{{ $builder->microsite_about }}</p>
            </div>
        </div>
    @endif

    @if($ongoingProjects->count() > 0)
        <div class="projects-section">
            <div class="container">
                <h2 class="section-title">{{ __('Ongoing Projects') }}</h2>
                <div class="grid">
                    @foreach($ongoingProjects as $project)
                        <div class="card">
                            <img src="{{ $project->image_thumb }}" alt="{{ $project->name }}">
                            <div class="card-content">
                                <h3 class="card-title">{{ $project->name }}</h3>
                                <p>{{ $project->location }}</p>
                                <a href="{{ $project->url }}" class="btn-primary">{{ __('View Details') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $ongoingProjects->links() }}
            </div>
        </div>
    @endif

    @if($completedProjects->count() > 0)
        <div class="projects-section" style="background-color: #f8f9fa;">
            <div class="container">
                <h2 class="section-title">{{ __('Completed Projects') }}</h2>
                <div class="grid">
                    @foreach($completedProjects as $project)
                        <div class="card">
                            <img src="{{ $project->image_thumb }}" alt="{{ $project->name }}">
                            <div class="card-content">
                                <h3 class="card-title">{{ $project->name }}</h3>
                                <p>{{ $project->location }}</p>
                                <a href="{{ $project->url }}" class="btn-primary">{{ __('View Details') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $completedProjects->links() }}
            </div>
        </div>
    @endif

    @if($properties->count() > 0)
        <div class="properties-section">
            <div class="container">
                <h2 class="section-title">{{ __('Our Properties') }}</h2>
                <div class="grid">
                    @foreach($properties as $property)
                        <div class="card">
                            <img src="{{ $property->image_thumb }}" alt="{{ $property->name }}">
                            <div class="card-content">
                                <h3 class="card-title">{{ $property->name }}</h3>
                                <p>{{ $property->location }}</p>
                                <div class="card-price">{{ $property->price_formatted }}</div>
                                <a href="{{ $property->url }}" class="btn-primary">{{ __('View Details') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $properties->links() }}
            </div>
        </div>
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>

</html>