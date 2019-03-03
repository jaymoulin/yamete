<?php

namespace YameteTests\Driver;


class NudeMoon extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://nude-moon.me/10883-online--karfagen-majuu-jouka-shoujo-utea-3.html';
        $driver = new \Yamete\Driver\NudeMoon();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
