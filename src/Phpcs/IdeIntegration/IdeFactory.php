<?php

namespace PlotBox\PhpcsParse\Phpcs\IdeIntegration;

use RuntimeException;

class IdeFactory
{
    /** @var VsCode */
    private $vsCode;
    /** @var PhpStorm */
    private $phpStorm;

    /**
     * @param VsCode $vsCode
     * @param PhpStorm $phpStorm
     */
    public function __construct($vsCode, $phpStorm)
    {
        $this->vsCode = $vsCode;
        $this->phpStorm = $phpStorm;
    }

    /**
     * @param string[] $args
     * @return IdeVendor
     */
    public function makeFromArgs(array $args)
    {
        // Get argument keys
        $allKeys = [];
        foreach ($args as $arg) {
            if ($this->isKeyValueArg($arg)) {
                $allKeys[] = $this->getKey($arg);
            }
        }

        // VSCode (Uses JSON, passes text via stdin)
        // --report=json -q --encoding=UTF-8 --error-severity=5 --warning-severity=5 --stdin-path=/home/richard/Development/PlotBox/plotbox-app/classes/Domain/DeaExport/ExportExecuter.php -
        if (in_array('--report=json', $args)) {
            return $this->vsCode;
        }

        // PHPStorm (uses XML, creates a temp file)
        // /tmp/phpcs_temp.tmp9/classes/LoginProviders/PlotBoxLoginProvider.class.php --encoding=utf-8 --report=xml --extensions=php,js,css,inc
        if(in_array('--report=xml', $args)){
            return $this->phpStorm;
        }
        foreach ($args as $arg) {
            if (preg_match('|^/tmp/|', $arg)) {
                return $this->phpStorm;
            }
        }

        throw new RuntimeException("Unable to determine IDE from args:\n\n".json_encode($args));
    }

    /**
     * @param string $arg
     * @return bool
     */
    private function isKeyValueArg($arg)
    {
        return strpos($arg, '--') !== false;
    }

    /**
     * @param string $arg
     * @return string
     */
    private function getKey($arg)
    {
        $parts = explode('=', $arg);

        return $parts[0];
    }
}