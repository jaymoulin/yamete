<?php

namespace YameteTests\Driver;


class MyMangaComicsCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mymangacomics.com/gallery/thumbnails/9779';
        $driver = new \Yamete\Driver\MyMangaComicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
