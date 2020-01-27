<?php

namespace YameteTests\Driver;


class MangaHomeCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.mangahome.com/manga/kodomo_x_otona_no_houteishiki/c001';
        $driver = new \Yamete\Driver\MangaHomeCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(366, count($driver->getDownloadables()));
    }
}
