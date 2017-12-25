<?php

namespace YameteTests\Driver;


class HentaiFantasy extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'http://www.hentaifantasy.it/series/una-stanza-senza-shiori-invito/';
        $driver = new \Yamete\Driver\HentaiFantasy();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(31, count($driver->getDownloadables()));
    }
}
