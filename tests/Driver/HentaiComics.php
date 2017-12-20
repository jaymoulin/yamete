<?php

namespace YameteTests\Driver;


class HentaiComics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://hentai-comics.org/gallery/74362/jukai-ni-shizunda-boukenshatachi.html';
        $driver = new \Yamete\Driver\HentaiComics();
        $driver->setUrl($url);
        $this->assertNotFalse($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
