<?php

namespace YameteTests\Driver;


class BoysLoveMe extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://boyslove.me/boyslove/tattoo-violence/';
        $driver = new \Yamete\Driver\BoysLoveMe();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(45, count($driver->getDownloadables()));
    }
}
