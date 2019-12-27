<?php

namespace YameteTests\Driver;


class HentaiKai extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaikai.com/world-cup-girls/';
        $driver = new \Yamete\Driver\HentaiKai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
    }
}
