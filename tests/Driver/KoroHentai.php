<?php

namespace YameteTests\Driver;


class KoroHentai extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://korohentai.com/kearuda-no-yarashii-hon-2.html';
        $driver = new \Yamete\Driver\KoroHentai();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
