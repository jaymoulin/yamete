<?php

namespace YameteTests\Driver;


class LustAndSinCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.lustandsin.com/2015/shinngeki_vol03/image0019.html';
        $driver = new \Yamete\Driver\LustAndSinCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(19, count($driver->getDownloadables()));
    }
}
