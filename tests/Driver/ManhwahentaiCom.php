<?php

namespace YameteTests\Driver;


class ManhwahentaiCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manhwahentai.com/manhwa/mind-reader/chapter-29/';
        $driver = new \Yamete\Driver\ManhwahentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(472, count($driver->getDownloadables()));
    }
}
