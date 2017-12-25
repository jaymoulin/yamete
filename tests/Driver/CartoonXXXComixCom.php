<?php

namespace YameteTests\Driver;


class CartoonXXXComixCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.cartoonxxxcomix.com/gallery/coop-in-trouble';
        $driver = new \Yamete\Driver\CartoonXXXComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
