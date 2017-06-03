<?php

namespace TS\PhpcsInstaller;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Composer\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * The actual Composer plugin which takes care of installing PHPCS standards.
 */
final class Plugin implements EventSubscriberInterface, PluginInterface
{
    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var string
     */
    private $vendorDir;

    /**
     * @return Filesystem
     */
    private function getFilesystem()
    {
        if (null === $this->filesystem) {
            $this->filesystem = new Filesystem();
        }

        return $this->filesystem;
    }

    /**
     * Returns the full path to the vendor dir.
     *
     * @return string
     */
    private function getVendorDir()// : string
    {
        if (null === $this->vendorDir) {
            $this->vendorDir = realpath($this->composer->getConfig()->get('vendor-dir'));
        }

        return $this->vendorDir;
    }

    /**
     * Checks whether PHPCS is installed.
     *
     * @return bool
     */
    private function isPhpcsInstalled()// : bool
    {
        // TODO: Maybe replace this with composer calls
        return $this->getFilesystem()->exists($this->getVendorDir() . '/' . Finder::PATH_PHPCS);
    }

    /**
     * @inheritdoc
     */
    public function activate(Composer $composer, IOInterface $io)// : void
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()// : array
    {
        return [
            ScriptEvents::PRE_AUTOLOAD_DUMP => 'onPreAutoloadDump',
        ];
    }

    /**
     * Finds and installs third-party PHPCS standards.
     *
     * @return void
     */
    public function onPreAutoloadDump()// : void
    {
        if (!$this->isPhpcsInstalled()) {
            return;
        }

        $vendorDir = $this->getVendorDir();
        $standardsDir = $vendorDir . '/' . Finder::PATH_STANDARDS;

        $finder = new Finder($vendorDir);
        $fs = $this->getFilesystem();
        $fsOptions = ['copy_on_windows' => true, 'override' => true];

        foreach ($finder->getCodeStandardFolders() as $folder) {
            $targetDir = $standardsDir . '/' . basename($folder);

            $fs->mirror($folder, $targetDir, null, $fsOptions);
        }
    }

    /**
     * Allows injection of a Symfony Filesystem instance for testing purposes.
     *
     * @param Filesystem $filesystem
     * @return void
     */
    public function setFilesystem(Filesystem $filesystem)// : void
    {
        $this->filesystem = $filesystem;
    }
}
