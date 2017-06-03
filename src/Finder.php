<?php

namespace TS\PhpcsInstaller;

use SplFileInfo;
use Symfony\Component\Finder\Finder as BaseFinder;

/**
 * Finds PHPCS standards folders.
 */
final class Finder extends BaseFinder
{
    const PATH_PHPCS = 'squizlabs/php_codesniffer';
    const PATH_STANDARDS = 'squizlabs/php_codesniffer/CodeSniffer/Standards';

    /**
     * @inheritdoc
     *
     * @param string $vendorDir
     */
    public function __construct($vendorDir)
    {
        parent::__construct();

        $this
            ->files()
            ->in($vendorDir)
            ->name('ruleset.xml')
            ->contains('<ruleset')
            ->notPath(self::PATH_PHPCS);
    }

    /**
     * Returns the folders containing PHPCS rule sets.
     *
     * @return string[]
     */
    public function getCodeStandardFolders()// : array
    {
        return array_map(
            function (SplFileInfo $file) {
                return dirname($file->getRealPath());
            },
            iterator_to_array($this->getIterator(), false)
        );
    }
}
