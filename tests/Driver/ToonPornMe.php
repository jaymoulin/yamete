<?php

namespace YameteTests\Driver;


class ToonPornMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://toonporn.me/content/riviera-moon-goddess/index.html';
        $driver = new \Yamete\Driver\ToonPornMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(49, count($driver->getDownloadables()));
    }
}
