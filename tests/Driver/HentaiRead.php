<?php

namespace YameteTests\Driver;


class HentaiRead extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentairead.com/hentai/seiki-kuubo-no-kantsuu-jijou-kai/';
        $driver = new \Yamete\Driver\HentaiRead();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
