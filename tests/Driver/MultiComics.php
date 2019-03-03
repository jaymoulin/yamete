<?php

namespace YameteTests\Driver;


class MultiComics extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://multicomics.net/online/interracial-breeding-farm-ishu-kan-kouhai-bokujou/';
        $driver = new \Yamete\Driver\MultiComics();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(24, count($driver->getDownloadables()));
    }

}
