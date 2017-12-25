<?php

namespace YameteTests\Driver;


class CartoonSexImagesCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.cartoonseximages.com/pictures/dear-with-large-tits-and-aliens-with-huge-dicks-at-participate-3d-porn';
        $driver = new \Yamete\Driver\CartoonSexImagesCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(6, count($driver->getDownloadables()));
    }
}
