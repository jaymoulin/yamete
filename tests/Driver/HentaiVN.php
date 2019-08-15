<?php

namespace YameteTests\Driver;


class HentaiVN extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://hentaivn.net/10270-doc-truyen-sense-honyorer-tte-nan-desu-ka.html';
        $driver = new \Yamete\Driver\HentaiVN();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testNoFormatDownload()
    {
        $url = 'https://hentaivn.net/16076-29054-xem-truyen-darrens-adventure-chap-1.html';
        $driver = new \Yamete\Driver\HentaiVN();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
