<?php

namespace YameteTests\Driver;


class FanFoxNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://fanfox.net/manga/masturbation_count/';
        $driver = new \Yamete\Driver\FanFoxNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(10, count($driver->getDownloadables()));
    }
}
