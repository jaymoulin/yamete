<?php

namespace YameteTests\Driver;


class Hentai24h extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentai24h.org/mom-know-best/chap-2.html';
        $driver = new \Yamete\Driver\Hentai24h();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(25, count($driver->getDownloadables()));
    }
}
