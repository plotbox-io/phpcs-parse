<?php

namespace PlotBox\PhpcsParse\Phpcs\IdeIntegration;

use PlotBox\PhpcsParse\RelativeFile;

interface IdeVendor
{
    /**
     * @param string[] $cliArguments
     * @return RelativeFile
     */
    public function getRelativeFilePath(array $cliArguments);

    /**
     * @param string[] $cliArguments
     * @return string
     */
    public function getOriginalRequestFilePath(array $cliArguments);
}