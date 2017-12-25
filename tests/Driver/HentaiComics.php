<?php

namespace YameteTests\Driver;


class HentaiComics extends \PHPUnit\Framework\TestCase
{
    public function testDownload()
    {
        $url = 'https://hentai-comics.org/gallery/74362/jukai-ni-shizunda-boukenshatachi.html';
        $driver = new \Yamete\Driver\HentaiComics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(31, count($driver->getDownloadables()));
    }
}
