<?php

namespace YameteTests\Driver;


class FreeComicOnlineMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://freecomiconline.me/comic/toxic-mother/';
        $driver = new \Yamete\Driver\FreeComicOnlineMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(78, count($driver->getDownloadables()));
    }
}
