<?php

namespace YameteTests\Driver;


class ManhwaHentaiMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manhwahentai.me/webtoon/new-teacher-in-town-webtoon-manhwa-hentai/';
        $driver = new \Yamete\Driver\ManhwaHentaiMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(437, count($driver->getDownloadables()));
    }
}
