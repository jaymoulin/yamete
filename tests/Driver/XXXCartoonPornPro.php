<?php

namespace YameteTests\Driver;


class XXXCartoonPornPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.xxxcartoonporn.pro/images/los-simpsons-viejas-costumbres-6-spanish-accoutrement-2';
        $driver = new \Yamete\Driver\XXXCartoonPornPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
