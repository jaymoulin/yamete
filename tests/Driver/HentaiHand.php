<?php

namespace YameteTests\Driver;


class HentaiHand extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaihand.com/porn-comics/cumming-inside-mommys-hole-vol-2-hentai/';
        $driver = new \Yamete\Driver\HentaiHand();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(129, count($driver->getDownloadables()));
    }
}
