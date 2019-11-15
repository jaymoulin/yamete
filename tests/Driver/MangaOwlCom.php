<?php

namespace YameteTests\Driver;


class MangaOwlCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://mangaowl.com/reader/55790/729906';
        $driver = new \Yamete\Driver\MangaOwlCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(14, count($driver->getDownloadables()));
    }
}
