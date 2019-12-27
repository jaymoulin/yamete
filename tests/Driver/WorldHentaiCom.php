<?php

namespace YameteTests\Driver;


class WorldHentaiCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://world-hentai.com/the-it-ch/';
        $driver = new \Yamete\Driver\WorldHentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(21, count($driver->getDownloadables()));
    }
}
