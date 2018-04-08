<?php

namespace YameteTests\Driver;


class HentaiComic extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://id.hentai-comic.com/image/antyuumosaku-malcorond-oyasuminasai-english-digital/';
        $driver = new \Yamete\Driver\HentaiComic();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(5, count($driver->getDownloadables()));
    }
}
