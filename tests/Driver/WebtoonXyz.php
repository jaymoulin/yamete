<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class WebtoonXyz extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.webtoon.xyz/read/dumped/';
        $driver = new \Yamete\Driver\WebtoonXyz();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1376, count($driver->getDownloadables()));
    }
}
