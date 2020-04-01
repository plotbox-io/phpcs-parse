<?php

namespace PlotBox\PhpcsParse\Phpcs\ReportType;

use PlotBox\PhpcsParse\CodeIssue;

interface Report
{
    /**
     * Parse out CodeIssue DTOs from the raw text
     *
     * @param $content
     * @return CodeIssue[]
     * @throws NotSupportedException
     */
    public function fromString($content);

    /**
     * Create a report as string from a set of issues
     *
     * @param CodeIssue[] $issues
     * @return string
     * @throws NotSupportedException
     */
    public function toString(array $issues);
}