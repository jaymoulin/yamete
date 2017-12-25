<?php

namespace YameteTests\Driver;


class FullMetalHentaiCom extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.fullmetal-hentai.com/hentai2016/Bokura_no_Kankin_Shinsatsushitsu_[Shimai_Torikaekko_Choukyou]/index006.php';
        $driver = new \Yamete\Driver\FullMetalHentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(80, count($driver->getDownloadables()));
    }
}
