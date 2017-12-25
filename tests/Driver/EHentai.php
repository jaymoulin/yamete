<?php

namespace YameteTests\Driver;


class EHentai extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://e-hentai.org/g/1157082/55d486759e/';
        $driver = new \Yamete\Driver\EHentai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(28, count($driver->getDownloadables()));
    }
}
