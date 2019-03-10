<?php

namespace YameteTests\Driver;


class NaughtyHentaiCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.naughtyhentai.com/manga/lets-warm-up-together-1/';
        $driver = new \Yamete\Driver\NaughtyHentaiCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(20, count($driver->getDownloadables()));
    }
}
