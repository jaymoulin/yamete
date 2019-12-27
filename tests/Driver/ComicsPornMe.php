<?php

namespace YameteTests\Driver;


class ComicsPornMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.comicsporn.me/videos/the-broken-mask#&gid=1&pid=1';
        $driver = new \Yamete\Driver\ComicsPornMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
