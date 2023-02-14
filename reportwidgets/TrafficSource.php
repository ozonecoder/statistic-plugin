<?php namespace Ozc\Statistic\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Exception;

/**
 * TrafficSource Report Widget
 */
class TrafficSource extends ReportWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'TrafficSourceReportWidget';

    public $data = [];
    public $total = 0;

    /**
     * defineProperties for the widget
     */
    public function defineProperties()
    {
        return [
            'title' => [
                'title' => 'Traffic Source',
                'default' => 'Traffic Source',
                'type' => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ],
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

        return $this->makePartial('trafficsource');
    }

    /**
     * Prepares the report widget view data
     */
    public function prepareVars()
    {
        $data = \Ozc\Statistic\Models\TrafficSource::orderBy('count', 'desc')
            ->select('source', 'count')->get();
        $sum = \Ozc\Statistic\Models\TrafficSource::sum('count');

        $this->data = $data;
        $this->total = $sum;
    }

    /**
     * @inheritDoc
     */
    protected function loadAssets()
    {
    }
}
