<?php

namespace PlotBox\PhpcsParse\Phpcs\IdeIntegration;

use PlotBox\PhpcsParse\FileService;
use PlotBox\PhpcsParse\RelativeFile;
use PlotBox\PhpcsParse\Util\StringUtil;
use RuntimeException;

class PhpStorm implements IdeVendor
{
    /**
     * @var FileService
     */
    private $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @inheritDoc
     */
    public function getRelativeFilePath(array $cliArguments)
    {
        $requestPath = $this->getOriginalRequestFilePath($cliArguments);
        $relativePath = $this->toRelativePath($requestPath);

        return new RelativeFile($relativePath);
    }

    /**
     * @inheritDoc
     */
    public function getOriginalRequestFilePath(array $cliArguments)
    {
        $path = null;
        foreach ($cliArguments as $arg) {
            if (!StringUtil::startsWith(trim($arg), '-')) {
                return $arg;
            }
        }

        throw new RuntimeException("Couldn't find file path in arguments");
    }

    /**
     * @param string $path
     * @return string
     */
    private function toRelativePath($path)
    {
        // /tmp/phpcs_temp.tmp9/classes/LoginProviders/PlotBoxLoginProvider.class.php
        $path = preg_replace('|^/[^/]+|', '', $path);
        $path = preg_replace('|^/[^/]+|', '', $path);
        $path = preg_replace('|^/|', '', $path);

        return $path;
    }
}