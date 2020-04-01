<?php

namespace PlotBox\PhpcsParse\Phpcs\ReportType;

use PlotBox\PhpcsParse\CodeIssue;
use stdClass;

class ReportAggregator
{
    /**
     * Create a report from a set of issues
     *
     * @param CodeIssue[] $issues
     * @return stdClass
     */
    public function getReportData(array $issues)
    {
        $totals = [
            "errors" => 0,
            "warnings" => 0,
            "fixable" => 0,
        ];

        $files = [];
        foreach ($issues as $issue) {
            $filename = $issue->getFile();
            if (!key_exists($filename, $files)) {
                $files[$filename] = (object)[
                    "errors" => 0,
                    "warnings" => 0,
                    "fixable" => 0,
                    "messages" => [],
                ];
            }

            if ($issue->getType() === 'warning') {
                $totals['warnings']++;
                $files[$filename]->warnings++;
            } elseif ($issue->getType() === 'error') {
                $totals['errors']++;
                $files[$filename]->errors++;
            }
            if ($issue->isFixable()) {
                $totals['fixable']++;
                $files[$filename]->fixable++;
            }

            $files[$filename]->messages[] = (object)[
                "message" => $issue->getMessage(),
                "source" => $issue->getSource(),
                "severity" => $issue->getSeverity(),
                "fixable" => $issue->isFixable(),
                "type" => $issue->getType(),
                "line" => $issue->getLine(),
                "column" => $issue->getColumn(),
            ];
        }

        return (object)[
            'totals' => (object)$totals,
            'files' => (object)$files,
        ];
    }
}
