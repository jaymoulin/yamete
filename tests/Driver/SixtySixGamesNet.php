<?php

namespace YameteTests\Driver;


class SixtySixGamesNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://66games.net/e5aa613e7de1edc10e1e7da103cfb48b-honey-deep';
        $driver = new \Yamete\Driver\SixtySixGamesNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(30, count($driver->getDownloadables()));
    }
}
