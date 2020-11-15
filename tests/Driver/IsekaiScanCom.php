<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class IsekaiScanCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://isekaiscan.com/manga/tanaka-no-atelier-nenrei-equal-kanojo-inaireki-no-mahoutsukai/';
        $driver = new \Yamete\Driver\IsekaiScanCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(353, count($driver->getDownloadables()));
    }
}
