<?php

namespace YameteTests\Driver;


class FanFoxNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://fanfox.net/manga/ten_count/';
        $driver = new \Yamete\Driver\FanFoxNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(1198, count($driver->getDownloadables()));
    }
}
