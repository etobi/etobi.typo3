<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\CodeQuality\General\ConvertImplicitVariablesToExplicitGlobalsRector;
use Ssch\TYPO3Rector\CodeQuality\General\ExtEmConfRector;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\TYPO312\v0\UseServerRequestInsteadOfGeneralUtilityPostRector;
use Ssch\TYPO3Rector\TYPO312\v1\TemplateServiceToServerRequestFrontendTypoScriptAttributeRector;
use Ssch\TYPO3Rector\TYPO312\v3\MigrateGeneralUtilityGPRector;
use Ssch\TYPO3Rector\TYPO312\v4\MigrateConfigurationManagerGetContentObjectRector;
use Ssch\TYPO3Rector\TYPO312\v4\UseServerRequestInsteadOfGeneralUtilityGetRector;

return RectorConfig::configure()
    ->withParallel(300, 1)
    ->withCache(
        cacheClass: FileCacheStorage::class,
        cacheDirectory: '.rector'
    )
    ->withConfiguredRule(ExtEmConfRector::class, [
        ExtEmConfRector::ADDITIONAL_VALUES_TO_BE_REMOVED => [],
    ])
    ->withPaths([
        __DIR__ . '/../../../packages/',
    ])
    ->withPhpSets(php83: true)
    ->withSets([
        Typo3LevelSetList::UP_TO_TYPO3_12
    ])
    ->withPHPStanConfigs([
        Typo3Option::PHPSTAN_FOR_RECTOR_PATH,
    ])
    ->withPhpVersion(PhpVersion::PHP_83)
    ->withImportNames(importShortClasses: false)
    ->withRules([
        ConvertImplicitVariablesToExplicitGlobalsRector::class,
    ])
    ->withSkip([
        AddOverrideAttributeToOverriddenMethodsRector::class,
        UseServerRequestInsteadOfGeneralUtilityPostRector::class,
        TemplateServiceToServerRequestFrontendTypoScriptAttributeRector::class,
        MigrateGeneralUtilityGPRector::class,
        MigrateConfigurationManagerGetContentObjectRector::class,
        UseServerRequestInsteadOfGeneralUtilityGetRector::class,
        ReadOnlyPropertyRector::class,
        NullToStrictStringFuncCallArgRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class,

        // @see https://github.com/sabbelasichon/typo3-rector/issues/2536
        __DIR__ . '/../**/Configuration/ExtensionBuilder/*',
        __DIR__ . '/../**/vendor/*',

        NameImportingPostRector::class => [
            'ext_localconf.php',
            'ext_tables.php',
            'ClassAliasMap.php',
            'Configuration/*.php',
            'Configuration/**/*.php',
        ],
    ])
;
