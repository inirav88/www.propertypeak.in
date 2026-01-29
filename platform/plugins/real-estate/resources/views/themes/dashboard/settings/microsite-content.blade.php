<x-core::tab.pane id="microsite-tab">
    <div class="microsite-settings-wrapper">
        @if(!auth('account')->user()->hasPackageFeature('microsite'))
            <div class="alert alert-warning">
                <i class="fas fa-lock"></i> {{ __('Microsite feature is not available in your current package.') }}
                <a href="{{ route('public.account.packages') }}">{{ __('Upgrade your package') }}</a>
            </div>
        @else
            <form action="{{ url('account/settings/microsite') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <h4>{{ __('Basic Settings') }}</h4>

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

                <div class="mb-4">
                    <h4>{{ __('Branding') }}</h4>

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

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Save Microsite Settings') }}
                    </button>

                    @if(auth('account')->user()->microsite_enabled && auth('account')->user()->microsite_slug)
                        <a href="{{ route('public.builder.microsite', auth('account')->user()->microsite_slug) }}"
                            class="btn btn-success" target="_blank">
                            <i class="fas fa-external-link-alt"></i> {{ __('View Microsite') }}
                        </a>
                    @endif
                </div>
            </form>
        @endif
    </div>
</x-core::tab.pane>