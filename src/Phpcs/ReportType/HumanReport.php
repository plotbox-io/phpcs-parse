<?php

namespace PlotBox\PhpcsParse\Phpcs\ReportType;

class HumanReport implements Report
{
    /**
     * @inheritDoc
     */
    public function fromString($content)
    {
        throw new NotSupportedException();
    }

    /**
     * @inheritDoc
     */
    public function toString(array $issues)
    {
        if (!count($issues)) {
            return "😃😃😃😃😃 NO STYLE ISSUES FOUND (IN MODIFIED CODE) 😃😃😃😃😃\n";
        }

        $result = '';
        $result .= "😲😲😲😲😲 STYLE ISSUES FOUND (IN MODIFIED CODE) 😲😲😲😲😲\n\n";
        foreach ($issues as $issue) {
            $shortPath = str_replace(getcwd() . '/', '', $issue->getFile());
            $result .= "{$shortPath} ({$issue->getLine()}) - {$issue->getMessage()} ({$issue->getSource()})\n";
        }

        return $result;
    }
}
