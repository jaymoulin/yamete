<?php

namespace YameteTests\Driver;


class PorkyFapOrg extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://porkyfap.org/wreck-it-ralph-vanellope-von-schweetz/';
        $driver = new \Yamete\Driver\PorkyFapOrg();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(15, count($driver->getDownloadables()));
    }
}
