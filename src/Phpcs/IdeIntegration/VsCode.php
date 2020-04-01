<?php

namespace PlotBox\PhpcsParse\Phpcs\IdeIntegration;

use PlotBox\PhpcsParse\FileService;
use PlotBox\PhpcsParse\RelativeFile;
use RuntimeException;

class VsCode implements IdeVendor
{
    /** @var string */
    private $projectDirectory;
    /** @var FileService */
    private $fileService;

    /**
     * @param string $projectDirectory
     * @param FileService $fileService
     */
    public function __construct($projectDirectory, FileService $fileService)
    {
        $this->projectDirectory = $projectDirectory;
        $this->fileService = $fileService;
    }

    /**
     * @inheritDoc
     */
    public function getRelativeFilePath(array $cliArguments)
    {
        $originalPath = $this->getOriginalRequestFilePath($cliArguments);

        // Test if file exists in project dir. Iterate (remove top level dirs) until true
        // /home/richard/Development/PlotBox/plotbox-app/classes/Domain/DeaExport/ExportExecuter.php
        $resultPath = null;
        $path = $originalPath;
        while (true) {
            $testPath = $this->projectDirectory.$path;
            if ($this->fileService->fileExists($testPath)) {
                $resultPath = $testPath;
                break;
            }
            $prevPath = $path;
            $path = preg_replace('|^/[^/]+|', '', $path);
            if ($path === '' || $prevPath === $path) {
                throw new RuntimeException('Unable to determine original file path');
            }
        }
        $resultPath = str_replace($this->projectDirectory . '/', '', $resultPath);

        return new RelativeFile($resultPath);
    }

    /**
     * @inheritDoc
     */
    public function getOriginalRequestFilePath(array $cliArguments)
    {
        // --stdin-path=/home/richard/Development/PlotBox/plotbox-app/classes/Domain/DeaExport/ExportExecuter.php

        $path = null;
        foreach ($cliArguments as $arg) {
            if (strpos($arg, '--stdin-path') !== false) {
                $parts = explode('=', $arg);
                $path = $parts[1];
                break;
            }
        }

        return $path;
    }
}