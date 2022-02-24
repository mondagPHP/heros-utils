<?php
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Test\File;

use Monda\Utils\File\FileUtil;
use PHPUnit\Framework\TestCase;

class FileUtilTest extends TestCase
{
    public function testRemoveDirs()
    {
        FileUtil::makeFileDirs(__DIR__ . '/a/a1/a2');
        FileUtil::removeDirs(__DIR__ . '/a');
        $this->assertDirectoryNotExists(__DIR__ . '/a/a1/a2');
    }

    public function testMakeFileDirs()
    {
        FileUtil::makeFileDirs(__DIR__ . '/a/a1/a2');
        $this->assertDirectoryExists(__DIR__ . '/a/a1/a2');
    }

    public function testDirTraversal()
    {
        $dirTraversal = FileUtil::dirTraversal(__DIR__);
        $this->assertEquals('FileUtilTest.php', $dirTraversal[0]);
    }

    public function testGlobScandir()
    {
        $res1 = FileUtil::glob_scandir(__DIR__);
        $this->assertEquals([
            __DIR__ . '/A.txt',
            __DIR__ . '/B',
            __DIR__ . '/FileUtilTest.php'], $res1);

        $res2 = FileUtil::glob_scandir(__DIR__, '/.*\.txt/');
        $this->assertEquals([__DIR__ . '/A.txt'], $res2);

        $res3 = FileUtil::glob_scandir(__DIR__, '/.*\.php/');
        $this->assertEquals([__DIR__ . '/FileUtilTest.php'], $res3);
    }
}
