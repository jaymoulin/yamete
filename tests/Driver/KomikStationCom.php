<?php

namespace YameteTests\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase;

class KomikStationCom extends TestCase
{
    /**
     * @throws GuzzleException
     */
    public function testDownload()
    {
        $url = 'https://www.komikstation.com/manga/2-5-dimensional-seduction/';
        $driver = new \Yamete\Driver\KomikStationCom();
        $driver->setUrl($url);
        $this->assertTrue($driver->canHandle());
        $this->assertEquals(223, count($driver->getDownloadables()));
    }
}
