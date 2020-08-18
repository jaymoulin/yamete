<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class SexCartoonPicsCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.sexcartoonpics.com/fr/galleries/-kiyokawa-zaidan-kiyokawa-nijiko-sonogo-no-haha-2214';
        $driver = new \Yamete\Driver\SexCartoonPicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(48, count($driver->getDownloadables()));
    }
}
