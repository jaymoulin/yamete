<?php

namespace YameteTests\Driver;


class WakamicsCom extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://wakamics.com/manga/my-four-masters/';
        $driver = new \Yamete\Driver\WakamicsCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(224, count($driver->getDownloadables()));
    }
}
