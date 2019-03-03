<?php

namespace YameteTests\Driver;


class CartoonPornEu extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://cartoonporn.eu/3d/la-ruptura-del-sol-la-mascota-del-rey/';
        $driver = new \Yamete\Driver\CartoonPornEu();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
    }
}
