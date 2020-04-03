<?php

namespace PlotBox\PhpcsParse\Phpcs\ReportType;

use PlotBox\PhpcsParse\CodeIssue;
use RuntimeException;

class CsvReport implements Report
{
    /**
     * @inheritDoc
     */
    public function fromString($content)
    {
        $foundIssues = explode("\n", $content);
        if (preg_match('/^ERROR:/', $foundIssues[0])) {
            throw new RuntimeException($foundIssues[0]);
        }

        $parsedIssues = [];
        $first = true;
        $issueData = [];
        foreach ($foundIssues as $foundIssue) {
            if ($first) {
                $first = false;
                continue;
            }

            // Use appending and check for each item to get around code sniffer
            // output bug where some csv lines are split in two (probably relating
            // to double quotes handling on phpcs side)
            $newFields = str_getcsv($foundIssue);
            if (count($issueData) === 0) {
                $issueData = $newFields;
            } else {
                // Append first item to last record of existing set
                // then just iteratively add the rest
                $currentCount = count($issueData);
                $issueData[$currentCount - 1] .= $newFields[0];
                unset($newFields[0]);
                foreach ($newFields as $newField) {
                    $issueData[] = $newField;
                }
            }

            if (count($issueData) === 8) {
                list($absolutePath, $line, $column, $type, $message, $source, $severity, $fixable) = $issueData;

                $parsedIssues[] = new CodeIssue(
                    $absolutePath,
                    (int) $line,
                    (int) $column,
                    $source,
                    $message,
                    $type,
                    (int) $severity,
                    $fixable === 'true'
                );
                $issueData = [];
            }
        }

        return $parsedIssues;
    }

    /**
     * @inheritDoc
     */
    public function toString(array $issues)
    {
        throw new NotSupportedException();
    }
}
