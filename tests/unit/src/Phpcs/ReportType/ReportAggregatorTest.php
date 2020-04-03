<?php

namespace PlotBox\PhpcsParse\Phpcs\ReportType;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use PlotBox\PhpcsParse\CodeIssue;
use PlotBox\PhpcsParse\RelativeFile;
use stdClass;

class ReportAggregatorTest extends MockeryTestCase
{
    /** @var ReportAggregator */
    private $reportAggregator;

    public function setUp()
    {
        $this->reportAggregator = new ReportAggregator();
    }

    public function testGetReportData()
    {
        $issues = $this->getIssues();
        $result = $this->reportAggregator->getReportData($issues);
        $this->assertEquals($this->getExpected(), $result);
    }

    /**
     * @return mixed[]
     */
    private function getIssues()
    {
        return [
            new CodeIssue(
                new RelativeFile('src/AnotherBadClass.php'),
                1,
                1,
                "PSR1.Files.SideEffects.FoundWithSymbols",
                "A file should declare new symbols (classes, functions, constants, etc.) and cause no other side effects, or it should execute logic with side effects, but should not do both. The first symbol is defined on line 8 and the first side effect is on line 29.",
                "warning",
                5,
                false
            ),
            new CodeIssue(
                new RelativeFile('src/AnotherBadClass.php'),
                14,
                1,
                '..DisallowContiguousNewlines.ContiguousNewlines',
                "Contiguous blank lines found",
                "error",
                5,
                true
            ),
            new CodeIssue(
                new RelativeFile('src/SomeOtherBadClass.php'),
                123,
                5,
                '..DisallowContiguousNewlines.ContiguousNewlines',
                "Contiguous blank lines found",
                "error",
                5,
                false
            ),
        ];
    }

    /**
     * @return stdClass
     */
    private function getExpected()
    {
        return (object) [
            'totals' =>
                (object) [
                    'errors' => 2,
                    'warnings' => 1,
                    'fixable' => 1,
                ],
            'files' =>
                (object) [
                    'src/AnotherBadClass.php' =>
                        (object) [
                            'errors' => 1,
                            'warnings' => 1,
                            'fixable' => 1,
                            'messages' =>
                                [
                                    (object) [
                                        'message' => 'A file should declare new symbols (classes, functions, constants, etc.) and cause no other side effects, or it should execute logic with side effects, but should not do both. The first symbol is defined on line 8 and the first side effect is on line 29.',
                                        'source' => 'PSR1.Files.SideEffects.FoundWithSymbols',
                                        'severity' => 5,
                                        'fixable' => false,
                                        'type' => 'warning',
                                        'line' => 1,
                                        'column' => 1,
                                    ],
                                    (object) [
                                        'message' => 'Contiguous blank lines found',
                                        'source' => '..DisallowContiguousNewlines.ContiguousNewlines',
                                        'severity' => 5,
                                        'fixable' => true,
                                        'type' => 'error',
                                        'line' => 14,
                                        'column' => 1,
                                    ],
                                ],
                        ],
                    'src/SomeOtherBadClass.php' =>
                        (object) [
                            'errors' => 1,
                            'warnings' => 0,
                            'fixable' => 0,
                            'messages' =>
                                [
                                    (object) [
                                        'message' => 'Contiguous blank lines found',
                                        'source' => '..DisallowContiguousNewlines.ContiguousNewlines',
                                        'severity' => 5,
                                        'fixable' => false,
                                        'type' => 'error',
                                        'line' => 123,
                                        'column' => 5,
                                    ],
                                ],
                        ],
                ],
        ];
    }
}
