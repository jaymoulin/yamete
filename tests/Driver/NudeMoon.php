<?php

namespace YameteTests\Driver;


class NudeMoon extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://nude-moon.net/11683-online--kairo-rising-heat.html';
        $driver = new \Yamete\Driver\NudeMoon();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(42, count($driver->getDownloadables()));
    }
}
