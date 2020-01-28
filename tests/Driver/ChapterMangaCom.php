<?php

namespace YameteTests\Driver;


class ChapterMangaCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://chaptermanga.com/read-manga-mr-45-years-old-beast-manga';
        $driver = new \Yamete\Driver\ChapterMangaCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(134, count($driver->getDownloadables()));
    }
}
