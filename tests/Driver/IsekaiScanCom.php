<?php

namespace YameteTests\Driver;


class IsekaiScanCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://isekaiscan.com/manga/tanaka-no-atelier-nenrei-equal-kanojo-inaireki-no-mahoutsukai/';
        $driver = new \Yamete\Driver\IsekaiScanCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(121, count($driver->getDownloadables()));
    }
}
