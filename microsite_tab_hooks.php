<?php

// Add this to platform/plugins/real-estate/src/Providers/HookServiceProvider.php
// in the boot() method

add_filter('account_settings_register_content_tabs', function ($html) {
    if (auth('account')->check() && auth('account')->user()->hasPackageFeature('microsite')) {
        $html .= view('plugins/real-estate::themes.dashboard.settings.microsite-tab')->render();
    }
    return $html;
});

add_filter('account_settings_register_content_tab_inside', function ($html) {
    if (auth('account')->check() && auth('account')->user()->hasPackageFeature('microsite')) {
        $html .= view('plugins/real-estate::themes.dashboard.settings.microsite-content')->render();
    }
    return $html;
});
