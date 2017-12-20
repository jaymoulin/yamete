<?php

namespace YameteTests\Driver;


class HentaiVN extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://hentaivn.net/9455-doc-truyen-seri-hatsune-miku.html';
        $driver = new \Yamete\Driver\HentaiVN();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
