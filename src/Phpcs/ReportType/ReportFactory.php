<?php

namespace PlotBox\PhpcsParse\Phpcs\ReportType;

use RuntimeException;

class ReportFactory
{
    /** @var ReportAggregator */
    private $reportAggregator;

    public function __construct()
    {
        $this->reportAggregator = new ReportAggregator();
    }

    /**
     * @param string $type
     * @return Report
     */
    public function getReport($type)
    {
        switch ($type) {
            case 'json':
                return new JsonReport($this->reportAggregator);
            case 'xml':
                return new XmlReport($this->reportAggregator);
            case 'csv':
                return new CsvReport();
            default:
                throw new RuntimeException("Unsupported report type '$type'");
        }
    }
}