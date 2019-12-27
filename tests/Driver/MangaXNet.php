<?php

namespace YameteTests\Driver;


class MangaXNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.mangax.net/c/Henai/1';
        $driver = new \Yamete\Driver\MangaXNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(33, count($driver->getDownloadables()));
    }
}
