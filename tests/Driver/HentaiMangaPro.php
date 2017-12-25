<?php

namespace YameteTests\Driver;


class HentaiMangaPro extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.hentaimanga.pro/galleries/metalforever-preggo-maya-occult-academy';
        $driver = new \Yamete\Driver\HentaiMangaPro();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(9, count($driver->getDownloadables()));
    }
}
