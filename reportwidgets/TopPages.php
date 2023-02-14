<?php namespace Ozc\Statistic\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
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
        $showPagesCount = $this->property('top_pages_count', 5);

        $allCount = PagesCounter::sum('count');
        $topPages = PagesCounter::orderBy('count', 'desc')->select('page', 'title', 'count')
            ->take($showPagesCount)->get()->map(function ($item) use ($allCount) {
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
