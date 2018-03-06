<?php

namespace YameteTests\Driver;


class HentaiVN extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://hentaivn.net/10270-doc-truyen-sense-honyorer-tte-nan-desu-ka.html';
        $driver = new \Yamete\Driver\HentaiVN();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(27, count($driver->getDownloadables()));
    }

    public function testNoFormatDownload()
    {
        $url = 'https://hentaivn.net/1934-4365-xem-truyen-magic-seven-the-idolmaster-oneshot.html';
        $driver = new \Yamete\Driver\HentaiVN();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(26, count($driver->getDownloadables()));
    }
}
