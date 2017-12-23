<?php

namespace YameteTests\Driver;


class HMangaSearcher extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.hmangasearcher.com/c/Teach%20Me%20-%20Very%20Short/1';
        $driver = new \Yamete\Driver\HMangaSearcher();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(8, count($driver->getDownloadables()));
    }

    public function testDownloadChapter()
    {
        $url = 'http://www.hmangasearcher.com/m/Lewd%20Dreams';
        $driver = new \Yamete\Driver\HMangaSearcher();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(33, count($driver->getDownloadables()));
    }
}
