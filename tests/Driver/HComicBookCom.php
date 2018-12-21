<?php

namespace YameteTests\Driver;


class HComicBookCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://www.hcomicbook.com/hentai_doujin/41962/';
        $driver = new \Yamete\Driver\HComicBookCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(22, count($driver->getDownloadables()));
    }
}
