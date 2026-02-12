<?php

namespace Botble\Developer\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Developer\Models\DeveloperProfile;
use Botble\Developer\Models\DeveloperProject;
use Botble\Developer\Policies\DeveloperProfilePolicy;
use Botble\Developer\Policies\DeveloperProjectPolicy;
use Botble\Developer\Services\DeveloperApprovalService;
use Botble\Developer\Services\DeveloperProjectShortcodeService;
use Botble\Developer\Services\DeveloperShortcodeService;
use Botble\Developer\Services\SlugConflictService;
use Botble\Developer\Services\SlugResolverService;
use Botble\Slug\Facades\SlugHelper;
use Botble\Developer\Supports\DeveloperManager;
use Illuminate\Support\Facades\Gate;

class DeveloperServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->singleton(DeveloperManager::class, DeveloperManager::class);
        $this->app->singleton(DeveloperApprovalService::class, DeveloperApprovalService::class);
        $this->app->singleton(SlugConflictService::class, SlugConflictService::class);
        $this->app->singleton(SlugResolverService::class, SlugResolverService::class);
        $this->app->singleton(DeveloperShortcodeService::class, DeveloperShortcodeService::class);
        $this->app->singleton(DeveloperProjectShortcodeService::class, DeveloperProjectShortcodeService::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/developer')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'settings', 'email'])
            ->loadMigrations()
            ->loadRoutes(['web', 'fronts'])
            ->loadAndPublishViews();

        Gate::policy(DeveloperProfile::class, DeveloperProfilePolicy::class);
        Gate::policy(DeveloperProject::class, DeveloperProjectPolicy::class);

        EmailHandler::addTemplateSettings(DEVELOPER_MODULE_SCREEN_NAME, config('plugins.developer.email', []));

        if (function_exists('shortcode')) {
            shortcode()->register(
                'developer-profiles',
                __('Developer Profiles'),
                __('Render approved developer profiles with filtering attributes.'),
                [app(DeveloperShortcodeService::class), 'renderProfiles']
            );

            shortcode()->register(
                'developer-projects',
                __('Developer Projects'),
                __('Render approved developer projects with filtering attributes.'),
                [app(DeveloperProjectShortcodeService::class), 'renderProjects']
            );
        }

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(DeveloperProfile::class, fn() => __('Developer Profile'));
            SlugHelper::setPrefix(DeveloperProfile::class, '', true);
            SlugHelper::setColumnUsedForSlugGenerator(DeveloperProfile::class, 'slug');
        });

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-developer',
                    'priority' => 37,
                    'name' => 'Developer',
                    'icon' => 'ti ti-building-estate',
                    'permissions' => ['plugins.developer'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-developer-profiles',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-developer',
                    'name' => 'Developers',
                    'url' => route('developer.profiles.index'),
                    'permissions' => ['developer.profiles.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-developer-projects',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-developer',
                    'name' => 'Projects',
                    'url' => route('developer.projects.index'),
                    'permissions' => ['developer.projects.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-developer-leads',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-developer',
                    'name' => 'Leads',
                    'url' => route('developer.leads.index'),
                    'permissions' => ['developer.leads.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-developer-approvals',
                    'priority' => 4,
                    'parent_id' => 'cms-plugins-developer',
                    'name' => 'Approvals',
                    'url' => route('developer.approvals.index'),
                    'permissions' => ['developer.approvals.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-developer-settings',
                    'priority' => 5,
                    'parent_id' => 'cms-plugins-developer',
                    'name' => 'Settings',
                    'url' => route('developer.settings.edit'),
                    'permissions' => ['developer.settings'],
                ]);
        });
    }
}
