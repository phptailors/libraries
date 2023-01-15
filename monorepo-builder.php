<?php

declare(strict_types=1);

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (MBConfig $mbConfig): void {
    $mbConfig->packageDirectories([
        __dir__ . '/packages'
    ]);

    $mbConfig->packageDirectoriesExcludes([
        'vendor-bin'
    ]);

    // for "merge" command
    $mbConfig->dataToAppend([
        ComposerJsonSection::REQUIRE_DEV => [
            'bamarni/composer-bin-plugin' => '^1.8'
        ],
        ComposerJsonSection::AUTOLOAD_DEV => [
            'psr-4' => [
                'Tailors\\Docs\\Behat\\' => 'docs/sphinx/behat/',
            ],
        ],
        ComposerJsonSection::EXTRA => [
            'bamarni-bin' => [
                'bin-links' => false,
                'target-directory' => 'vendor-bin',
                'forward-command' => false,
            ],
        ],
        // not handled by monorepo-builder, just for reference
        ComposerJsonSection::CONFIG => [
            "allow-plugins" => [
                "bamarni/composer-bin-plugin" => true,
            ]
        ],
    ]);
};
