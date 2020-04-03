<?php

namespace PlotBox\PhpcsParse\Phpcs\ReportType;

use PlotBox\PhpcsParse\CodeIssue;

class JsonReport implements Report
{
    /** @var ReportAggregator */
    private $reportAggregator;

    public function __construct(ReportAggregator $reportAggregator)
    {
        $this->reportAggregator = $reportAggregator;
    }

    /**
     * @inheritDoc
     */
    public function toString(array $issues)
    {
        //{
        //                   "totals":{
        //                      "errors":34,
        //                      "warnings":2,
        //                      "fixable":17
        //                   },
        //                   "files":{
        //                      "\/app\/tests\/integration\/fixtures\/phpcs-shim-fixture\/src\/AnotherBadClass.php":{
        //                         "errors":34,
        //                         "warnings":2,
        //                         "messages":[
        //                            {
        //                               "message":"A file should declare new symbols (classes, functions, constants, etc.) and cause no other side effects, or it should execute logic with side effects, but should not do both. The first symbol is defined on line 8 and the first side effect is on line 29.",
        //                               "source":"PSR1.Files.SideEffects.FoundWithSymbols",
        //                               "severity":5,
        //                               "fixable":false,
        //                               "type":"WARNING",
        //                               "line":1,
        //                               "column":1
        //                            }
        //                         ]
        //                      }
        //                   }
        //                }


        $reportData = $this->reportAggregator->getReportData($issues);
        foreach ($reportData->files as $fileKey => $file) {
            foreach ($file->messages as $error) {
                $error->type = strtoupper($error->type);
            }
        }

        return json_encode($reportData);
    }

    /**
     * @inheritDoc
     */
    public function fromString($content)
    {
        $jsonData = json_decode($content);

        $parsedResults = [];
        foreach($jsonData->files as $fileName => $details){
            foreach($details->messages as $issue){
                $parsedResults[] = new CodeIssue(
                    $fileName,
                    $issue->line,
                    $issue->column,
                    $issue->source,
                    $issue->message,
                    $issue->type,
                    $issue->severity,
                    $issue->fixable
                );
            }
        }
        
        return $parsedResults;
    }
}
