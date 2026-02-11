<?php

namespace Botble\Crm\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Crm\Services\CrmAnalyticsService;
use Botble\Crm\Services\CrmPipelineService;
use Botble\Crm\Supports\CrmManager;

class CrmServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->singleton(CrmManager::class, CrmManager::class);
        $this->app->singleton(CrmPipelineService::class, CrmPipelineService::class);
        $this->app->singleton(CrmAnalyticsService::class, function () {
            return new CrmAnalyticsService();
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/crm')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadRoutes(['web'])
            ->loadAndPublishViews();

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()->registerItem([
                'id' => 'cms-plugins-crm',
                'priority' => 39,
                'name' => 'CRM',
                'icon' => 'ti ti-layout-kanban',
                'permissions' => ['plugins.crm'],
                'url' => route('crm.dashboard'),
            ]);
        });
    }
}
