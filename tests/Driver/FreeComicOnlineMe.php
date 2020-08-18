<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class FreeComicOnlineMe extends TestCase
{
    /**
     * @throws GuzzleException
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
