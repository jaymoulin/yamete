<?php

namespace YameteTests\Driver;


class BebopHentaiCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.bebop-hentai.com/hentai2017/touhou_for-24-hours/image001.php';
        $driver = new \Yamete\Driver\BebopHentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(37, count($driver->getDownloadables()));
    }
}
