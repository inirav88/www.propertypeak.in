@extends('plugins/real-estate::themes.dashboard.layouts.master')

@section('content')
    <div class="dashboard-content-wrapper">
        <h2>{{ __('Microsite Settings') }}</h2>
        <p class="text-muted">{{ __('Customize your builder microsite with your own branding') }}</p>

        <form action="{{ url('account/settings/microsite') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card mb-4">
                <div class="card-header">
                    <h4>{{ __('Basic Settings') }}</h4>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>{{ __('Enable Microsite') }}</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="microsite_enabled" id="microsite_enabled"
                                value="1" {{ auth('account')->user()->microsite_enabled ? 'checked' : '' }}>
                            <label class="form-check-label" for="microsite_enabled">
                                {{ __('Activate your microsite') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="microsite_slug">{{ __('Microsite URL Slug') }} *</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ url('builders') }}/</span>
                            <input type="text" class="form-control" name="microsite_slug" id="microsite_slug"
                                value="{{ auth('account')->user()->microsite_slug }}" placeholder="your-company-name">
                            <span class="input-group-text">/microsite</span>
                        </div>
                        <small class="text-muted">{{ __('Use lowercase letters, numbers, and hyphens only') }}</small>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4>{{ __('Branding') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="microsite_logo">{{ __('Logo') }}</label>
                            @if(auth('account')->user()->microsite_logo)
                                <div class="mb-2">
                                    <img src="{{ auth('account')->user()->microsite_logo }}" alt="Logo"
                                        style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="microsite_logo" id="microsite_logo">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="microsite_banner">{{ __('Banner Image') }}</label>
                            @if(auth('account')->user()->microsite_banner)
                                <div class="mb-2">
                                    <img src="{{ auth('account')->user()->microsite_banner }}" alt="Banner"
                                        style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="microsite_banner" id="microsite_banner">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="microsite_primary_color">{{ __('Primary Color') }}</label>
                            <input type="color" class="form-control" name="microsite_primary_color"
                                id="microsite_primary_color"
                                value="{{ auth('account')->user()->microsite_primary_color ?? '#db1d23' }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="microsite_secondary_color">{{ __('Secondary Color') }}</label>
                            <input type="color" class="form-control" name="microsite_secondary_color"
                                id="microsite_secondary_color"
                                value="{{ auth('account')->user()->microsite_secondary_color ?? '#161e2d' }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4>{{ __('About Section') }}</h4>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="microsite_about">{{ __('About Your Company') }}</label>
                        <textarea class="form-control" name="microsite_about" id="microsite_about"
                            rows="5">{{ auth('account')->user()->microsite_about }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h4>{{ __('Social Media Links') }}</h4>
                </div>
                <div class="card-body">
                    @php
                        $socialLinks = auth('account')->user()->microsite_social_links ?? [];
                    @endphp

                    <div class="form-group mb-3">
                        <label for="facebook">{{ __('Facebook') }}</label>
                        <input type="url" class="form-control" name="social_links[facebook]"
                            value="{{ $socialLinks['facebook'] ?? '' }}" placeholder="https://facebook.com/yourpage">
                    </div>

                    <div class="form-group mb-3">
                        <label for="twitter">{{ __('Twitter') }}</label>
                        <input type="url" class="form-control" name="social_links[twitter]"
                            value="{{ $socialLinks['twitter'] ?? '' }}" placeholder="https://twitter.com/yourhandle">
                    </div>

                    <div class="form-group mb-3">
                        <label for="instagram">{{ __('Instagram') }}</label>
                        <input type="url" class="form-control" name="social_links[instagram]"
                            value="{{ $socialLinks['instagram'] ?? '' }}" placeholder="https://instagram.com/yourhandle">
                    </div>

                    <div class="form-group mb-3">
                        <label for="linkedin">{{ __('LinkedIn') }}</label>
                        <input type="url" class="form-control" name="social_links[linkedin]"
                            value="{{ $socialLinks['linkedin'] ?? '' }}"
                            placeholder="https://linkedin.com/company/yourcompany">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Save Settings') }}
                </button>

                @if(auth('account')->user()->microsite_enabled && auth('account')->user()->microsite_slug)
                    <a href="{{ route('public.builder.microsite', auth('account')->user()->microsite_slug) }}"
                        class="btn btn-success" target="_blank">
                        <i class="fas fa-external-link-alt"></i> {{ __('View Microsite') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
@endsection