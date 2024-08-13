<?php

namespace EmailSender\Tests\Infrastructure;

use EmailSender\Infrastructure\Shared\CurrentWorkDirPath;
use PHPUnit\Framework\TestCase;

class CurrentWorkDirPathTest extends TestCase
{
    public function testGetFullPath(): void
    {
        $currentDir = dirname(__DIR__, 4);
        $path = CurrentWorkDirPath::getPath();
        $this->assertEquals($currentDir, $path);
    }
}
