<?php

namespace YameteTests\Driver;


use PHPUnit\Framework\TestCase;

class EightMuses extends TestCase
{
    public function testDownload()
    {
        $url = 'https://www.8muses.com/comix/album/JAB-Comics/A-Model-Life/Issue-1';
        $driver = new \Yamete\Driver\EightMuses();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }

    public function testDownloadSeries()
    {
        $url = 'https://www.8muses.com/comix/album/JAB-Comics/A-Model-Life';
        $driver = new \Yamete\Driver\EightMuses();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(65, count($driver->getDownloadables()));
    }
}
