<?php

namespace Botble\AgentBroker\Providers;

use Botble\AgentBroker\Models\AgentProfile;
use Botble\AgentBroker\Policies\AgentProfilePolicy;
use Botble\AgentBroker\Services\AgentBrokerApprovalService;
use Botble\AgentBroker\Supports\AgentBrokerManager;
use Botble\AgentBroker\Services\AgentBrokerShortcodeService;
use Botble\AgentBroker\Services\AgentPropertyShortcodeService;
use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\EmailHandler;
use Botble\Slug\Facades\SlugHelper;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Support\Facades\Gate;

class AgentBrokerServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->singleton(AgentBrokerManager::class, AgentBrokerManager::class);
        $this->app->singleton(AgentBrokerApprovalService::class, AgentBrokerApprovalService::class);
        $this->app->singleton(AgentBrokerShortcodeService::class, AgentBrokerShortcodeService::class);
        $this->app->singleton(AgentPropertyShortcodeService::class, AgentPropertyShortcodeService::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/agent-broker')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'settings', 'email'])
            ->loadMigrations()
            ->loadRoutes(['web', 'fronts'])
            ->loadAndPublishViews();

        Gate::policy(AgentProfile::class, AgentProfilePolicy::class);

        EmailHandler::addTemplateSettings(AGENT_BROKER_MODULE_SCREEN_NAME, config('plugins.agent-broker.email', []));

        if (function_exists('shortcode')) {
            shortcode()->register(
                'agent-profiles',
                __('Agent Profiles'),
                __('Render approved agent profiles with filtering attributes.'),
                [app(AgentBrokerShortcodeService::class), 'renderProfiles']
            );

            shortcode()->register(
                'agent-properties',
                __('Agent Properties'),
                __('Render approved properties for active agents with filtering attributes.'),
                [app(AgentPropertyShortcodeService::class), 'renderProperties']
            );
        }

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(AgentProfile::class, fn() => __('Agent Profile'));
            SlugHelper::setPrefix(AgentProfile::class, '', true);
            SlugHelper::setColumnUsedForSlugGenerator(AgentProfile::class, 'slug');
        });

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-agent-broker',
                    'priority' => 38,
                    'name' => 'Agent Broker',
                    'icon' => 'ti ti-user-star',
                    'permissions' => ['plugins.agent-broker'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-agent-broker-profiles',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-agent-broker',
                    'name' => 'Agents',
                    'url' => route('agent-broker.profiles.index'),
                    'permissions' => ['agent-broker.profiles.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-agent-broker-leads',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-agent-broker',
                    'name' => 'Leads',
                    'url' => route('agent-broker.leads.index'),
                    'permissions' => ['agent-broker.leads.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-agent-broker-approvals',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-agent-broker',
                    'name' => 'Approvals',
                    'url' => route('agent-broker.approvals.index'),
                    'permissions' => ['agent-broker.approvals.index'],
                ])
                ->registerItem([
                    'id' => 'cms-plugins-agent-broker-settings',
                    'priority' => 4,
                    'parent_id' => 'cms-plugins-agent-broker',
                    'name' => 'Settings',
                    'url' => route('agent-broker.settings.edit'),
                    'permissions' => ['agent-broker.settings'],
                ]);
        });
    }
}
