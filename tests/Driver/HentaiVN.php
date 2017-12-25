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
}
