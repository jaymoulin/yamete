<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class ManganelosCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'http://manganelos.com/manga/free-dj-osananajimi-to-shichakushitsu-ni-iru-to';
        $driver = new \Yamete\Driver\ManganelosCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(56, count($driver->getDownloadables()));
    }
}
