<?php

namespace YameteTests\Driver;


class HentaiyaroCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaiyaro.com/hentai/offline-game/';
        $driver = new \Yamete\Driver\HentaiyaroCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(25, count($driver->getDownloadables()));
    }
}
