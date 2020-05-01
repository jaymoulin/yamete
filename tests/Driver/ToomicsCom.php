<?php

namespace YameteTests\Driver;


class ToomicsCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://toomics.com/en/webtoon/episode/toon/4698';
        $driver = new \Yamete\Driver\ToomicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(170, count($driver->getDownloadables()));
    }
}
