<?php

namespace YameteTests\Driver;


class FreewebtooncoinsCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://freewebtooncoins.com/webtoon/hot-special-agent-k/';
        $driver = new \Yamete\Driver\FreewebtooncoinsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(70, count($driver->getDownloadables()));
    }
}
