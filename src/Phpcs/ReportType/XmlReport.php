<?php

namespace PlotBox\PhpcsParse\Phpcs\ReportType;

use DOMDocument;

class XmlReport implements Report
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
//        <?xml version="1.0" encoding="UTF-8"? >
//        <phpcs version="3.4.2">
//            <file name="/app/tests/integration/fixtures/phpcs-shim-fixture/src/AnotherBadClass.php" errors="34" warnings="2" fixable="17">
//                <warning line="1" column="1" source="PSR1.Files.SideEffects.FoundWithSymbols" severity="5" fixable="0">A file should declare new symbols (classes, functions, constants, etc.) and cause no other side effects, or it should execute logic with side effects, but should not do both. The first symbol is defined on line 8 and the first side effect is on line 29.</warning>
//                <error line="3" column="1" source="PSR2.Namespaces.NamespaceDeclaration.BlankLineAfter" severity="5" fixable="1">There must be one blank line after the namespace declaration</error>
//                <warning line="21" column="87" source="Generic.CodeAnalysis.EmptyPHPStatement.SemicolonWithoutCodeDetected" severity="5" fixable="1">Empty PHP statement detected: superfluous semi-colon.</warning>
//            </file>
//            <file name="/app/tests/integration/fixtures/phpcs-shim-fixture/src/BadlyFormatted.php" errors="27" warnings="1" fixable="16">
//                <error line="3" column="1" source="..DisallowContiguousNewlines.ContiguousNewlines" severity="5" fixable="0">Contiguous blank lines found</error>
//                <warning line="18" column="89" source="Generic.CodeAnalysis.EmptyPHPStatement.SemicolonWithoutCodeDetected" severity="5" fixable="1">Empty PHP statement detected: superfluous semi-colon.</warning>
//            </file>
//        </phpcs>

        $reportData = $this->reportAggregator->getReportData($issues);

        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->xmlVersion = '1.0';
        $dom->formatOutput = true;

        $root = $dom->createElement('phpcs');
        $root->setAttribute('version', '3.4.2');
        $dom->appendChild($root);
        foreach ($reportData->files as $fileName => $file) {
            $fileElement = $dom->createElement('file');
            $fileElement->setAttribute('name', (string) $fileName);
            $fileElement->setAttribute('errors', (string) $file->errors);
            $fileElement->setAttribute('warnings', (string) $file->warnings);
            $fileElement->setAttribute('fixable', (string) (int) $file->fixable);
            $root->appendChild($fileElement);

            foreach ($file->messages as $error) {
                $type = strtolower($error->type);
                $errorElement = $dom->createElement($type);
                $errorElement->nodeValue = $error->message;
                $errorElement->setAttribute('line', (string) $error->line);
                $errorElement->setAttribute('column', (string) $error->column);
                $errorElement->setAttribute('source', $error->source);
                $errorElement->setAttribute('severity', (string) $error->severity);
                $errorElement->setAttribute('fixable', (string) (int) $error->fixable);
                $fileElement->appendChild($errorElement);
            }
        }

        return $dom->saveXML();
    }

    /**
     * @inheritDoc
     */
    public function fromString($content)
    {
        throw new NotSupportedException();
    }
}