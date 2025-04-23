<?php

declare(strict_types=1);

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use TYPO3\CodingStandards\CsFixerConfig;

$config = CsFixerConfig::create();
$config->setParallelConfig(ParallelConfigFactory::detect());
$config->setCacheFile('.php-cs-fixer.cache');
$config->getFinder()
    ->in([
        __DIR__ . '/../../../packages',
        __DIR__ . '/../../../config',
    ])
    ->ignoreVCSIgnored(true);

return $config;
