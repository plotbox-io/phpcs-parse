<?php

namespace PlotBox\PhpcsParse;

class FileService
{
    /**
     * @param string $path
     * @return bool
     */
    public function fileExists($path)
    {
        return file_exists($path);
    }
}