@extends('plugins/real-estate::themes.dashboard.layouts.master')

@section('content')
    <div class="settings-microsite">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __('Microsite Settings') }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('public.account.settings.microsite') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="microsite_enabled" class="form-label">{{ __('Enable Microsite') }}</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="microsite_enabled" name="microsite_enabled"
                                value="1" @if(old('microsite_enabled', $account->microsite_enabled)) checked @endif>
                            <label class="form-check-label"
                                for="microsite_enabled">{{ __('Show my microsite publicly') }}</label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="microsite_slug" class="form-label">{{ __('Microsite Slug') }}</label>
                        <input type="text" class="form-control @error('microsite_slug') is-invalid @enderror"
                            id="microsite_slug" name="microsite_slug"
                            value="{{ old('microsite_slug', $account->microsite_slug) }}" placeholder="your-company-name">
                        @error('microsite_slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            {{ __('This will be your public URL: ') }}
                            @if($account->microsite_slug)
                                <a href="{{ route('public.builder.microsite', $account->microsite_slug) }}" target="_blank">
                                    {{ route('public.builder.microsite', $account->microsite_slug) }} <i
                                        class="ti ti-external-link"></i>
                                </a>
                            @else
                                {{ url('builders') }}/<strong>your-slug</strong>/microsite
                            @endif
                        </small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="microsite_logo" class="form-label">{{ __('Logo') }}</label>
                                <input type="file" class="form-control" id="microsite_logo" name="microsite_logo"
                                    accept="image/*">
                                @if($account->microsite_logo)
                                    <div class="mt-2">
                                        <img src="{{ RvMedia::getImageUrl($account->microsite_logo) }}" alt="Logo"
                                            style="height: 50px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="microsite_banner" class="form-label">{{ __('Banner Image') }}</label>
                                <input type="file" class="form-control" id="microsite_banner" name="microsite_banner"
                                    accept="image/*">
                                @if($account->microsite_banner)
                                    <div class="mt-2">
                                        <img src="{{ RvMedia::getImageUrl($account->microsite_banner) }}" alt="Banner"
                                            style="height: 50px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="microsite_website" class="form-label">{{ __('Website URL') }}</label>
                                <input type="url" class="form-control" id="microsite_website" name="microsite_website"
                                    value="{{ old('microsite_website', $account->microsite_website) }}"
                                    placeholder="https://example.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="microsite_address" class="form-label">{{ __('Office Address') }}</label>
                                <input type="text" class="form-control" id="microsite_address" name="microsite_address"
                                    value="{{ old('microsite_address', $account->microsite_address) }}"
                                    placeholder="123 Main St">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="microsite_primary_color" class="form-label">{{ __('Primary Color') }}</label>
                                <input type="color" class="form-control form-control-color" id="microsite_primary_color"
                                    name="microsite_primary_color"
                                    value="{{ old('microsite_primary_color', $account->microsite_primary_color ?? '#000000') }}"
                                    title="Choose your color">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="microsite_secondary_color"
                                    class="form-label">{{ __('Secondary Color') }}</label>
                                <input type="color" class="form-control form-control-color" id="microsite_secondary_color"
                                    name="microsite_secondary_color"
                                    value="{{ old('microsite_secondary_color', $account->microsite_secondary_color ?? '#ffffff') }}"
                                    title="Choose your color">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="microsite_about" class="form-label">{{ __('About Us') }}</label>
                        <textarea class="form-control" id="microsite_about" name="microsite_about"
                            rows="5">{{ old('microsite_about', $account->microsite_about) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Social Links') }}</label>
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <input type="url" class="form-control" name="social_links[facebook]"
                                    value="{{ old('social_links.facebook', $account->microsite_social_links['facebook'] ?? '') }}"
                                    placeholder="Facebook URL">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="url" class="form-control" name="social_links[twitter]"
                                    value="{{ old('social_links.twitter', $account->microsite_social_links['twitter'] ?? '') }}"
                                    placeholder="Twitter URL">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="url" class="form-control" name="social_links[instagram]"
                                    value="{{ old('social_links.instagram', $account->microsite_social_links['instagram'] ?? '') }}"
                                    placeholder="Instagram URL">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="url" class="form-control" name="social_links[linkedin]"
                                    value="{{ old('social_links.linkedin', $account->microsite_social_links['linkedin'] ?? '') }}"
                                    placeholder="LinkedIn URL">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection