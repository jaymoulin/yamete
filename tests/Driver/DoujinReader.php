<?php

namespace YameteTests\Driver;


class DoujinReader extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
