<?php

namespace YameteTests\Driver;


class MangairoCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://m.mangairo.com/story-ii50166/chapter-1';
        $driver = new \Yamete\Driver\MangairoCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(31, count($driver->getDownloadables()));
    }
}
