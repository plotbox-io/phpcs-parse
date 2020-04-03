<?php

namespace PlotBox\PhpcsParse;

class CodeIssue
{
    /** @var string */
    private $file;
    /** @var int */
    private $line;
    /** @var int */
    private $column;
    /** @var string */
    private $source;
    /** @var string */
    private $message;
    /** @var string */
    private $type;
    /** @var int */
    private $severity;
    /** @var bool */
    private $fixable;

    /**
     * @param string $file
     * @param int $line
     * @param int $column
     * @param string $source
     * @param string $message
     * @param string $type
     * @param int $severity
     * @param bool $fixable
     */
    public function __construct(
        $file,
        $line,
        $column,
        $source,
        $message,
        $type,
        $severity,
        $fixable
    ) {
        $this->file = $file;
        $this->line = $line;
        $this->column = $column;
        $this->source = $source;
        $this->message = $message;
        $this->type = $type;
        $this->severity = $severity;
        $this->fixable = $fixable;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param $filePath
     */
    public function changeFilePath($filePath)
    {
        $this->file = $filePath;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * @return bool
     */
    public function isFixable()
    {
        return $this->fixable;
    }
}
