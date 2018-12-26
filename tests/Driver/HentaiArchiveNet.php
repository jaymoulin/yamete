<?php

namespace YameteTests\Driver;


class HentaiArchiveNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.hentai-archive.net/ho-bisogno-di-un-po-di-latte/';
        $driver = new \Yamete\Driver\HentaiArchiveNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(28, count($driver->getDownloadables()));
    }
}
