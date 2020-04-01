<?php

namespace PlotBox\PhpcsParse;

use PlotBox\PhpcsParse\Phpcs\ReportType\ReportFactory;

class Converter
{
    /** @var ReportFactory */
    private $reportFactory;

    public function __construct()
    {
        $this->reportFactory = new ReportFactory();
    }

    /**
     * @param string $content
     * @param string $fromType
     * @param string $toType
     * @return string
     * @throws Phpcs\ReportType\NotSupportedException
     */
    public function convert($content, $fromType, $toType)
    {
        $fromReport = $this->reportFactory->getReport($fromType);
        $toReport = $this->reportFactory->getReport($toType);

        $parsedIssues = $fromReport->fromString($content);
        return $toReport->toString($parsedIssues);
    }
}
