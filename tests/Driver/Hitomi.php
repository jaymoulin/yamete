<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class Hitomi extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hitomi.la/galleries/1084281.html';
        $driver = new \Yamete\Driver\Hitomi();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadThird()
    {
        $url = 'https://hitomi.la/galleries/1495922.html';
        $driver = new \Yamete\Driver\Hitomi();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }

    /**
     * @throws GuzzleException
     */
    public function testDownloadEmojiPlusNewUrl()
    {
        $url = 'https://hitomi.la/doujinshi/producer-to-otomarishimasu%E2%99%A5--decensored--english-1550440.html';
        $driver = new \Yamete\Driver\Hitomi();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(7, count($driver->getDownloadables()));
    }
}
