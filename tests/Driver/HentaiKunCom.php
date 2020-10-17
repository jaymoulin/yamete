<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class HentaiKunCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaikun.com/manga/ore-no-imouto-ga-konna-ni-kawaii-wake-ga-nai/a-book-where-kuroneko-and-i-get-naughty-9194/';
        $driver = new \Yamete\Driver\HentaiKunCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
