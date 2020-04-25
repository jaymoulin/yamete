<?php

namespace YameteTests\Driver;


class HentaiItaNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentai-ita.net/tette-piu-grandi/';
        $driver = new \Yamete\Driver\HentaiItaNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }
}
