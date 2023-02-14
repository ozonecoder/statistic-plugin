<?php
namespace Ozc\Statistic;

use Backend;
use Ozc\Statistic\ReportWidgets\TopPages;
use Ozc\Statistic\ReportWidgets\TrafficSource;
use Ozc\Statistic\ReportWidgets\VisitorsCounter;
use System\Classes\PluginBase;

/**
 * statistic Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'ozc Statistic',
            'description' => 'ozc Statistic Plugin shows "Visitors Count", "Top pages" and "Traffic source" in Dashboard widgets',
            'author' => 'Ozonecoders | Pierre Otto',
            'homepage' => 'https://www.ozonecoders.de',
            'icon' => 'icon-line-chart',
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Ozc\Statistic\Components\Tracking' => 'ozcTracking',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'ozc.statistic.view' => [
                'tab' => 'OZC PLUGINS',
                'label' => 'ozc Statistic :: View statistic on Dashboard',
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'statistic' => [
                'label' => 'statistic',
                'url' => Backend::url('ozc/statistic/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['ozc.statistic.*'],
                'order' => 500,
            ],
        ];
    }

    public function registerReportWidgets()
    {
        return [
            TopPages::class => [
                'label' => 'ozc Top Pages',
                'context' => 'dashboard'
            ],
            VisitorsCounter::class => [
                'label' => 'ozc Visitors Counter',
                'context' => 'dashboard'
            ],
            TrafficSource::class => [
                'label' => 'ozc Traffic Source',
                'context' => 'dashboard'
            ]
        ];
    }
}
