<?php

namespace YameteTests\Driver;


class CartoonPornPics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.cartoonporn.pics/fr/galleries/ben-10-bro-sis';
        $driver = new \Yamete\Driver\CartoonPornPics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
