<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonyLevelSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Symfony34\Rector\Closure\ContainerGetNameToTypeInTestsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->symfonyContainerXml(
        __DIR__.'/var/cache/dev/App_KernelDevDebugContainer.xml'
    );

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,

        SymfonySetList::SYMFONY_64,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
        SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,

        DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,

        SetList::EARLY_RETURN,
        SetList::DEAD_CODE,
    ]);

    $rectorConfig->skip([
        ContainerGetNameToTypeInTestsRector::class => [
            __DIR__ . '/tests/Controller/ControllerAccessTest.php',
        ],
    ]);
};
