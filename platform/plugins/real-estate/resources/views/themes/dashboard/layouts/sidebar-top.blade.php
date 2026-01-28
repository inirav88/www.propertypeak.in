<div class="ps-block--user-wellcome">
    <div class="ps-block__left">
        <img src="{{ auth('account')->user()->avatar_url }}" alt="{{ auth('account')->user()->name }}"
            class="avatar avatar-lg" />
    </div>
    <div class="ps-block__right">
        <p>{{ __('Hello') }}, {{ auth('account')->user()->name }}</p>
        <small>{{ __('Joined on :date', ['date' => auth('account')->user()->created_at->translatedFormat('M d, Y')]) }}</small>
    </div>
    <a href="#" title="{{ trans('plugins/real-estate::dashboard.header_logout_link') }}"
        onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
        <x-core::icon name="ti ti-logout" />
    </a>
    <form id="sidebar-logout-form" action="{{ route('public.account.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>

@if (RealEstateHelper::isEnabledCreditsSystem())
    <div class="ps-block--earning-count">
        {!! apply_filters('real_estate_account_dashboard_sidebar_top_account_credits_before', null) !!}

        <small>{{ __('Credits') }}</small>
        <h3 class="my-2">{{ number_format(auth('account')->user()->credits) }}</h3>
        <a href="{{ route('public.account.packages') }}" target="_blank">
            {{ __('Buy credits') }}
        </a>

        {!! apply_filters('real_estate_account_dashboard_sidebar_top_account_credits_after', null) !!}
    </div>
@endif