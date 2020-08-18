<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class FreewebtooncoinsCom extends TestCase
{
    /**
     * @throws GuzzleException
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
