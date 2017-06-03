<?php

namespace TS\PhpcsInstaller\Tests;

use Composer\Composer;
use Composer\Config;
use Composer\EventDispatcher\EventDispatcher;
use Composer\IO\NullIO;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PluginManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use TS\PhpcsInstaller\Finder;
use TS\PhpcsInstaller\Plugin;

class PluginTest extends TestCase
{
    /**
     * @return array[Composer, NullIO]
     */
    private function createComposer()
    {
        $composer = new Composer();
        $io = new NullIO();
        $config = new Config(true, __DIR__);
        $config->merge([
            'vendor-dir' => __DIR__ . '/vendor',
        ]);

        $composer->setConfig($config);
        $composer->setEventDispatcher(new EventDispatcher($composer, $io));
        $composer->setPluginManager(new PluginManager($io, $composer));

        return [$composer, $io];
    }

    /**
     * @test
     */
    public function instantiation_works()
    {
        $plugin = new Plugin();

        $this->assertInstanceOf(Plugin::class, $plugin);
        $this->assertInstanceOf(PluginInterface::class, $plugin);
    }

    /**
     * @test
     */
    public function plugin_is_loadable()
    {
        list($composer) = $this->createComposer();
        $plugin = new Plugin();

        $composer->getPluginManager()->addPlugin($plugin);

        $this->assertContains($plugin, $composer->getPluginManager()->getPlugins());
    }

    /**
     * @test
     */
    public function does_not_copy_anything_when_phpcs_is_not_installed()
    {
        $fs = $this
            ->getMockBuilder(Filesystem::class)
            ->setMethods(['exists', 'symlink'])
            ->getMock();

        $fs
            ->expects($this->once())
            ->method('exists')
            ->willReturn(false);

        $fs
            ->expects($this->never())
            ->method('symlink');

        list($composer, $io) = $this->createComposer();

        $plugin = new Plugin();
        $plugin->activate($composer, $io);
        $plugin->setFilesystem($fs);

        $plugin->onPreAutoloadDump();
    }

    /**
     * @test
     */
    public function links_phpcs_standards()
    {
        $fs = new Filesystem();
        $fs->mkdir([
            __DIR__ . '/vendor/' . Finder::PATH_STANDARDS,
            __DIR__ . '/vendor/test-standard'
        ]);
        $fs->touch(__DIR__ . '/vendor/test-standard/ruleset.xml');
        file_put_contents(__DIR__ . '/vendor/test-standard/ruleset.xml', '<ruleset');

        list($composer, $io) = $this->createComposer();

        $plugin = new Plugin();
        $plugin->activate($composer, $io);

        $plugin->onPreAutoloadDump();

        $this->assertTrue($fs->exists(__DIR__ . '/vendor/' . Finder::PATH_STANDARDS . '/test-standard'));

        $fs->remove(__DIR__ . '/vendor');
    }
}
