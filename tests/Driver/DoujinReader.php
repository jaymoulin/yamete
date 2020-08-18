<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class DoujinReader extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://doujinreader.com/doujin/hentai/manga/comic/14-kaiten-ass-manga-daioh';
        $driver = new \Yamete\Driver\DoujinReader();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(35, count($driver->getDownloadables()));
    }
}
