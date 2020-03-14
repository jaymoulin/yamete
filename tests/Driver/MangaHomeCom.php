<?php

namespace YameteTests\Driver;


class MangaHomeCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.mangahome.com/manga/woooh_rovers';
        $driver = new \Yamete\Driver\MangaHomeCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(94, count($driver->getDownloadables()));
    }
}
