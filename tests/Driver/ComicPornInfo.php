<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ComicPornInfo extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://comicporn.info/content/an-incredible-story/index.html';
        $driver = new \Yamete\Driver\ComicPornInfo();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(39, count($driver->getDownloadables()));
    }
}
