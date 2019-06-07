<?php

namespace YameteTests\Driver;


class XXXHentaiComixCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.xxxhentaicomix.com/gallery/distressed-damsels-wonder-woman.html';
        $driver = new \Yamete\Driver\XXXHentaiComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
