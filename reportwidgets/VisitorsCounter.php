<?php namespace Ozc\Statistic\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Carbon\Carbon;
use Exception;

/**
 * VisitorsCounter Report Widget
 */
class VisitorsCounter extends ReportWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'VisitorsCounterReportWidget';

    public $dataChart;

    /**
     * defineProperties for the widget
     */
    public function defineProperties()
    {
        return [
            'title' => [
                'title' => 'Visitors Counter',
                'default' => 'Visitors Counter',
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ],
            'days' => [
                'title' => 'Number of days to display data for',
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

        return $this->makePartial('visitorscounter');
    }

    /**
     * Prepares the report widget view data
     */
    public function prepareVars()
    {
        $from = Carbon::today()->format('Y-m-d');
        $to = Carbon::today()->subDays(intval($this->property('days')) - 1)->format('Y-m-d');

        $data = \Ozc\Statistic\Models\VisitorsCounter::whereBetween('date', [$to, $from])
            ->select('date', 'count')->get();

        $dataChart = [];
        foreach ($data as $item) {
            $dataChart[] = '['.implode(', ', [Carbon::parse($item->date)->timestamp * 1000, $item->count]).']';
        }

        $this->dataChart = implode(',', $dataChart);
    }

    /**
     * @inheritDoc
     */
    protected function loadAssets()
    {
    }
}
