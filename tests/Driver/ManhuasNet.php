<?php

namespace YameteTests\Driver;


class ManhuasNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://manhuas.net/manhua/urban-leveling-manhua/';
        $driver = new \Yamete\Driver\ManhuasNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(82, count($driver->getDownloadables()));
    }
}
