<?php

namespace TS\PhpcsInstaller\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use TS\PhpcsInstaller\Finder;

class FinderTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $fs = new Filesystem();
        $fs->mkdir(__DIR__ . '/test');
        $fs->touch(__DIR__ . '/test/ruleset.xml');
        file_put_contents(__DIR__ . '/test/ruleset.xml', '<ruleset');
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        $fs = new Filesystem();
        $fs->remove(__DIR__ . '/test');
    }

    /**
     * @test
     */
    public function instantiation_works()
    {
        $finder = new Finder(__DIR__);

        $this->assertInstanceOf(Finder::class, $finder);
    }

    /**
     * @test
     */
    public function can_find_rulesets()
    {
        $finder = new Finder(__DIR__);

        /** @var \SplFileInfo[] $rulesets */
        $rulesets = iterator_to_array($finder->getIterator(), false);

        $this->assertCount(1, $rulesets);
        $this->assertSame(__DIR__ . '/test/ruleset.xml', $rulesets[0]->getRealPath());
    }

    /**
     * @test
     */
    public function can_find_code_standard_folders()
    {
        $finder = new Finder(__DIR__);
        $folders = $finder->getCodeStandardFolders();

        $this->assertCount(1, $folders);
        $this->assertSame(__DIR__ . '/test', $folders[0]);
    }
}
