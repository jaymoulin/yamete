<?php

namespace YameteTests\Driver;


class MangaPornPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.mangaporn.pro/galleries/twintail-butt';
        $driver = new \Yamete\Driver\MangaPornPro();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(4, count($driver->getDownloadables()));
    }
}
