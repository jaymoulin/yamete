<?php

namespace YameteTests\Driver;


class CartoonSexComixCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.cartoonsexcomix.com/gallery/milftoon-grand-prize';
        $driver = new \Yamete\Driver\CartoonSexComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
