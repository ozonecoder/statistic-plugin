<?php namespace Ozc\Statistic\Components;

use Carbon\Carbon;
use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Cms\Classes\Theme;
use Ozc\Statistic\Models\PagesCounter;
use Ozc\Statistic\Models\TrafficSource;
use Ozc\Statistic\Models\VisitorsCounter;
use RainLab\Pages\Classes\Router;
use Request;

/**
 * Tracking Component
 */
class Tracking extends ComponentBase
{
    const SPECIAL_DOMAINS = ['com', 'org', 'gov', 'edu', 'net', 'mil', 'int'];
    public function componentDetails()
    {
        return [
            'name' => 'ozc Tracking Component',
            'description' => 'Tracking component for pages '
        ];
    }

    public function defineProperties()
    {
        return [
            'visitors_counter' => [
                'title'       => 'visitors_counter',
                'description' => 'tracking visitors counter for days',
                'type'        => 'checkbox',
                'default'     => true,
            ],
            'top_pages' => [
                'title'       => 'top_pages',
                'description' => 'count top pages accessed',
                'type'        => 'checkbox',
                'default'     => true,
            ],
            'traffic_source' => [
                'title'       => 'traffic_source',
                'description' => 'count references',
                'type'        => 'checkbox',
                'default'     => true,
            ],
        ];
    }

    public function onRun()
    {
        if($this->property('top_pages')) {
            $currentPageUrl = Request::path();
            if($currentPageUrl != '/')
                $currentPageUrl = '/' . $currentPageUrl;

            $currentPageCounter = PagesCounter::where('page', $currentPageUrl)->first();
            if(is_null($currentPageCounter)) {

                $themePage = $this->getCurrentPage();
                PagesCounter::create([
                    'page' => $currentPageUrl,
                    'title' => $themePage->title
                ]);
            }
            else {
                $currentPageCounter->update([
                    'count' => $currentPageCounter->count + 1
                ]);
            }
        }
        if($this->property('visitors_counter')) {
            $today = Carbon::today()->format('Y-m-d');

            $counter = VisitorsCounter::where('date', $today)->first();
            if(is_null($counter)) {
                $counter = VisitorsCounter::create(['date' => $today]);
            }
            else {
                $counter->update([
                    'count' => $counter->count + 1
                ]);
            }
        }
        if($this->property('traffic_source')) {
            $sourceQuery = $_SERVER['HTTP_REFERER'] ?? null;
            if(strpos($sourceQuery, Request::getHost()))
                return;
            // http://hust.edu.vn => hust.edu.vn
            // http://ctsv.hust.edu.vn => hust.edu.vn
            // http://www.google.com => google.com
            // http://google.com => google.com
            if(empty($sourceQuery)){
                $source = 'direct';
            }
            else {
                $sourceUrl = explode('.', explode('/',$sourceQuery)[2]);
                if(count($sourceUrl) > 2 && !in_array($sourceUrl[count($sourceUrl) - 2], self::SPECIAL_DOMAINS)){
                    array_shift($sourceUrl);
                }
                $source = implode('.', $sourceUrl);
            }
            $counter = TrafficSource::where('source', $source)->first();
            if(is_null($counter)) {
                $counter = TrafficSource::create(['source' => $source]);
            }
            else {
                $counter->update([
                    'count' => $counter->count + 1
                ]);
            }
        }
    }

    private function getCurrentPage() {
        $url = Request::path();
        $theme = Theme::getActiveTheme();
        $router = new Router($theme);
        $currentPage = is_null($this->page->baseFileName)
            ? $router->findByUrl($url) : Page::load($theme, $this->page->baseFileName);

        return $currentPage;
    }
}
