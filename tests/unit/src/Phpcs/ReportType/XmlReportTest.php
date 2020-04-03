<?php

namespace PlotBox\PhpcsParse\Git\Phpcs\ReportType;

use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface as Mock;
use PlotBox\PhpcsParse\Phpcs\ReportType\ReportAggregator;
use PlotBox\PhpcsParse\Phpcs\ReportType\XmlReport;
use stdClass;

class XmlReportTest extends MockeryTestCase
{
    /** @var XmlReport */
    private $xmlReport;

    /**
     * @var ReportAggregator|Mock
     */
    private $reportAggregator;

    public function setUp()
    {
        $this->reportAggregator = m::mock(ReportAggregator::class);
        $this->xmlReport = new XmlReport($this->reportAggregator);
    }

    public function testToString()
    {
        $mockIssues = [];

        $this->reportAggregator
            ->shouldReceive('getReportData')
            ->andReturn($this->getAggregatedReportData());

        $result = $this->xmlReport->toString($mockIssues);

        $this->assertEquals($this->getExpectedResult(), $result);
    }

    /**
     * @return stdClass
     */
    private function getAggregatedReportData()
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
                    '/app/tests/integration/fixtures/phpcs-shim-fixture/src/AnotherBadClass.php' =>
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
                                        'type' => 'WARNING',
                                        'line' => 1,
                                        'column' => 1,
                                    ],
                                    (object) [
                                        'message' => 'Contiguous blank lines found',
                                        'source' => '..DisallowContiguousNewlines.ContiguousNewlines',
                                        'severity' => 5,
                                        'fixable' => true,
                                        'type' => 'ERROR',
                                        'line' => 14,
                                        'column' => 1,
                                    ],
                                ],
                        ],
                    '/app/tests/integration/fixtures/phpcs-shim-fixture/src/SomeOtherBadClass.php' =>
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
                                        'type' => 'ERROR',
                                        'line' => 123,
                                        'column' => 5,
                                    ],
                                ],
                        ],
                ],
        ];
    }

    /**
     * @return string
     */
    private function getExpectedResult()
    {
        return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<phpcs version="3.4.2">
  <file name="/app/tests/integration/fixtures/phpcs-shim-fixture/src/AnotherBadClass.php" errors="1" warnings="1" fixable="1">
    <warning line="1" column="1" source="PSR1.Files.SideEffects.FoundWithSymbols" severity="5" fixable="0">A file should declare new symbols (classes, functions, constants, etc.) and cause no other side effects, or it should execute logic with side effects, but should not do both. The first symbol is defined on line 8 and the first side effect is on line 29.</warning>
    <error line="14" column="1" source="..DisallowContiguousNewlines.ContiguousNewlines" severity="5" fixable="1">Contiguous blank lines found</error>
  </file>
  <file name="/app/tests/integration/fixtures/phpcs-shim-fixture/src/SomeOtherBadClass.php" errors="1" warnings="0" fixable="0">
    <error line="123" column="5" source="..DisallowContiguousNewlines.ContiguousNewlines" severity="5" fixable="0">Contiguous blank lines found</error>
  </file>
</phpcs>

XML;
    }
}
