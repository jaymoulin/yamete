<?php

namespace YameteTests\Driver;


class SuperHentaiComicsNet extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://superhentaicomics.net/matemi-rise-of-dragons/';
        $driver = new \Yamete\Driver\SuperHentaiComicsNet();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(32, count($driver->getDownloadables()));
    }
}
