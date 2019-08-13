<?php

namespace YameteTests\Driver;


class MyMangaComics extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://myhentaigallery.com/gallery/thumbnails/9753';
        $driver = new \Yamete\Driver\MyHentaiGallery();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(11, count($driver->getDownloadables()));
    }
}
