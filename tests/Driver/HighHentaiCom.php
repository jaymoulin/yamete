<?php

namespace YameteTests\Driver;


class HighHentaiCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.high-hentai.com/hentai2016/If_Story_Hen_[Lilith]/image000.php';
        $driver = new \Yamete\Driver\HighHentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(144, count($driver->getDownloadables()));
    }
}
