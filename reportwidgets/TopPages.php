<?php namespace Ozc\Statistic\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Carbon\Carbon;
use Exception;
use Ozc\Statistic\Models\PagesCounter;

/**
 * TopPages Report Widget
 */
class TopPages extends ReportWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'TopPagesReportWidget';

    public $data = [];

    /**
     * defineProperties for the widget
     */
    public function defineProperties()
    {
        return [
            'title' => [
                'title' => 'Top Pages',
                'default' => 'Top Pages',
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ],
            'top_pages_count' => [
                'title' => 'Number of Top Pages that will be displayed',
                'default' => '5',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$'
            ],
            'days' => [
                'title' => 'Number of days',
                'default' => '7',
                'type' => 'string',
                'validation' => [
                    'regex' => [
                        'message' => 'The days property can contain only numeric symbols.',
                        'pattern' => '^[0-9]+$'
                    ]
                ]
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        try {
            $this->prepareVars();
        }
        catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }

        return $this->makePartial('toppages');
    }

    /**
     * Prepares the report widget view data
     */
    public function prepareVars()
    {
        $from = Carbon::today()->format('Y-m-d');
        $to = Carbon::today()->subDays(intval($this->property('days')) - 1)->format('Y-m-d');

        $showPagesCount = $this->property('top_pages_count', 5);

        $allCount = PagesCounter::whereBetween('date', [$to, $from])->sum('count');
        $topPages = PagesCounter::whereBetween('date', [$to, $from])
            ->select('page', 'title' )
            ->selectRaw('sum(count) as page_count')
            ->groupBy('page', 'title')
            ->orderBy('page_count', 'desc')
            ->take($showPagesCount)
            ->get()
            ->map(function ($item) use ($allCount) {
                $item->count = $item->page_count;
                $item->percentage = number_format($item->count / $allCount * 100.0, 2);
                return $item;
            });

        $this->data = $topPages;
    }

    /**
     * @inheritDoc
     */
    protected function loadAssets()
    {
    }
}
