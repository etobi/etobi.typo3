<?php

namespace Etobi\Typo3\Configuration;

use TYPO3\CMS\Core\Utility\ArrayUtility;

class ConfigurationHelper
{
    /**
     * This function transforms given .env variables prefixed by "TYPO3__" into
     * $GLOBALS['TYPO3_CONF_VARS'] settings for AdditionalConfiguration.php.
     */
    public static function loadDotEnv()
    {
        $prefix = 'TYPO3';
        $keySeparator = '__';
        $configuration = [];

        foreach ($_ENV as $name => $value) {
            if (strpos($name, $prefix . $keySeparator) !== 0) {
                continue;
            }
            $configuration = self::setValueByPath(
                $configuration,
                str_replace($keySeparator, '/', substr($name, strlen($prefix . $keySeparator))),
                $value
            );
        }

        ArrayUtility::mergeRecursiveWithOverrule($GLOBALS['TYPO3_CONF_VARS'], $configuration);
    }

    protected static function setValueByPath(array $array, string $path, $value, string $delimiter = '/'): array
    {
        if (empty($path)) {
            throw new \RuntimeException('Path must not be empty. Please set a non empty $prefix!', 1462018404);
        }
        if (!is_string($path)) {
            throw new \RuntimeException('Path must be a string', 1341406402);
        }

        // Extract parts of the path
        $path = str_getcsv($path, $delimiter, escape: '\\');
        // Point to the root of the array
        $pointer = &$array;
        // Find path in given array
        foreach ($path as $segment) {
            // Fail if the part is empty
            if (empty($segment)) {
                throw new \RuntimeException('Invalid path segment specified', 1341406846);
            }
            // Create cell if it doesn't exist
            if (!array_key_exists($segment, $pointer)) {
                $pointer[$segment] = [];
            }
            // Set pointer to new cell
            $pointer = &$pointer[$segment];
        }
        // Set value of target cell
        $pointer = $value;

        return $array;
    }
}
