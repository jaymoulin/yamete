<?php

namespace YameteTests\Driver;


class HotSexHentaiCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://hotsexhentai.com/gallery/hentai-zone-38/index.html';
        $driver = new \Yamete\Driver\HotSexHentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
