<?php

namespace YameteTests\Driver;


class KingComixCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://kingcomix.com/jukumitsuki-intouden-maki-no-ichi/';
        $driver = new \Yamete\Driver\KingComixCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
    }
}
